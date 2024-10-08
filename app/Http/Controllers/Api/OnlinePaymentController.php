<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\OnlinePayment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

use App\Models\PaymentConfig;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CoinTransactionController;
use App\Services\FCMService;

class OnlinePaymentController extends Controller
{

    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }


    
    public function CreateOrder(Request $request) {


        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'coins' => 'required|string|max:36',
            /** @query */
            'amount' => 'required|string|max:10'
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

            $endpoint = 'https://api.razorpay.com/v1/orders';
            $keyId = config('app.RAZORPAY_KEY_ID');
            $keySecret = config('app.RAZORPAY_KEY_SECRET');
            $amount = $request->amount;
            $currency = 'INR';
            $receipt = 'MT-' . $userDetails->phone . '-' . rand(1000,9999);

            $client = new \GuzzleHttp\Client(
                [
                    'verify' => App::environment('local') ? false : true,
                    'headers' => [
                        'content-type' => 'application/json',
                    ]
                ]
            );

            $response = $client->request('POST', $endpoint, [
                'auth' => [$keyId, $keySecret],
                'json' => [
                    'receipt' => $receipt,
                    'amount' => $amount,
                    'currency' => $currency,
                ]
            ]);
            $data = json_decode($response->getBody());
            
            $onlinePayment = OnlinePayment::create([
                'online_payment_id' => Str::uuid(),
                'fk_user_id' => $request->user_id,
                'payment_date' => $data->created_at,
                'order_id' => $data->id,
                'entity' => $data->entity,
                'coins' => $request->coins,
                'amount' => $data->amount,
                'amount_paid' => $data->amount_paid,
                'amount_due' => $data->amount_due,
                'currency' => $data->currency,
                'receipt' => $data->receipt,
                'offer_id' => $data->offer_id,
                'status' => $data->status,
                'attempts' => $data->attempts,
                'order_created_at' => $data->created_at,
            ]);

            return response()->json([
                'message' => 'Order created successfully',
                'status' => 200,
                'onlinePayment' => $onlinePayment
            ]);

        }

    }


    public function VerifyPayment(Request $request) {


        $request->validate([
            /** @query */
            'online_payment_id' => 'required|string|max:36',
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'razorpay_signature' => 'required|string|max:200',
            /** @query */
            'razorpay_order_id' => 'required|string|max:36',
            /** @query */
            'razorpay_payment_id' => 'required|string|max:36'
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

            $onlinePayment = OnlinePayment::where("online_payment_id", $request->online_payment_id)->first();

            if(empty($onlinePayment)){

                return response()->json([
                    'message' => 'Payment Details not found',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else {

                $razorpayPaymentId = $request->razorpay_payment_id;
                $razorpayOrderId = $request->razorpay_order_id;
                $razorpaySignature = $request->razorpay_signature;

                
                $api = new Api(config('app.RAZORPAY_KEY_ID'), config('app.RAZORPAY_KEY_SECRET'));

                $attributes = [
                    'razorpay_order_id' => $onlinePayment->order_id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_signature' => $razorpaySignature
                ];


                try {
                    $api->utility->verifyPaymentSignature($attributes);
                } catch (\Exception $e) {

                    return response()->json([
                        'message' => 'Could not verify payment details. Try again.',
                        'status' => 409,
                        'userDetails' => null
                    ]);

                }


                $endpointOrder = 'https://api.razorpay.com/v1/orders/' . $onlinePayment->order_id;
                $endpointPayment = 'https://api.razorpay.com/v1/payments/' . $razorpayPaymentId;
                $keyId = config('app.RAZORPAY_KEY_ID');
                $keySecret = config('app.RAZORPAY_KEY_SECRET');

                $client = new \GuzzleHttp\Client(
                    [
                        'verify' => App::environment('local') ? false : true,
                        'headers' => [
                            'content-type' => 'application/json',
                        ]
                    ]
                );
                $responsePayment = $client->request('GET', $endpointPayment, [
                    'auth' => [$keyId, $keySecret],
                ]);
                $dataPayment = json_decode($responsePayment->getBody());
                $responseOrder = $client->request('GET', $endpointOrder, [
                    'auth' => [$keyId, $keySecret],
                ]);
                $dataOrder = json_decode($responseOrder->getBody());
                
                if ($dataOrder->status == "paid") {

                    $onlinePayment->update([
                        'razorpay_payment_id' => $razorpayPaymentId,
                        'razorpay_signature' => $razorpaySignature,
                        'payment_mode' => $dataPayment->method,
                        'payment_status' => $dataPayment->status,
                        'amount_refunded' => $dataPayment->amount_refunded,
                        'refund_status' => $dataPayment->refund_status,
                        'captured' => $dataPayment->captured,
                        'card_id' => $dataPayment->card_id,
                        'bank' => $dataPayment->bank,
                        'wallet' => $dataPayment->wallet,
                        'vpa' => $dataPayment->vpa,
                        'fee' => $dataPayment->fee,
                        'tax' => $dataPayment->tax,
                        'amount_paid' => $dataOrder->amount_paid,
                        'amount_due' => $dataOrder->amount_due,
                        'status' => $dataOrder->status,
                        'attempts' => $dataOrder->attempts,
                    ]);


                    $walletController = new WalletController();
                    $walletController->UpdateWalletAmount($request->user_id, $onlinePayment->coins);
        
                    $coinTransactionController = new CoinTransactionController();
                    $coinTransactionController->InsertCoinTransaction($request->user_id, $onlinePayment->coins, $onlinePayment->coins . ' coins purchased.', 'CREDIT', 'PURCHASED');

                    $userFCM = User::where('user_id', $request->user_id)->first();
                    $this->fcmService->sendNotificationToToken(
                        "Payment Successful", 
                        "Successfully purchased " . $onlinePayment->coins . " coins.", 
                        $userFCM->fcm_token
                    );

                    return response()->json([
                        'message' => 'Payment Successful.',
                        'status' => 200,
                        'onlinePayment' => $onlinePayment
                    ]);

                } 
                else if ($dataOrder->status == "attempted") {
                    sleep(5);
                    $responsePayment2 = $client->request('GET', $endpointPayment, [
                        'auth' => [$keyId, $keySecret],
                    ]);
                    $dataPayment2 = json_decode($responsePayment2->getBody());
        
                    $responseOrder2 = $client->request('GET', $endpointOrder, [
                        'auth' => [$keyId, $keySecret],
                    ]);
                    $dataOrder2 = json_decode($responseOrder2->getBody());
                    $onlinePayment->razorpay_payment_id = $razorpayPaymentId;
                    $onlinePayment->razorpay_signature = $razorpaySignature;
 
                    if ($dataPayment2 != null) {

                        $onlinePayment->update([
                            'payment_mode' => $dataPayment2->method,
                            'payment_status' => $dataPayment2->status,
                            'amount_refunded' => $dataPayment2->amount_refunded,
                            'refund_status' => $dataPayment2->refund_status,
                            'captured' => $dataPayment2->captured,
                            'card_id' => $dataPayment2->card_id,
                            'bank' => $dataPayment2->bank,
                            'wallet' => $dataPayment2->wallet,
                            'vpa' => $dataPayment2->vpa,
                            'fee' => $dataPayment2->fee,
                            'tax' => $dataPayment2->tax,
                        ]);
                    }
        
                    if ($dataOrder2 != null) {

                        $onlinePayment->update([
                            'amount_paid' => $dataOrder2->amount_paid,
                            'amount_due' => $dataOrder2->amount_due,
                            'status' => $dataOrder2->status,
                            'attempts' => $dataOrder2->attempts,
                        ]);
                    }
        
                    $walletController = new WalletController();
                    $walletController->UpdateWalletAmount($request->user_id, $onlinePayment->coins);
        
                    $coinTransactionController = new CoinTransactionController();
                    $coinTransactionController->InsertCoinTransaction($request->user_id, $onlinePayment->coins, $onlinePayment->coins . ' coins purchased.', 'CREDIT', 'PURCHASED');

                    $userFCM = User::where('user_id', $request->user_id)->first();
                    $result = $this->fcmService->sendNotificationToToken(
                        "Payment Successful", 
                        "Successfully purchased " . $onlinePayment->coins . " coins.", 
                        $userFCM->fcm_token
                    );
                    

                    if ($dataOrder->status == "paid") {
        
                        return response()->json([
                            'message' => 'Payment Successful.',
                            'status' => 200,
                            'onlinePayment' => $onlinePayment
                        ]);
                    } else {
        
                        return response()->json([
                            'message' => 'Payment Processing.',
                            'status' => 200,
                            'onlinePayment' => $onlinePayment
                        ]);
                    }
                } 
                else {

                    $onlinePayment->update([
                        'razorpay_payment_id' => $razorpayPaymentId,
                        'razorpay_signature' => $razorpaySignature,
                        'payment_mode' => $dataPayment->method,
                        'payment_status' => $dataPayment->status,
                        'amount_refunded' => $dataPayment->amount_refunded,
                        'refund_status' => $dataPayment->refund_status,
                        'captured' => $dataPayment->captured,
                        'card_id' => $dataPayment->card_id,
                        'bank' => $dataPayment->bank,
                        'wallet' => $dataPayment->wallet,
                        'vpa' => $dataPayment->vpa,
                        'fee' => $dataPayment->fee,
                        'tax' => $dataPayment->tax,
                        'amount_paid' => $dataOrder->amount_paid,
                        'amount_due' => $dataOrder->amount_due,
                        'status' => $dataOrder->status,
                        'attempts' => $dataOrder->attempts,
                    ]);
        
                    return response()->json([
                        'message' => 'Payment Failed.',
                        'status' => 200,
                        'onlinePayment' => $onlinePayment
                    ]);

                }
            }

        }

    }

    public function RefreshPaymentStatusWithPaymentId(Request $request) {

        $request->validate([
            /** @query */
            'online_payment_id' => 'required|string|max:36',
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'razorpay_order_id' => 'required|string|max:36',
            /** @query */
            'razorpay_payment_id' => 'required|string|max:36'
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

            $onlinePayment = OnlinePayment::where("online_payment_id", $request->online_payment_id)->first();

            if(empty($onlinePayment)){

                return response()->json([
                    'message' => 'Payment Details not found',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else if($onlinePayment->order_id != $request->razorpay_order_id){

                return response()->json([
                    'message' => 'Payment Details Mismatch',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else {

                $endpointOrder = 'https://api.razorpay.com/v1/orders/' . $onlinePayment->order_id;
                $endpointPayment = 'https://api.razorpay.com/v1/payments/' . $request->razorpay_payment_id;

                $keyId = config('app.RAZORPAY_KEY_ID');
                $keySecret = config('app.RAZORPAY_KEY_SECRET');

                $client = new \GuzzleHttp\Client(
                    [
                        'verify' => App::environment('local') ? false : true,
                        'headers' => [
                            'content-type' => 'application/json',
                        ]
                    ]
                );
                $responsePayment = $client->request('GET', $endpointPayment, [
                    'auth' => [$keyId, $keySecret],
                ]);
                $dataPayment = json_decode($responsePayment->getBody());

                $responseOrder = $client->request('GET', $endpointOrder, [
                    'auth' => [$keyId, $keySecret],
                ]);
                $dataOrder = json_decode($responseOrder->getBody());


                if ($dataPayment != null) {
                    $onlinePayment->update([
                        'payment_mode' => $dataPayment->method,
                        'payment_status' => $dataPayment->status,
                        'amount_refunded' => $dataPayment->amount_refunded,
                        'refund_status' => $dataPayment->refund_status,
                        'captured' => $dataPayment->captured,
                        'card_id' => $dataPayment->card_id,
                        'bank' => $dataPayment->bank,
                        'wallet' => $dataPayment->wallet,
                        'vpa' => $dataPayment->vpa,
                        'fee' => $dataPayment->fee,
                        'tax' => $dataPayment->tax,

                        'error_code' => $dataPayment->error_code,
                        'error_description' => $dataPayment->error_description,
                        'error_source' => $dataPayment->error_source,
                        'error_step' => $dataPayment->error_step,
                        'error_reason' => $dataPayment->error_reason,
                        'razorpay_payment_id' => $request->razorpay_payment_id,
                    ]);
                }

                if ($dataOrder != null) {
                    $onlinePayment->update([
                        'amount_paid' => $dataOrder->amount_paid,
                        'amount_due' => $dataOrder->amount_due,
                        'status' => $dataOrder->status,
                        'attempts' => $dataOrder->attempts,
                    ]);
                }

                $onlinePayment->save();
                

                if ($dataOrder->status == "paid") {

                    return response()->json([
                        'message' => 'Payment Successful',
                        'status' => 200,
                        'onlinePayment' => $onlinePayment,
                    ]);


                } 
                else {
        
                    return response()->json([
                        'message' => 'Payment Processing',
                        'status' => 200,
                        'onlinePayment' => $onlinePayment,
                    ]);


                }

            }

        }

    }

    public function RefreshPaymentStatusWithOrderId(Request $request) {

        $request->validate([
            /** @query */
            'online_payment_id' => 'required|string|max:36',
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'razorpay_order_id' => 'required|string|max:36',
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

            $onlinePayment = OnlinePayment::where("online_payment_id", $request->online_payment_id)->first();

            if(empty($onlinePayment)){

                return response()->json([
                    'message' => 'Payment Details not found',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else if($onlinePayment->order_id != $request->razorpay_order_id){

                return response()->json([
                    'message' => 'Payment Details Mismatch',
                    'status' => 400,
                    'userDetails' => null
                ]);
    
            }
            else {

                $api = new Api(config('app.RAZORPAY_KEY_ID'), config('app.RAZORPAY_KEY_SECRET'));
                $order = $api->order->fetch($onlinePayment->order_id);

                foreach ($order->payments()->items as $payment) {

                    $onlinePayment->update([
                        'entity' => $order->entity,
                        'amount' => $order->amount,
                        'amount_paid' => $order->amount_paid,
                        'amount_due' => $order->amount_due,
                        'currency' => $order->currency,
                        'receipt' => $order->receipt,
                        'offer_id' => $order->offer_id,
                        'status' => $order->status,
                        'attempts' => $order->attempts,
                        'order_created_at' => $order->created_at,
                        'payment_mode' => $payment->method,
                        'payment_status' => $payment->status,
                        'amount_refunded' => $payment->amount_refunded,
                        'refund_status' => $payment->refund_status,
                        'captured' => $payment->captured,
                        'card_id' => $payment->card_id,
                        'bank' => $payment->bank,
                        'wallet' => $payment->wallet,
                        'vpa' => $payment->vpa,
                        'fee' => $payment->fee,
                        'tax' => $payment->tax,
                        'razorpay_payment_id' => $payment->id,

                        'error_code' => $payment->error_code,
                        'error_description' => $payment->error_description,
                        'error_source' => $payment->error_source,
                        'error_step' => $payment->error_step,
                        'error_reason' => $payment->error_reason

                    ]);

                    if ($payment->status == "captured") {
                        break;
                    }
                }

                return response()->json([
                    'message' => 'Payment Details Refreshed Successfully',
                    'status' => 200,
                    'onlinePayment' => $onlinePayment
                ]);

            }

        }


    }

    public function GetUsersPaymentDetails(Request $request) {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
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

            $onlinePayments = OnlinePayment::where("fk_user_id", $request->user_id)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'message' => 'Payment Details Fetched Successfully',
                'status' => 200,
                'onlinePayments' => $onlinePayments
            ]);

        }



    }



}
