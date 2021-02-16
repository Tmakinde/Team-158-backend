<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class MyValidatorException extends Exception
{
    protected $validator;

    protected $status = 422;

    public function __construct($validator){
        $this->validator = $validator;
    }

    public function render(){
        $transformed = [];
        
        foreach ($this->validator->errors()->all() as $field => $message) {
            $transformed[] = [
                'field' => $field,
                'message' => $message
            ];
        }
        return response()->json([
            "error" => "form error",
            "message" => $this->validator->errors(),
        ], $this->status);
    }

}
