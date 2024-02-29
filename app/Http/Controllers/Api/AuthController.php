<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['otpVerify', 'checkUserPhoneNumber']]);
    }


    public function checkUserPhoneNumber(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:10|unique:users',
        ]);

        $user_exists = User::where('is_delete', 0)->where('phone', $request->phone)->first();

        if(empty($user_exists)){

            // $user = User::create([
            //     'user_id' => '123123123123',//Str::uuid()->toString(),
            //     'phoness' => $request->phone,
            //     'otp' => '1234',
            //     'otp_valid_upto' => ,
            // ]);


            $user = new User();
            $user->user_id = Str::uuid()->toString();
            $user->phone = $request->phone;
            $user->otp = '1234';
            $user->otp_valid_upto = Carbon::now()->addMinutes(10)->toDateTimeString();
    
            $user->save();

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ]);

        }
        else{

            if($user->is_active == 0){

                return response()->json(['message' => 'User is not active.',], 403);
            }
            else{

                $credentials = $request->only('phone');

                $token = Auth::attempt($credentials);
                
                if (!$token) {
                    return response()->json([
                        'message' => 'Unauthorized',
                    ], 401);
                }

                $user = Auth::user();
                return response()->json([
                    'user' => $user,
                    'authorization' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
            }
        }
    }

    public function otpVerify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:10',
            'otp' => 'required|string|min:4|max:4',
            'fcm_token' => 'required|string',
        ]);

        $user = User::where('is_delete', 0)->where('phone', $request->phone)->first();

        if(empty($user)){

            return response()->json([
                'message' => 'User not found.',
            ], 403);

        }
        else{

            if($user->is_active == 0){

                return response()->json(['message' => 'User is not active.',], 403);
            }
            else{

                if($user->otp_valid_upto >= Carbon::now()->addMinutes(10)->toDateTimeString()){
                    return response()->json([
                        'message' => 'OTP is expired. Please resend OTP.',
                    ], 403);
                }

                if($user->otp == $request->otp){

                    $user->is_active = 1;
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                    
                    return response()->json([
                        'user' => $user,
                        'authorization' => [
                            //'token' => $token,
                            'type' => 'bearer',
                        ]
                    ]);

                }
                else{
                    return response()->json([
                        'message' => 'Invalid OTP. Try again.',
                    ], 403);
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
