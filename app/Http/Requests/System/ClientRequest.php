<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize() {
        return true;
    }
    
    public function rules() {
        $id = $this->input('id');
        
        return [
            'email' => ['required', 'email'],
            'number' => ['required',],
            // 'name' => ['required', Rule::unique('system.clients')->ignore($id)],
            'name' => ['required',],
            'password' => ['required'],
            'profile' => ['required'],
            'subdomain' => ['required'],
        ];
    }
}