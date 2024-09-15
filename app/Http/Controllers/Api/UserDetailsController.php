<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\PaymentConfig;
use Illuminate\Support\Str;

use App\Models\UserDetails;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CoinTransactionController;
use App\Services\FCMService;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class UserDetailsController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    
    public function SaveUserPersonalInformation(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'name' => 'required|string|max:200',
            /** @query */
            'email' => 'nullable|string|max:50',
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

                $userDetailsNew->my_referral_code = $this->GenerateReferralCode();
    
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
            'subject_type' => 'nullable|string|max:50'
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
            'school_address_pin' => 'required|string|max:6',
            /** @query */
            'amalgamation' => 'nullable|integer'
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
            $userDetails->amalgamation = $request->amalgamation;

            $userDetails->modified_by = $request->user_id;
            $userDetails->modified_on = Carbon::now()->toDateTimeString();

            $userDetails->save();

            // $result = $this->fcmService->sendNotificationToTopic(
            //     "New User Alert!", 
            //     "Someone new has registered in your preferred district. There could be a match with your profile. Check it out!", 
            //     "PREFERRED_DISTRICT_" . str_replace(' ', '_', $request->school_address_district)
            // );


            return response()->json([
                'message' => 'User Details updated successfully ',
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
            'preferred_district_2' => 'nullable|string|max:100',
            /** @query */
            'preferred_district_3' => 'nullable|string|max:100',
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

            // $result = $this->fcmService->sendNotificationToTopic(
            //     "New User Alert!", 
            //     "Someone new has registered in your district as preferred. There could be a match with your profile. Check it out!", 
            //     "SCHOOL_DISTRICT_" . str_replace(' ', '_', $request->preferred_district_1)
            // );

            // $result = $this->fcmService->sendNotificationToTopic(
            //     "New User Alert!", 
            //     "Someone new has registered in your district as preferred. There could be a match with your profile. Check it out!", 
            //     "SCHOOL_DISTRICT_" . str_replace(' ', '_', $request->preferred_district_2)
            // );

            // $result = $this->fcmService->sendNotificationToTopic(
            //     "New User Alert!", 
            //     "Someone new has registered in your district as preferred. There could be a match with your profile. Check it out!", 
            //     "SCHOOL_DISTRICT_" . str_replace(' ', '_', $request->preferred_district_3)
            // );


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

    public function UseReferralCode(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'referral_code' => 'required|string|max:10',
        ]);

        $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $request->user_id)->first();

        if(empty($userDetails)){

            return response()->json([
                'message' => 'User Details Not Found',
                'status' => 400,
                'userDetails' => null
            ]);

        }
        else if($userDetails->is_referral_code_used){

            return response()->json([
                'message' => 'You have already use referral code. You can only use once.',
                'status' => 403,
                'userDetails' => null
            ]);

        }
        else if($userDetails->my_referral_code == $request->referral_code){

            return response()->json([
                'message' => 'You can not use your own referral code.',
                'status' => 403,
                'userDetails' => null
            ]);

        }
        else{

            $userDetailsWithReferralCode = UserDetails::where('is_delete', 0)->where('my_referral_code', $request->referral_code)->first();

            if(empty($userDetailsWithReferralCode)) {

                return response()->json([
                    'message' => 'Referral Code is not valid.',
                    'status' => 403,
                    'userDetails' => null
                ]);

            }
            else{

                $userDetails->used_referral_code = $request->referral_code;
                $userDetails->is_referral_code_used = 1;
    
                $userDetails->modified_by = $request->user_id;
                $userDetails->modified_on = Carbon::now()->toDateTimeString();
    
                $userDetails->save();
    

                $paymentConfig = PaymentConfig::first();
                //$paymentConfig = PaymentConfig::where('is_delete', 0)->first();

                // Call UpdateWalletAmount method from WalletController
                $walletController = new WalletController();
                $walletController->UpdateWalletAmount($userDetailsWithReferralCode->fk_user_id, $paymentConfig->referral_amount_for_giver);
                $walletController->UpdateWalletAmount($request->user_id, $paymentConfig->referral_amount_for_taker);

                // coin transaction
                $coinTransactionController = new CoinTransactionController();
                $coinTransactionController->InsertCoinTransaction($userDetailsWithReferralCode->fk_user_id, $paymentConfig->referral_amount_for_giver, $paymentConfig->referral_amount_for_giver . ' coin credited for referral.', 'CREDIT', 'REFERRAL');
                $coinTransactionController->InsertCoinTransaction($request->user_id, $paymentConfig->referral_amount_for_taker, $paymentConfig->referral_amount_for_taker . ' coin credited for referral.', 'CREDIT', 'REFERRAL');


                return response()->json([
                    'message' => 'Referral code used successfully',
                    'status' => 200,
                    'userDetails' => $userDetails
                ]);

            }

        }
    }
    
    private function GenerateReferralCode() {

        $randomString = Str::random(6);

        $randomString = strtoupper(preg_replace('/[^A-Z0-9]/', '', $randomString));
        
        while (strlen($randomString) < 6) {
            $randomString .= strtoupper(Str::random(6 - strlen($randomString)));
            $randomString = strtoupper(preg_replace('/[^A-Z0-9]/', '', $randomString));
        }
        
        $randomString = substr($randomString, 0, 6);

        return $randomString;
    }



}
