<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use  App\Services\Interfaces\AttributeServiceInterface as AttributeService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\AttributeRepositoryInterface as  AttributeRepository;//dùng cho tra cứu theo id



class AttributeController  extends Controller
{
  protected $attributeService;
  protected $attributeRepository;

  public function __construct(
    AttributeService $attributeService,
    AttributeRepository $attributeRepository,
  ) {

    $this->attributeService = $attributeService;
    $this->attributeRepository = $attributeRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'attribute_catalouges',
      'foreignkey'=>'attribute_catalouge_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    
    $this->authorize('modules','attribute.index');
    //controller->service->repository thực hiện nghiệp vụ
    $attributes = $this->attributeService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.attribute') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    // Định nghĩa đường dẫn tới template
    $template = 'backend.attribute.attribute.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'attributes', 'seo','dropdown'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
    $this->authorize('modules','attribute.create');
    $template = 'backend.attribute.attribute.create';
    $seo = [
      'meta_title' => __('messages.attribute') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreAttributeRequest $request ){ // validate các thông tin cần create
    if($this->attributeService->create($request)){
      return redirect()->route('attribute.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('attribute.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','attribute.edit');
    $attribute = $this->attributeRepository->getAttributeById($id,$this->language);

    $template = 'backend.attribute.attribute.edit';
    $seo = [
        'meta_title' => __('messages.attribute') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','attribute','dropdown'));
}
  public function update($id, UpdateAttributeRequest $request){
    if($this->attributeService->update($id,$request)){
      return redirect()->route('attribute.index')->with('success', 'Chỉnh sửa ngôn ngữ thành công');
    }
    return redirect()->route('attribute.index')->with('error', 'Chỉnh sửa ngôn ngữ ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','attribute.delete');
    $attribute = $this->attributeRepository->findById($id);  
    $template = 'backend.attribute.attribute.delete';
    $seo = [
        'meta_title' => __('messages.attribute') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','attribute','dropdown'));
}

public function destroy($id){
  if($this->attributeService->destroy($id)){
    return redirect()->route('attribute.index')->with('success', 'Xóa ngôn ngữ thành công');
  }
  return redirect()->route('attribute.index')->with('error', 'Xóa ngôn ngữ ko thành công');
}

}



