<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesContact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'contact_name',
        'contact_number',
        'contact_paidBy',
        'contact_excludedFromEqualShare',
        'contact_paidAmount',
        'contact_equalShare',
        'contact_extraShare',
        'contact_totalShare',
        'contact_amount_get',
        'contact_amount_give',
        'contact_amount_get_from',
        'amount_give_to',

        'fk_expenses_id',

        'contact_created_by',
        'contact_created_on',
        'contact_modified_by',
        'contact_modified_on',
        'contact_deleted_by',
        'contact_deleted_on',
        'contact_is_deleted',

    ];


    protected $casts = [
        'contact_paidBy' => 'boolean',
        'contact_excludedFromEqualShare' => 'boolean',
    ];

}
