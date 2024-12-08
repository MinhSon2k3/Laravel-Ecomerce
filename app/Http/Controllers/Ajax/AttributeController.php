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
}
