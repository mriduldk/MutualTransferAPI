<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'fk_user_id',
        'paid_amount',
        'status'
    ];

    
    protected $hidden = [
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete'
    ];


}
