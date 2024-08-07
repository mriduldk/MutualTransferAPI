<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;


    protected $fillable = [
        'wallet_id',
        'fk_user_id',
        'total_amount',
        'expired_on'
    ];

    
    protected $hidden = [
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete'
    ];


}
