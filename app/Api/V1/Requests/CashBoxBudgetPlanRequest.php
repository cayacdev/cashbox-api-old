<?php


namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class CashBoxBudgetPlanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'budget' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
