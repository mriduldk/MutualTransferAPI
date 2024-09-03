<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

use App\Models\CoinTransaction;


class CoinTransactionController extends Controller
{
    public function InsertCoinTransaction($user_id, $coin_amount, $transaction_message, $transaction_type, $transaction_category)
    {
        $coinTransaction = new CoinTransaction();
            
        $coinTransaction->coin_amount = $coin_amount;
        $coinTransaction->transaction_message = $transaction_message;
        $coinTransaction->transaction_type = $transaction_type;
        $coinTransaction->transaction_category = $transaction_category;
        $coinTransaction->transaction_done_for = $user_id;

        $coinTransaction->created_by = $user_id;
        $coinTransaction->created_on = Carbon::now()->toDateTimeString();

        $coinTransaction->save();

    }
}
