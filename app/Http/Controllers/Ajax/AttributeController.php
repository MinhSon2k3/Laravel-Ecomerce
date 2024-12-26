<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\AttributeRepositoryInterface  as AttributeRepository;



class AttributeController extends Controller
{
    protected $attributeRepository;
   

    public function __construct(
        AttributeRepository $attributeRepository
    ){
        $this->attributeRepository = $attributeRepository;
        $this->language=$this->currentLanguage();
    }

    public function getAttribute(Request $request){
      
        
        $payload = $request->input();
        $attributes = $this->attributeRepository->searchAttributes($payload['search'], $payload['option'],$this->language);

        $attributeMapped = $attributes->map(function($attribute){
            return [
                'id' => $attribute->id,
                'text' => $attribute->attribute_language->first()->name,
            ];
        })->all();
       
        return response()->json(array('items' => $attributeMapped)); 
    }
    // Phương thức load các thuộc tính dựa trên danh sách ID
    public function loadAttribute(Request $request)
    {
        // Giải mã và chuyển dữ liệu từ base64 về array
        $payload['attribute'] = json_decode(base64_decode($request->input('attribute')), TRUE);
        // Lấy ID của danh mục thuộc tính
        $payload['attributeCatalougeId'] = $request->input('attributeCatalougeId');
        // Lấy danh sách ID thuộc tính từ danh mục
        $attributeArray = $payload['attribute'][$payload['attributeCatalougeId']];
        
        // Khởi tạo mảng chứa các thuộc tính
        $attributes = [];

        // Nếu có danh sách ID thuộc tính, gọi repository để lấy dữ liệu
        if (count($attributeArray)) {
            $attributes = $this->attributeRepository->findAttributeByIdArray(
                $attributeArray,  // Mảng ID thuộc tính
                $this->language   // Ngôn ngữ hiện tại
            );
        }

        // Mảng tạm để lưu kết quả định dạng
        $temp = [];

        // Map dữ liệu thuộc tính nếu có kết quả
        if (count($attributes)) {
            foreach ($attributes as $key => $val) {
                $temp[] = [
                    'id' => $val->id,   // ID của thuộc tính
                    'text' => $val->name,  // Tên thuộc tính
                ];
            }
        }

        // Trả về danh sách thuộc tính dưới dạng JSON
        return response()->json(['items' => $temp]);
    }

}
