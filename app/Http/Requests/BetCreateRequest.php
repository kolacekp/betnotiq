<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BetCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $isAdmin = self::user()->isAdmin();
        $schema = [
            'url' => ['required', 'string'],
            'value' => ['numeric', 'min:0'],
        ];

        if(!$isAdmin){
            $schema['value'][] = 'max:5';
            $schema['fixed_value_value'][] = 'max:5000';
        }

        return $schema;
    }
}
