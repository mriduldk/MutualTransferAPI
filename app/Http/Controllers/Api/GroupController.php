<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    
    public function createGroup(Request $request){

        $group = Group::where('group_is_deleted', 0)
            ->where('group_name', $request->group_name)
            ->where('group_created_by', $request->group_created_by)
            ->first();

        if(empty($group)){
            
            $group_new = new Group();
            $group_new->group_id = $request->group_id;
            $group_new->group_name = $request->group_name;
            $group_new->group_icon_url = $request->group_icon_url;
            $group_new->group_color = $request->group_color;
            $group_new->group_created_date_time_String = $request->group_created_date_time_String;
            $group_new->group_created_date_time_Long = $request->group_created_date_time_Long;
            $group_new->group_description = $request->group_description;
            $group_new->group_created_by = $request->group_created_by;
            $group_new->group_created_on = $request->group_created_on;

            $group_new->save();


            return response()->json([
                'message' => 'Group created successfully',
                'group' => $group_new
            ]);

        }
        else{
            return response()->json([
                'message' => 'Group name already exists',
            ], 403);
        }

    }

    public function getAllByUserID(Request $request){

        $group = Group::where('group_is_deleted', 0)
            ->where('group_created_by', $request->group_created_by)
            ->get();

        return response()->json([
            'message' => 'Group fetched successfully',
            'user' => $group
        ]);

    }


}
