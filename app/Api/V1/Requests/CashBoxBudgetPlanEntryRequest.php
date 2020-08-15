<?php


namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class CashBoxBudgetPlanEntryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'value' => 'required|numeric',
            'description' => 'required|max:255',
            'date' => 'required|date',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
