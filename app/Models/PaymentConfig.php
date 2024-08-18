<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount_per_person',
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete',
    ];


}
