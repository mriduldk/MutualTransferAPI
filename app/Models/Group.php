<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'group_name',
        'group_icon_url',
        'group_color',
        'group_created_date_time_String',
        'group_created_date_time_Long',
        'group_description',
        'group_is_deleted',
        'group_is_pinned',
        'group_is_important',
        'total_item_available',
        'group_created_by',
        'group_created_on',
        'group_modified_by',
        'group_modified_on',
        'group_deleted_by',
        'group_deleted_on',
    ];


}
