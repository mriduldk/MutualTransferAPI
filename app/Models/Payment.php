<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'payment_done_by',
        'payment_done_for',
        'amount',
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete',
    ];


    protected $hidden = [
        'is_delete',
        'created_at',
        'updated_at',
        'id'
    ];


}
