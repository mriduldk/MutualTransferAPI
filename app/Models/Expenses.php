<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expenses_id',
        'expenses_amount',
        'expenses_description',
        'expenses_date_time',
        'expenses_date',
        'expenses_time',
        'expenses_group_id',
        'expenses_paidBy',
        'expenses_paidById',
        'expenses_splitType',
        'expenses_is_deleted',
        'expenses_is_paid',
        'expenses_paid_date_time',
        'expenses_created_by',
        'expenses_created_on',
        'expenses_modified_by',
        'expenses_modified_on',
        'expenses_deleted_by',
        'expenses_deleted_on',

    ];


}
