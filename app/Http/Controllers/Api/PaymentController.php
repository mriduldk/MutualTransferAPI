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

class PaymentController extends Controller
{
    
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


        if(empty($paymentOld)){

            $payment = new Payment();

            $payment->payment_id = Str::uuid()->toString();
            $payment->payment_done_by = $request->payment_done_by;
            $payment->payment_done_for = $request->payment_done_for;
            $payment->amount = $request->amount;

            $payment->created_by = $request->payment_done_by;
            $payment->created_on = Carbon::now()->toDateTimeString();

            $payment->save();

            return response()->json([
                'message' => 'Payment Done',
                'status' => 200,
                'payment' => $payment
            ]);

        }
        else{

            return response()->json([
                'message' => 'Already Paid',
                'status' => 403,
                'payment' => null
            ]);

        }
    }
}
