<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subject;

class SubjectController extends Controller
{
    
    public function GetAllHighSecondarySubject(Request $request)
    {
        $subjects = Subject::where('type', 'HIGH_SECONDARY')->where('is_delete', 0)->get();

        return response()->json([
            'message' => 'All Subjects',
            'status' => 200,
            'subjects' => $subjects
        ]);
        
    }



}
