<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpensesContact;
use \Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpensesContactController extends Controller
{
    
    public function createExpensesContact(Request $request){

        $expenses_contact = new ExpensesContact();

        $expenses_contact->contact_id = $request->contact_id;
        $expenses_contact->contact_name = $request->contact_name;
        $expenses_contact->contact_number = $request->contact_number;
        $expenses_contact->contact_paidBy = $request->contact_paidBy;
        $expenses_contact->contact_excludedFromEqualShare = $request->contact_excludedFromEqualShare;
        $expenses_contact->contact_paidAmount = $request->contact_paidAmount;
        $expenses_contact->contact_equalShare = $request->contact_equalShare;
        $expenses_contact->contact_extraShare = $request->contact_extraShare;
        $expenses_contact->contact_totalShare = $request->contact_totalShare;
        $expenses_contact->contact_amount_get = $request->contact_amount_get;
        $expenses_contact->contact_amount_give = $request->contact_amount_give;
        $expenses_contact->contact_amount_get_from = $request->contact_amount_get_from;
        
        $expenses_contact->amount_give_to = $request->amount_give_to;
        
        $expenses_contact->fk_expenses_id = $request->fk_expenses_id;

        $expenses_contact->contact_created_by = $request->contact_created_by;
        $expenses_contact->contact_created_on = Carbon::now()->toDateTimeString();

        $expenses_contact->save();


        return response()->json([
            'message' => 'Expense Contact Add Successfully',
            'status' => 200
        ]);

    }

    public function getAllByExpensesID(Request $request){

        $expenses_contact = ExpensesContact::where('contact_is_deleted', 0)
            ->where('fk_expenses_id', $request->fk_expenses_id)
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
                'amount_give_to',
                'fk_expenses_id',
                'contact_created_by',
                'contact_created_on',
                'contact_modified_by',
                'contact_modified_on',
                'contact_deleted_by',
                'contact_deleted_on'
            ])
            ->get();

        return response()->json([
            'message' => 'Expenses Contact fetched successfully',
            'status' => 200,
            'data' => $expenses_contact
        ]);

    }


}
