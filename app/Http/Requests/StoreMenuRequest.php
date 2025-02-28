<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'menu.name'=>[
                'required',
            ],
            'type' => 'required',

        ];
    }
    
    public function messages(): array
    {
        return [
            'menu_catalouge_id.gt' => 'Bạn chưa chọn vị trí menu ',
            'menu.name.required'=>'Bạn phải tạo ít nhất 1 menu',
            'type.required' => 'Bạn chưa chọn kiểu menu',
            'keyword.unique' => 'Nhóm menu đã tồn tại',
        ];
    }
}