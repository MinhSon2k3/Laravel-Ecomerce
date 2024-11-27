<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\Nestedsetbie;
use Illuminate\Http\Request;
use App\Models\ProductCatalouge;
use App\Http\Requests\StoreProductCatalougeRequest;
use App\Http\Requests\UpdateProductCatalougeRequest;
use App\Http\Requests\DeleteProductCatalougeRequest;
use App\Services\Interfaces\ProductCatalougeServiceInterface as ProductCatalougeService;//thao tác create/update/delete/paginate
use App\Repositories\Interfaces\ProductCatalougeRepositoryInterface as ProductCatalougeRepository;//dùng cho tra cứu theo id



class ProductCatalougeController  extends Controller
{   
  protected $productCatalougeService;
  protected $productCatalougeRepository;


  public function __construct(
   ProductCatalougeService $productCatalougeService,
   ProductCatalougeRepository $productCatalougeRepository,
  ) {

    $this->productCatalougeService = $productCatalougeService;
    $this->productCatalougeRepository = $productCatalougeRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'product_catalouges',
      'foreignkey'=>'product_catalouge_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    $this->authorize('modules','product.catalouge.index');
    //controller->service->repository thực hiện nghiệp vụ
    $productCatalouges = $this->productCatalougeService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.productCatalouge') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.product.catalouge.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'productCatalouges' tới view
    return view('backend.dashboard.layout', compact('template', 'productCatalouges', 'seo'));
  }

  //khi ấn vào dòng thêm
  public function create()
  { 
    $this->authorize('modules','product.catalouge.create');
    $template = 'backend.product.catalouge.create';
    $seo = [
      'meta_title' => __('messages.productCatalouge') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreproductCatalougeRequest $request ){ // validate các thông tin cần create
    if($this->productCatalougeService->create($request)){
      return redirect()->route('product.catalouge.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('product.catalouge.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','product.catalouge.edit');
    $productCatalouge = $this->productCatalougeRepository->getProductCatalougeById($id,$this->language);
    $dropdown=$this->nestedsetbie->Dropdown();
    $template = 'backend.product.catalouge.edit';
    $seo = [
        'meta_title' => __('messages.productCatalouge') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','productCatalouge','dropdown'));
}
  public function update($id, UpdateproductCatalougeRequest $request){
    if($this->productCatalougeService->update($id,$request)){
      return redirect()->route('product.catalouge.index')->with('success', 'Chỉnh sửa thành công');
    }
    return redirect()->route('product.catalouge.index')->with('error', 'Chỉnh sửa ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','product.catalouge.delete');
    $productCatalouge = $this->productCatalougeRepository->getProductCatalougeById($id,$this->language);
    $template = 'backend.product.catalouge.delete';
    $seo = [
        'meta_title' => __('messages.productCatalouge') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','productCatalouge'));
}

public function destroy($id,DeleteproductCatalougeRequest $request){
  if($this->productCatalougeService->destroy($id)){
    return redirect()->route('product.catalouge.index')->with('success', 'Xóa thành công');
  }
  return redirect()->route('product.catalouge.index')->with('error', 'Xóa ko thành công');
}


}



