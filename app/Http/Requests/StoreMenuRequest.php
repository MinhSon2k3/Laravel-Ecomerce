<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\checkMenuItem;
class StoreMenuRequest extends FormRequest
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
            'menu_catalouge_id' => 'gt:0',
            'menu_type' => 'required',

        ];
    }
    
    public function messages(): array
    {
        return [
            'menu_catalouge_id.gt' => [
                'Bạn chưa chọn vị trí menu ',
                new checkMenuItem($id)
            ],
            'menu_type.required' => 'Bạn chưa chọn kiểu menu',
            'keyword.unique' => 'Nhóm menu đã tồn tại',
        ];
    }
}