<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use  App\Services\Interfaces\MenuServiceInterface as MenuService;
use  App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use  App\Repositories\Interfaces\MenuCatalougeRepositoryInterface as MenuCatalougeRepository;



class MenuController extends Controller
{
  protected $menuService;
  protected $menuRepository;
  protected $menuCatalougeRepository;
  public function __construct(
   MenuService $menuService,
   MenuRepository $menuRepository,
   MenuCatalougeRepository $menuCatalougeRepository
   
  ) {
    $this->menuService = $menuService;
    $this->menuRepository = $menuRepository;
    $this->menuCatalougeRepository = $menuCatalougeRepository;
  }

  public function index(Request $request)
  {
    $this->authorize('modules','menu.index');
    //controller->service->repository thực hiện nghiệp vụ
    $menus = $this->menuService->paginate($request);
    $seo = [
        //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
        'meta_title' => __('messages.menu') 
      ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.menu.menu.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'menus' tới view
    return view('backend.dashboard.layout', compact('template', 'menus', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
    $this->authorize('modules','menu.create');
    $menuCatalouges=$this->menuCatalougeRepository->all();
    $template = 'backend.menu.menu.create';
    $seo = [
     'meta_title' => __('messages.menu') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','menuCatalouges'));
  }
  //Khi nhấn vào submit create
  public  function store(Request $request ){ //StoremenuRequest validate các menu cần create
    if($this->menuService->create($request)){
      return redirect()->route('menu.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('menu.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','menu.edit');
    $menu = $this->menuRepository->findById($id);
   
    $template = 'backend.menu.menu.edit';
    $seo = [
       'meta_title' => __('messages.menu') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo', 'menu'));
}
  public function update($id, UpdatemenuRequest $request)
  {
      if($this->menuService->update($id, $request)) {
          // Thông báo thành công
          return redirect()->route('menu.index')->with('success', 'Chỉnh sửa menu thành công');
      }
      // Thông báo lỗi
      return redirect()->route('menu.index')->with('error', 'Chỉnh sửa menu không thành công');
  }


  public function delete($id){
    $this->authorize('modules','menu.delete');
    $menu = $this->menuRepository->findById($id);  
    $template = 'backend.menu.menu.delete';
    $seo = [
       'meta_title' => __('messages.menu') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','menu'));
}

public function destroy($id){
  if($this->menuService->destroy($id)){
    return redirect()->route('menu.index')->with('success', 'Xóa menu thành công');
  }
  return redirect()->route('menu.index')->with('error', 'Xóa menu ko thành công');
}


}