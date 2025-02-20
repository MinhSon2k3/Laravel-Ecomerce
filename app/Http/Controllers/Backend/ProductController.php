<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Classes\Nestedsetbie;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use  App\Services\Interfaces\ProductServiceInterface as ProductService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\ProductRepositoryInterface as  ProductRepository;//dùng cho tra cứu theo id
use  App\Repositories\Interfaces\AttributeCatalougeRepositoryInterface as  AttributeCatalougeRepository;



class ProductController  extends Controller
{
  protected $productService;
  protected $productRepository;

  public function __construct(
    ProductService $productService,
    ProductRepository $productRepository,
    AttributeCatalougeRepository $attributeCatalougeRepository
  ) {

    $this->productService = $productService;
    $this->productRepository = $productRepository;
    $this->attributeCatalougeRepository = $attributeCatalougeRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'product_catalouges',
      'foreignkey'=>'product_catalouge_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    
    $this->authorize('modules','product.index');
    //controller->service->repository thực hiện nghiệp vụ
    $products = $this->productService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.product') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    // Định nghĩa đường dẫn tới template
    $template = 'backend.product.product.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'products', 'seo','dropdown'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
    $this->authorize('modules','product.create');
    $attributeCatalouge=$this->attributeCatalougeRepository->getAll();
    $template = 'backend.product.product.create';
    $seo = [
      'meta_title' => __('messages.product') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown','attributeCatalouge'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreProductRequest $request ){ // validate các thông tin cần create
    if($this->productService->create($request,$this->language)){
      return redirect()->route('product.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('product.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','product.edit');
    $attributeCatalouge=$this->attributeCatalougeRepository->getAll();
    $product = $this->productRepository->getProductById($id,$this->language);
   
    $template = 'backend.product.product.edit';
    $seo = [
        'meta_title' => __('messages.product') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','product','dropdown','attributeCatalouge'));
}
  public function update($id, UpdateProductRequest $request){
    if($this->productService->update($id,$request,$this->language)){
      return redirect()->route('product.index')->with('success', 'Chỉnh sửa thành công');
    }
    return redirect()->route('product.index')->with('error', 'Chỉnh sửa ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','product.delete');
    $product = $this->productRepository->getProductById($id,$this->language);  
    $template = 'backend.product.product.delete';
    $seo = [
        'meta_title' => __('messages.product') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','product','dropdown'));
}

public function destroy($id){
  if($this->productService->destroy($id)){
    return redirect()->route('product.index')->with('success', 'Xóa thành công');
  }
  return redirect()->route('product.index')->with('error', 'Xóa ko thành công');
}

}