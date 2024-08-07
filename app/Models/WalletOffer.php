<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_offer_id',
        'total_amount',
        'total_coin',
        'discount',
        'message',
        'is_new'
    ];

    
    protected $hidden = [
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete'
    ];

}
