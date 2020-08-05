<?php


namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class CashBoxBudgetPlanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'cash_box_id' => 'required',
            'budget' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
