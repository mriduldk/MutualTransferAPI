<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Razorpay\Api\Api;

class OnlinePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'online_payment_id',
        'fk_user_id',
        'payment_date',
        'payment_mode',
        'payment_status',
        'amount_refunded',
        'refund_status',
        'captured',
        'card_id',
        'bank',
        'wallet',
        'vpa',
        'fee',
        'tax',
        'order_id',
        'entity',
        'coins',
        'amount',
        'amount_paid',
        'amount_due',
        'currency',
        'receipt',
        'offer_id',
        'status',
        'attempts',
        'notes',
        'order_created_at',
        'razorpay_payment_id',
        'razorpay_signature',
        'error_code',
        'error_description',
        'error_source',
        'error_step',
        'error_reason',
        'error_field',

    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'order_created_at' => 'datetime',
    ];


}
