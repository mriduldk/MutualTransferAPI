<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_details_id',
        'fk_user_id',
        'name',
        'gender',
        'employee_code',
        'school_type',
        'teacher_type',
        'subject_type',
        'school_name',
        'school_address_vill',
        'school_address_district',
        'school_address_block',
        'school_address_state',
        'school_address_pin',
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete',
        'is_actively_looking',
    ];

    protected $hidden = [
        'is_delete',
        'remember_token'
    ];


}