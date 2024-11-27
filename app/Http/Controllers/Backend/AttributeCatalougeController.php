<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\Nestedsetbie;
use Illuminate\Http\Request;
use App\Models\AttributeCatalouge;
use App\Http\Requests\StoreAttributeCatalougeRequest;
use App\Http\Requests\UpdateAttributeCatalougeRequest;
use App\Http\Requests\DeleteAttributeCatalougeRequest;
use App\Services\Interfaces\AttributeCatalougeServiceInterface as AttributeCatalougeService;//thao tác create/update/delete/paginate
use App\Repositories\Interfaces\AttributeCatalougeRepositoryInterface as AttributeCatalougeRepository;//dùng cho tra cứu theo id



class AttributeCatalougeController  extends Controller
{   
  protected $attributeCatalougeService;
  protected $attributeCatalougeRepository;


  public function __construct(
   AttributeCatalougeService $attributeCatalougeService,
   AttributeCatalougeRepository $attributeCatalougeRepository,
  ) {

    $this->attributeCatalougeService = $attributeCatalougeService;
    $this->attributeCatalougeRepository = $attributeCatalougeRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'attribute_catalouges',
      'foreignkey'=>'attribute_catalouge_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    $this->authorize('modules','attribute.catalouge.index');
    //controller->service->repository thực hiện nghiệp vụ
    $attributeCatalouges = $this->attributeCatalougeService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.attributeCatalouge') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.attribute.catalouge.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'attributeCatalouges' tới view
    return view('backend.dashboard.layout', compact('template', 'attributeCatalouges', 'seo'));
  }

  //khi ấn vào dòng thêm
  public function create()
  { 
    $this->authorize('modules','attribute.catalouge.create');
    $template = 'backend.attribute.catalouge.create';
    $seo = [
      'meta_title' => __('messages.attributeCatalouge') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreattributeCatalougeRequest $request ){ // validate các thông tin cần create
    if($this->attributeCatalougeService->create($request)){
      return redirect()->route('attribute.catalouge.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('attribute.catalouge.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','attribute.catalouge.edit');
    $attributeCatalouge = $this->attributeCatalougeRepository->getAttributeCatalougeById($id,$this->language);
    $dropdown=$this->nestedsetbie->Dropdown();
    $template = 'backend.attribute.catalouge.edit';
    $seo = [
        'meta_title' => __('messages.attributeCatalouge') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','attributeCatalouge','dropdown'));
}
  public function update($id, UpdateattributeCatalougeRequest $request){
    if($this->attributeCatalougeService->update($id,$request)){
      return redirect()->route('attribute.catalouge.index')->with('success', 'Chỉnh sửa bài viết thành công');
    }
    return redirect()->route('attribute.catalouge.index')->with('error', 'Chỉnh sửa bài viết ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','attribute.catalouge.delete');
    $attributeCatalouge = $this->attributeCatalougeRepository->getAttributeCatalougeById($id,$this->language);
    $template = 'backend.attribute.catalouge.delete';
    $seo = [
        'meta_title' => __('messages.attributeCatalouge') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','attributeCatalouge'));
}

public function destroy($id,DeleteattributeCatalougeRequest $request){
  if($this->attributeCatalougeService->destroy($id)){
    return redirect()->route('attribute.catalouge.index')->with('success', 'Xóa bài viết thành công');
  }
  return redirect()->route('attribute.catalouge.index')->with('error', 'Xóa bài viết ko thành công');
}


}



