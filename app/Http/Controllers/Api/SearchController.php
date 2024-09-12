<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

use App\Models\UserDetails;
use App\Models\Payment;
use App\Models\PaymentConfig;


class SearchController extends Controller
{
    
    public function SearchPerson(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'school_address_district' => 'required|string|max:200',
            /** @query */
            'school_address_block' => 'nullable|string|max:50',
            /** @query */
            'school_address_vill' => 'nullable|string|max:50',
            /** @query */
            'school_name' => 'nullable|string|max:50'
        ]);

        $userId = $request->user_id;

        // Retrieve the searcher's districts
        $user = UserDetails::where('fk_user_id', $userId)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404, 'searchResult' => []]);
        }
        
        //$amount_per_person = "";
        // $paymentConfig = PaymentConfig::where('is_delete', 0)->first();
        // if(empty($paymentConfig)){
        //     $amount_per_person = "25";
        // }
        // else{
        //     $amount_per_person = $paymentConfig->amount_per_person;
        // }
        
        $amount_per_person = PaymentConfig::where('is_delete', 0)->value('amount_per_person') ?? '25';

        $searcherDistrict = $user->school_address_district;
        $school_type = $user->school_type;
        $teacher_type = $user->teacher_type;

        // Build the query dynamically using the 'when' method
        $query = UserDetails::query()
            ->leftJoin('payments', function($join) use ($userId) {
                $join->on('user_details.fk_user_id', '=', 'payments.payment_done_for')
                     ->where('payments.payment_done_by', '=', $userId);
            })
            ->select('user_details.*')
            ->selectRaw(
                "CASE
                    WHEN preferred_district_1 = ? OR preferred_district_2 = ? OR preferred_district_3 = ? THEN 1
                    ELSE 0
                 END AS district_match_flag", 
                [$searcherDistrict, $searcherDistrict, $searcherDistrict]
            ) // Add a flag for district match

            ->where('school_address_district', $request->school_address_district)
            ->where('user_details.fk_user_id', '!=', $userId)  // Exclude current user
            ->where('user_details.school_type', '=', $school_type)  // School Type Same
            ->where('user_details.teacher_type', '=', $teacher_type)  // Teacher Type Same
            ->where('user_details.is_actively_looking', '=', 1)  

            ->when($request->filled('school_address_block'), function ($q) use ($request) {
                $q->where('school_address_block', 'like', '%' . $request->school_address_block . '%');
            })
            ->when($request->filled('school_address_vill'), function ($q) use ($request) {
                $q->where('school_address_vill', 'like', '%' . $request->school_address_vill . '%');
            })
            ->when($request->filled('school_name'), function ($q) use ($request) {
                $q->where('school_name', 'like', '%' . $request->school_name . '%');
            })
            ->orderByRaw(
                "CASE 
                    WHEN preferred_district_1 = ? THEN 1
                    WHEN preferred_district_2 = ? THEN 2
                    WHEN preferred_district_3 = ? THEN 3
                    ELSE 5 
                END", [$searcherDistrict, $searcherDistrict, $searcherDistrict]
            )
            ->orderBy('user_details.name');


        $results = $query->get();

        // Transform the results to mask the name field
        $maskedResults = $results->transform(function ($item) use ($amount_per_person) {

            $item->is_paid = is_null($item->payment_id) ? 0 : 1;
            $item->pay_to_view_amount = $amount_per_person;
            
            if (is_null($item->payment_id)) {
                $item->name = $this->maskParameter($item->name);
                $item->email = $this->maskParameter($item->email);
                $item->phone = $this->maskParameter($item->phone);
                $item->employee_code = $this->maskParameter($item->employee_code);
                $item->school_name = $this->maskParameter($item->school_name);
                $item->udice_code = $this->maskParameter($item->udice_code);
                $item->school_address_vill = $this->maskParameter($item->school_address_vill);
            }
            
            return $item;
        });


        if ($results->isEmpty()) {
            return response()->json([
                'message' => 'No matching records found',
                'status' => 404,
                'searchResult' => []
            ]);
        } else {
            return response()->json([
                'message' => 'Records found',
                'status' => 200,
                'searchResult' => $maskedResults
            ]);
        }
        
    }

    public function ViewPersonDetails(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'person_user_id' => 'required|string|max:36',
        ]);

        $userId = $request->user_id;
        $amount_per_person = "";
        $paymentConfig = PaymentConfig::where('is_delete', 0)->first();
        if(empty($paymentConfig)){
            $amount_per_person = "25";
        }
        else{
            $amount_per_person = $paymentConfig->amount_per_person;
        }


        // Build the query dynamically using the 'when' method
        $results = UserDetails::query()
            ->leftJoin('payments', function($join) use ($userId) {
                $join->on('user_details.fk_user_id', '=', 'payments.payment_done_for')
                     ->where('payments.payment_done_by', '=', $userId);
            })
            ->where('user_details_id', $request->person_user_id)
            ->first();


        if ($results) {

            $results->is_paid = is_null($results->payment_id) ? 0 : 1;
            $results->pay_to_view_amount = $amount_per_person;

            // Transform the results to mask the name field
            if (is_null($results->payment_id)) {
                $results->name = $this->maskParameter($results->name);
                $results->email = $this->maskParameter($results->email);
                $results->phone = $this->maskParameter($results->phone);
                $results->employee_code = $this->maskParameter($results->employee_code);
                $results->school_name = $this->maskParameter($results->school_name);
                $results->udice_code = $this->maskParameter($results->udice_code);
                $results->school_address_vill = $this->maskParameter($results->school_address_vill);
            }

            return response()->json([
                'message' => 'Records found',
                'status' => 200,
                'personDetails' => $results
            ]);
            
        } else {
            
            return response()->json([
                'message' => 'No matching records found',
                'status' => 404,
                'personDetails' => null
            ]);
        }
        
    }

    // Function to mask the Parameter
    protected function maskParameter($name)
    {
        if (strlen($name) <= 2) {
            return str_repeat('*', strlen($name));
        }

        $firstChar = $name[0];
        $secondChar = $name[1];
        //$lastChar = $name[strlen($name) - 1];

        return $firstChar . $secondChar . str_repeat('*', strlen($name) - 2);
    }



}
