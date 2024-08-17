<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

use App\Models\District;

class DistrictController extends Controller
{
    public function GetAllDistricts(Request $request)
    {

        $district = District::where('is_delete', 0)->get();

        return response()->json([
            'message' => 'All Districts',
            'status' => 200,
            'districts' => $district
        ]);
        
    }

    public function GetDistrictByName(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'district_name' => 'required|string|max:100',
        // ]);

        $district = District::where('is_delete', 0)->where('district_name', 'LIKE', '%' . $request->district_name . '%')->get();

        return response()->json([
            'message' => 'All Districts',
            'status' => 200,
            'districts' => $district
        ]);
        
    }

    public function GetDistrictsByStateAndDistrictName(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'state_id' => 'required|string|max:36',
        //     /** @query */
        //     'district_name' => 'required|string|max:200',
        // ]);

        $district = District::where('is_delete', 0)
                        ->where('state_id', $request->state_id)
                        ->where('district_name', 'LIKE', '%' . $request->district_name . '%')
                        ->get();


        return response()->json([
            'message' => 'All Districts',
            'status' => 200,
            'districts' => $district
        ]);
        
    }


    public function GetDistrictByState(Request $request)
    {

        // $request->validate([
        //     /** @query */
        //     'state_id' => 'required|string|max:36',
        // ]);

        $district = District::where('is_delete', 0)->where('state_id', $request->state_id)->get();

        return response()->json([
            'message' => 'All Districts',
            'status' => 200,
            'districts' => $district
        ]);
        
    }

}
