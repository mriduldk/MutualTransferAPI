<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenses;
use \Carbon\Carbon;


class ExpensesController extends Controller
{
    
    public function createExpenses(Request $request){

        $expenses = new Expenses();

        $expenses->expenses_id = $request->expenses_id;
        $expenses->expenses_amount = $request->expenses_amount;
        $expenses->expenses_description = $request->expenses_description;
        //$expenses->expenses_date_time = $request->expenses_date_time;
        $expenses->expenses_date = $request->expenses_date;
        $expenses->expenses_group_id = $request->expenses_group_id;
        
        $expenses->expenses_created_by = $request->expenses_created_by;
        $expenses->expenses_created_on = Carbon::now()->toDateTimeString();;

        $expenses->save();


        return response()->json([
            'message' => 'Expense Add Successfully',
            'status' => 200
        ]);

    }

    public function getAllByGroupID(Request $request){

        $expenses = Expenses::where('expenses_is_deleted', 0)
            ->where('expenses_group_id', $request->expenses_group_id)
            ->get();

        return response()->json([
            'message' => 'Expenses fetched successfully',
            'status' => 200,
            'data' => $expenses
        ]);

    }


}
