<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'canonical' => 'required|unique:routers',
            'code' => 'required',
            'price' => 'required',
            'made_in' => 'required',
            'product_catalouge_id' => 'required:product_catalouges,id|not_in:0', // Chèn validation cho product_catalouge_id
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn chưa nhập vào ô tiêu đề.',
            'canonical.required' => 'Bạn chưa nhập vào ô đường dẫn',
            'canonical.unique' => 'Đường dẫn đã tồn tại, Hãy chọn đường dẫn khác',
            'code.required' => 'Bạn chưa nhập mã sản phẩm.',
            'price.required' => 'Bạn chưa nhập giá.',
            'made_in.required' => 'Bạn chưa nhập xuất sứ.',
            'product_catalouge_id.required' => 'Bạn chưa chọn danh mục sản phẩm.',
            'product_catalouge_id.not_in' => 'Danh mục sản phẩm không được là root.',
        ];
    }
}
