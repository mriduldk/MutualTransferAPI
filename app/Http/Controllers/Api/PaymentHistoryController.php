<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\PaymentHistory;
use Illuminate\Support\Str;
use App\Http\Controllers\API\WalletController;


class PaymentHistoryController extends Controller
{
    public function SavePaymentHistory(Request $request)
    {

        $request->validate([
            /** @query */
            'user_id' => 'required|string|max:36',
            /** @query */
            'paid_amount' => 'required|integer|max:200',
            /** @query */
            'status' => 'required|string|max:20'
        ]);

        $paymentHistory = new PaymentHistory();
        $paymentHistory->payment_history_id = Str::uuid()->toString();
        $paymentHistory->fk_user_id = $request->user_id;
        $paymentHistory->paid_amount = $request->paid_amount;
        $paymentHistory->status = $request->status;

        $paymentHistory->created_by = $request->user_id;
        $paymentHistory->created_on = Carbon::now()->toDateTimeString();

        $paymentHistory->save();


        if($request->status == 'success'){

            // Call UpdateWalletAmount method from WalletController
            $walletController = new WalletController();
            $walletController->UpdateWalletAmount($request->user_id, $request->paid_amount);


            return response()->json([
                'message' => 'Payment Details saved successfully',
                'status' => 200,
                'paymentHistory' => $paymentHistory
            ]);

        }
        else{

            return response()->json([
                'message' => 'Payment Failed',
                'status' => 403,
                'paymentHistory' => null
            ]);

        }
    }
}
