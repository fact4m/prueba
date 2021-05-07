<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'internal_id' => [
                //Rule::unique('tenant.items')->ignore($id),
            ],
            'description' => [
                'required',
                Rule::unique('tenant.items')->ignore($id),
            ],
            'unit_type_id' => [
                'required',
            ],
            'unit_price' => [
                'required','numeric'
            ],
        ];
    }
}