<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Exceptions\MyValidatorException;
class CreateFarmerRequest extends FormRequest
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
            '*.type' => 'required|in:Farmer',
            '*.attributes' => 'required|array',
            '*.attributes.f_name' => 'required|string',
            '*.attributes.l_name' => 'required|string',
            '*.attributes.email' => 'required|email|unique:farmers',
            '*.attributes.state' => 'required|string',
            '*.attributes.status' => 'required|string',
            '*.attributes.uid'=> 'required|string|unique:farmers',
        ];
        
    }
    
    protected function failedValidation(Validator $validator){
        
        throw new MyValidatorException($validator);
    }
   
}
