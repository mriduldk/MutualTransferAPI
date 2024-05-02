<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expenses;
use App\Models\ExpensesContact;
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

    public function getAllByGroupIDWithContactDetails(Request $request){

        $expenses = Expenses::where('expenses_is_deleted', 0)
            ->where('expenses_group_id', $request->expenses_group_id)
            ->get();

            // Manually map expenses_contacts data into each expenses object
            $expenses->each(function ($expense) {

                $expensesContact = ExpensesContact::where('contact_is_deleted', 0)
                    ->where('fk_expenses_id', $expense->expenses_id)
                    ->select([
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
                        'amount_give_to as amount_give_to_String',
                        'fk_expenses_id',
                        'contact_created_by',
                        'contact_created_on',
                        'contact_modified_by',
                        'contact_modified_on',
                        'contact_deleted_by',
                        'contact_deleted_on'
                    ])
                    ->get();

                $expense->contactLists = $expensesContact->toArray();
                //unset($expense->expensesContacts); 
            });

        return response()->json([
            'message' => 'Expenses fetched successfully',
            'status' => 200,
            'data' => $expenses
        ]);

    }

    public function getAllByUserIDWithContactDetails(Request $request){

        $expenses = Expenses::where('expenses_is_deleted', 0)
            ->where('expenses_created_by', $request->expenses_created_by)
            ->get();

            // Manually map expenses_contacts data into each expenses object
            $expenses->each(function ($expense) {

                $expensesContact = ExpensesContact::where('contact_is_deleted', 0)
                    ->where('fk_expenses_id', $expense->expenses_id)
                    ->select([
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
                        'amount_give_to as amount_give_to_String',
                        'fk_expenses_id',
                        'contact_created_by',
                        'contact_created_on',
                        'contact_modified_by',
                        'contact_modified_on',
                        'contact_deleted_by',
                        'contact_deleted_on'
                    ])
                    ->get();

                $expense->contactLists = $expensesContact->toArray();
                //unset($expense->expensesContacts); 
            });

        return response()->json([
            'message' => 'Expenses fetched successfully',
            'status' => 200,
            'data' => $expenses
        ]);

    }

    public function getExpensesByID(Request $request){

        $expenses = Expenses::where('expenses_is_deleted', 0)
            ->where('expenses_id', $request->expenses_id)
            ->first();

        return response()->json([
            'message' => 'Expenses fetched successfully',
            'status' => 200,
            'data' => $expenses
        ]);

    }


}
