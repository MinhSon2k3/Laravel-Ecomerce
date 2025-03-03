<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\StoreMenuChildrenRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\MenuCatalougeRepositoryInterface as MenuCatalougeRepository;
use App\Services\Interfaces\MenuCatalougeServiceInterface as MenuCatalougeService;



class MenuController extends Controller
{
  protected $menuService;
  protected $menuRepository;
  protected $menuCatalougeRepository;
  protected $menuCatalougeService;
  public function __construct(
   MenuService $menuService,
   MenuRepository $menuRepository,
   MenuCatalougeRepository $menuCatalougeRepository,
   MenuCatalougeService $menuCatalougeService
   
  ) {
    $this->menuService = $menuService;
    $this->menuRepository = $menuRepository;
    $this->menuCatalougeRepository = $menuCatalougeRepository;
    $this->menuCatalougeService = $menuCatalougeService;
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    $this->authorize('modules','menu.index');
    //controller->service->repository thực hiện nghiệp vụ
    $menus = $this->menuCatalougeService->paginate($request);
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
  public  function store(StoreMenuRequest $request ){ //StoremenuRequest validate các menu cần create
    if($this->menuService->create($request,$this->language)){
      return redirect()->route('menu.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('menu.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','menu.edit');
    $menus = $this->menuRepository->findByConditionAndRelation([
      ['menu_catalouge_id','=',$id],
    ],['languages']);
    $a=recursive($menus);
    $template = 'backend.menu.menu.edit';
    $seo = [
       'meta_title' => __('messages.menu') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo', 'menus'));
  }

  public function children($id){
    $this->authorize('modules','menu.create');
    $menu=$this->menuRepository->findById($id,['*'],['languages']);
    $menuList=$this->menuService->convertMenu($menu);
    $template = 'backend.menu.menu.children';
    $seo = [
     'meta_title' => __('messages.menu') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','menu','menuList'));
  }

  public function saveChildren(StoreMenuChildrenRequest $request,$id){
    $menu=$this->menuRepository->findById($id);
    if($this->menuService->saveChildren($request,$this->language,$menu)){
      return redirect()->route('menu.edit',['id'=>$menu->menu_catalouge_id])->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('menu.edit',['id'=>$menu->menu_catalouge_id])->with('error', 'Thêm mới ko thành công');

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