<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Payment;
use App\Models\PaymentConfig;
use App\Models\Wallet;
use App\Services\FCMService;
use Razorpay\Api\Api;

use App\Http\Controllers\Api\CoinTransactionController;

class PaymentController extends Controller
{

    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    
    public function SaveUserPayForAnotherUser(Request $request)
    {

        $request->validate([
            /** @query */
            'payment_done_by' => 'required|string|max:36',
            /** @query */
            'payment_done_for' => 'required|string|max:36',
            /** @query */
            'amount' => 'string|max:10'
        ]);

        $paymentOld = Payment::where('payment_done_by', $request->payment_done_by)->where('payment_done_for', $request->payment_done_for)->first();
        $wallet = Wallet::where('is_delete', 0)->where('fk_user_id', $request->payment_done_by)->first();

        $paymentConfig = PaymentConfig::where('is_delete', 0)->first();
        
        if(empty($paymentConfig)) {
            return response()->json([
                'message' => 'Payment Amount Unableable. Please contact admin.',
                'status' => 403,
                'payment' => null
            ]);
        }

        if($paymentConfig->amount_per_person != $request->amount){
            return response()->json([
                'message' => 'Payment Amount Mismatch ',
                'status' => 403,
                'payment' => null
            ]);
        }

        if(empty($wallet)) {
            return response()->json([
                'message' => 'No wallet amount. Please add your wallet amount first.',
                'status' => 409,
                'payment' => null
            ]);
        }

        if($wallet->total_amount < $paymentConfig->amount_per_person) {
            return response()->json([
                'message' => 'No sufficiant balance in wallet.',
                'status' => 409,
                'payment' => null
            ]);
        }

        if(empty($paymentOld)){


            // Save Payment 
            $payment = new Payment();

            $payment->payment_id = Str::uuid()->toString();
            $payment->payment_done_by = $request->payment_done_by;
            $payment->payment_done_for = $request->payment_done_for;
            $payment->amount = $request->amount;

            $payment->created_by = $request->payment_done_by;
            $payment->created_on = Carbon::now()->toDateTimeString();

            $payment->save();


            // Update Wallet
            $wallet->total_amount = $wallet->total_amount - $paymentConfig->amount_per_person;
            $wallet->expired_on = Carbon::now()->toDateTimeString();

            $wallet->modified_by = $request->payment_done_by;
            $wallet->modified_on = Carbon::now()->toDateTimeString();

            $wallet->save();


            // Save coin transaction
            $coinTransactionController = new CoinTransactionController();
            $coinTransactionController->InsertCoinTransaction($request->payment_done_by, $paymentConfig->amount_per_person, $paymentConfig->amount_per_person . ' coins debited for profile view.', 'DEBIT', 'PROFILE VIEW');



            $userFCM = User::where('user_id', $request->payment_done_for)->first();
            $result = $this->fcmService->sendNotificationToToken(
                "Profile Purchase Alert!", 
                "Someone bought and viewed your profile. Take a look to connect!", 
                $userFCM->fcm_token
            );

            return response()->json([
                'message' => 'Payment Done',
                'status' => 200,
                'payment' => $payment
            ]);

        }
        else{

            return response()->json([
                'message' => 'Already Paid',
                'status' => 409,
                'payment' => null
            ]);

        }
    }


}
