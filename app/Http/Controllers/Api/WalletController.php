<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Str;


class WalletController extends Controller
{

    public function UpdateWalletAmount($user_id, $total_amount)
    {

        $wallet_old = Wallet::where('is_delete', 0)->where('fk_user_id', $user_id)->first();

        if(empty($wallet_old)){

            $wallet = new Wallet();
            $wallet->wallet_id = Str::uuid()->toString();
            $wallet->fk_user_id = $user_id;
            $wallet->total_amount = $total_amount;
            $wallet->expired_on = Carbon::now()->toDateTimeString();

            $wallet->created_by = $user_id;
            $wallet->created_on = Carbon::now()->toDateTimeString();

            $wallet->save();
            
        }
        else{

            $wallet_old->total_amount = $wallet_old->total_amount + $total_amount;
            $wallet_old->expired_on = Carbon::now()->toDateTimeString();

            $wallet_old->modified_by = $user_id;
            $wallet_old->modified_on = Carbon::now()->toDateTimeString();

            $wallet_old->save();
        
        }

    }
}