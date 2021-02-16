<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidatorException;

class CreateInvestorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            '*.type' => 'required|in:Investor',
            '*.attributes' => 'required|array',
            '*.attributes.f_name' => 'required|string',
            '*.attributes.l_name' => 'required|string',
            '*.attributes.email' => 'required|email|unique:investors',
            '*.attributes.state' => 'required|string',
            '*.attributes.status' => 'required|string',
            '*.attributes.uid'=> 'required|string|unique:investors',
        ];
    }

    protected function failedValidation(Validator $validator){
        
        throw new MyValidatorException($validator);
    }
}
