<?php

namespace App\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

class CashBoxRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'description' => 'max:255',
        ];
    }

    public function authorize()
    {
        return true;
    }
}