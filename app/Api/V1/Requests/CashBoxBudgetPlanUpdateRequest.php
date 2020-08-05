<?php


namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class CashBoxBudgetPlanUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'max:255',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
