<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\WalletOffer;
use Illuminate\Support\Str;

use App\Models\UserDetails;



class WalletOfferController extends Controller
{
    public function GetWalletOffers(Request $request)
    {
        $walletOffer = WalletOffer::where('is_delete', 0)->get();

        return response()->json([
            'message' => 'Wallet Offer Fetched Successfully',
            'status' => 200,
            'walletOffers' => $walletOffer
        ]);
       
    }

    
}
