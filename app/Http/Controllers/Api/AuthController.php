<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Support\Str;

use App\Models\PaymentConfig;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CoinTransactionController;


class AuthController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['otpVerify', 'checkUserPhoneNumber']]);
    }


    public function checkUserPhoneNumber(Request $request)
    {

        $request->validate([
            /** @query */
            'phone' => 'required|string|max:10',
        ]);

        $user_exists = User::where('is_delete', 0)->where('phone', $request->phone)->first();

        if(empty($user_exists)){

            $user = new User();
            $user->user_id = Str::uuid()->toString();
            $user->phone = $request->phone;
            $user->otp = '1234';
            $user->otp_valid_upto = Carbon::now()->addMinutes(10)->toDateTimeString();
    
            $user->save();


            $paymentConfig = PaymentConfig::first();

            $walletController = new WalletController();
            $walletController->UpdateWalletAmount($user->user_id, $paymentConfig->registration_amount);

            $coinTransactionController = new CoinTransactionController();
            $coinTransactionController->InsertCoinTransaction($user->user_id, $paymentConfig->registration_amount, $paymentConfig->registration_amount . ' coin credited for registration bonus.', 'CREDIT', 'SIGNUP');


            return response()->json([
                'message' => 'User created successfully',
                'status' => 200,
                'user' => $user
            ]);

        }
        else{

            if($user_exists->is_active == 0){

                return response()->json(['message' => 'User is not active.', 'status' => 403]);
            }
            else{

                $user_exists->otp = '1234';
                $user_exists->otp_valid_upto = Carbon::now()->addMinutes(10)->toDateTimeString();
        
                $user_exists->save();
    
                return response()->json([
                    'message' => 'OTP sent to phone number',
                    'status' => 200,
                    'user' => $user_exists
                ]);
               
            }
        }
    }

    public function otpVerify(Request $request)
    {
        $request->validate([
            /** @query */
            'phone' => 'required|string|max:10',
            /** @query */
            'otp' => 'required|string|min:4|max:4',
            /** @query */
            'fcm_token' => 'required|string',
        ]);

        $user = User::where('is_delete', 0)->where('phone', $request->phone)->first();

        if(empty($user)){

            return response()->json([
                'message' => 'User not found.',
                'status' => 403,
                'user' => null
            ]);

        }
        else{

            if($user->is_active == 0){

                return response()->json([
                    'message' => 'User is not active.',
                    'status' => 403,
                    'user' => null
                ]);
            }
            else{

                if($user->otp_valid_upto >= Carbon::now()->addMinutes(10)->toDateTimeString()){

                    return response()->json([
                        'message' => 'OTP is expired. Please resend OTP.',
                        'status' => 403,
                        'user' => null
                    ]);
                }

                if($user->otp == $request->otp){

                    $user->is_active = 1;
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                    

                    $userDetails = UserDetails::where('is_delete', 0)->where('fk_user_id', $user->user_id)->first();

                    return response()->json([
                        'message' => 'OTP verified successfully',
                        'status' => 200,
                        'user' => $user,
                        'userDetails' => $userDetails
                    ]);

                }
                else{

                    return response()->json([
                        'message' => 'Invalid OTP. Try again.',
                        'status' => 403,
                        'user' => null
                    ]);

                }
                
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}
