<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;


    protected $fillable = [
        'coin_amount',
        'transaction_message',
        'transaction_done_for',
        'transaction_type', // credit or debit
        'transaction_category', // referral or paid etc..


        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete',
    ];




}
