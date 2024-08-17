<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_id',
        'block_name',
        'district_id',
        'district_name',
        'state_name',
        'state_id',
        'created_on',
        'created_by',
        'modified_on',
        'modified_by',
        'is_delete',
    ];

}
