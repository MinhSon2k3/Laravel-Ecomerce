<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'user_catalouge_id' => 'required',
            'birthday' => 'required',
            'password' => 'required',
            're_password' => 'required|same:password',
        ];
    }


    public function messages(): array
    {
        return [
            'email.required'=>'Bạn chưa nhập EMAIL ',
            'email.unique'=>'EMAIL đã tồn tại ',
            'email.email'=>'EMAIL chưa đúng định dạng',
            'name.required'=>'Bạn chưa nhập tên ',
            'user_catalogue_id.required'=>'Bạn chưa nhập vai trò ',
            'birthday.required'=>'Bạn chưa nhập ngày sinh ',
            'password.required'=>'Bạn chưa nhập mật khẩu',
            're_password.required'=>'Bạn chưa nhập lại mật khẩu',
            're_password.same'=>'Mật khẩu không trùng khớp',
        ];
    }
}
