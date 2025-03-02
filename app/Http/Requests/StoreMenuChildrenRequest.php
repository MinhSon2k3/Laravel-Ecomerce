<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreMenuChildrenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu.name'=>[
                'required',
            ],

        ];
    }
    
    public function messages(): array
    {
        return [
            'menu.name.required'=>'Bạn phải tạo ít nhất 1 menu',
        ];
    }
}