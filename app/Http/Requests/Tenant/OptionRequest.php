<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'period' => [
                'required'
            ],
            'date_start' => [
                'required_if:period,"between"',
//                'date'
            ],
            'date_end' => [
                'required_if:period,"between"',
//                'date'
            ],
        ];
    }
}