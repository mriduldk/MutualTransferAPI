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


class UserDetailsController extends Controller
{
    
    public function SaveUserPersonalInformation(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'name' => 'required|string|max:200',
            /** @query */
            'email' => 'string|max:50',
            /** @query */
            'gender' => 'required|string|max:6'
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();


        if(empty($userDetails)){

            $user = User::where('is_delete', 0)->where('user_id', $request->user_id)->first();


            if(empty($user)){

                return response()->json([
                    'message' => 'User Details Not Found',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else {
    
                $userDetailsNew = new UserDetails();
                $userDetailsNew->user_details_id = Str::uuid()->toString();
                $userDetailsNew->name = $request->name;
                $userDetailsNew->email = $request->email;
                $userDetailsNew->phone = $user->phone;
                $userDetailsNew->gender = $request->gender;
                $userDetailsNew->fk_user_id = $request->user_id;
    
                $userDetailsNew->created_by = $request->user_id;
                $userDetailsNew->created_on = Carbon::now()->toDateTimeString();
        
                $userDetailsNew->save();
    
                return response()->json([
                    'message' => 'User Details saved successfully',
                    'status' => 200,
                    'userDetails' => $userDetailsNew
                ]);
            }

        }
        else{

            $userDetails->name = $request->name;
            $userDetails->email = $request->email;
            $userDetails->gender = $request->gender;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            return response()->json([
                'message' => 'User Details updated successfully',
                'status' => 200,
                'userDetails' => $userDetails
            ]);

        }
    }

    public function SaveUserEmployeeDetails(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'employee_code' => 'required|string|max:200',
            /** @query */
            'school_type' => 'required|string|max:50',
            /** @query */
            'teacher_type' => 'required|string|max:50',
            /** @query */
            'subject_type' => 'string|max:50'
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();


        if(empty($userDetails)){

            $user = User::where('is_delete', 0)->where('user_id', $request->user_id)->first();


            if(empty($user)){

                return response()->json([
                    'message' => 'User Details Not Found',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else {
    
                $userDetailsNew = new UserDetails();
                $userDetailsNew->user_details_id = Str::uuid()->toString();
                $userDetailsNew->phone = $user->phone;
                $userDetailsNew->fk_user_id = $request->user_id;
    
                $userDetailsNew->employee_code = $request->employee_code;
                $userDetailsNew->school_type = $request->school_type;
                $userDetailsNew->teacher_type = $request->teacher_type;
                $userDetailsNew->subject_type = $request->subject_type;

                $userDetailsNew->created_by = $request->user_id;
                $userDetailsNew->created_on = Carbon::now()->toDateTimeString();
        
                $userDetailsNew->save();
    
                return response()->json([
                    'message' => 'User Details saved successfully',
                    'status' => 200,
                    'userDetails' => $userDetailsNew
                ]);
            }

        }
        else{

            $userDetails->employee_code = $request->employee_code;
            $userDetails->school_type = $request->school_type;
            $userDetails->teacher_type = $request->teacher_type;
            $userDetails->subject_type = $request->subject_type;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            return response()->json([
                'message' => 'User Details updated successfully',
                'status' => 200,
                'userDetails' => $userDetails
            ]);

        }
    }

    public function SaveUserSchoolDetails(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'school_name' => 'required|string|max:200',
            /** @query */
            'udice_code' => 'required|string|max:50',
            /** @query */
            'school_address_vill' => 'required|string|max:100',
            /** @query */
            'school_address_district' => 'required|string|max:50',
            /** @query */
            'school_address_block' => 'required|string|max:50',
            /** @query */
            'school_address_state' => 'required|string|max:50',
            /** @query */
            'school_address_pin' => 'required|string|max:6'
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();


        if(empty($userDetails)){

            return response()->json([
                'message' => 'User Details Not Found',
                'status' => 400,
                'userDetails' => null
            ]);

        }
        else{

            $userDetails->school_name = $request->school_name;
            $userDetails->udice_code = $request->udice_code;
            $userDetails->school_address_vill = $request->school_address_vill;
            $userDetails->school_address_district = $request->school_address_district;
            $userDetails->school_address_block = $request->school_address_block;
            $userDetails->school_address_state = $request->school_address_state;
            $userDetails->school_address_pin = $request->school_address_pin;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            return response()->json([
                'message' => 'User Details updated successfully',
                'status' => 200,
                'userDetails' => $userDetails
            ]);

        }
    }

    public function SaveUserPreferredDistrict(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'preferred_district_1' => 'required|string|max:100',
            /** @query */
            'preferred_district_2' => 'required|string|max:100',
            /** @query */
            'preferred_district_3' => 'required|string|max:100',
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();


        if(empty($userDetails)){

            return response()->json([
                'message' => 'User Details Not Found',
                'status' => 400,
                'userDetails' => null
            ]);

        }
        else{

            $userDetails->preferred_district_1 = $request->preferred_district_1;
            $userDetails->preferred_district_2 = $request->preferred_district_2;
            $userDetails->preferred_district_3 = $request->preferred_district_3;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            return response()->json([
                'message' => 'User Details updated successfully',
                'status' => 200,
                'userDetails' => $userDetails
            ]);

        }
    }

    public function ChangeActivelyLookingStatus(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'is_actively_looking' => 'required|integer',
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();


        if(empty($userDetails)){

            return response()->json([
                'message' => 'User Details Not Found',
                'status' => 400,
                'userDetails' => null
            ]);

        }
        else{

            $userDetails->is_actively_looking = $request->is_actively_looking;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            return response()->json([
                'message' => 'User Details updated successfully',
                'status' => 200,
                'userDetails' => $userDetails
            ]);

        }
    }

    

}
