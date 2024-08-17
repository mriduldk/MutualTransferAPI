<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

use App\Models\Block;

class BlockController extends Controller
{
    public function GetAllBlocks(Request $request)
    {

        $blocks = Block::where('is_delete', 0)->get();

        return response()->json([
            'message' => 'All Blocks',
            'status' => 200,
            'blocks' => $blocks
        ]);
        
    }

    public function GetBlocksByDistrict(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'district_id' => 'required|string|max:36',
        // ]);

        $blocks = Block::where('is_delete', 0)->where('district_id', $request->district_id)->get();

        return response()->json([
            'message' => 'Blocks By District',
            'status' => 200,
            'blocks' => $blocks
        ]);
        
    }

    public function GetBlocksByDistrictAndBlockName(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'district_id' => 'required|string|max:36',
        //     /** @query */
        //     'block_name' => 'required|string|max:200',
        // ]);

        $blocks = Block::where('is_delete', 0)
                        ->where('district_id', $request->district_id)
                        ->where('block_name', 'LIKE', '%' . $request->block_name . '%')
                        ->get();


        return response()->json([
            'message' => 'Blocks By District and Block Name',
            'status' => 200,
            'blocks' => $blocks
        ]);
        
    }


    public function GetBlocksByState(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'state_id' => 'required|string|max:36',
        // ]);

        $blocks = Block::where('is_delete', 0)->where('state_id', $request->state_id)->get();

        return response()->json([
            'message' => 'All Blocks',
            'status' => 200,
            'blocks' => $blocks
        ]);
        
    }







}
