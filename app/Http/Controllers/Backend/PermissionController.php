<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use  App\Services\Interfaces\PermissionServiceInterface as PermissionService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\PermissionRepositoryInterface as  PermissionRepository;//dùng cho tra cứu theo id



class PermissionController  extends Controller
{
  protected $permissionService;
  protected $permissionRepository;

  public function __construct(
    PermissionService $permissionService,
    PermissionRepository $permissionRepository,
  ) {

    $this->permissionService = $permissionService;
    $this->permissionRepository = $permissionRepository;
  }

  public function index(Request $request)
  {
    //controller->service->repository thực hiện nghiệp vụ
    $permissions = $this->permissionService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.permission') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.permission.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'permissions', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 

    $template = 'backend.permission.create';
    $seo = [
      'meta_title' => __('messages.permission') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo'));
  }
  //Khi nhấn vào submit create
  public  function store(StorePermissionRequest $request ){ // validate các thông tin cần create
    if($this->permissionService->create($request)){
      return redirect()->route('permission.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('permission.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $permission = $this->permissionRepository->findById($id);

    $template = 'backend.permission.edit';
    $seo = [
        'meta_title' => __('messages.permission') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','permission'));
}
  public function update($id, UpdatePermissionRequest $request){
    if($this->permissionService->update($id,$request)){
      return redirect()->route('permission.index')->with('success', 'Chỉnh sửa ngôn ngữ thành công');
    }
    return redirect()->route('permission.index')->with('error', 'Chỉnh sửa ngôn ngữ ko thành công');
  }

  public function delete($id){
    $permission = $this->permissionRepository->findById($id);  
    $template = 'backend.permission.delete';
    $seo = [
        'meta_title' => __('messages.permission') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','permission'));
}

public function destroy($id){
  if($this->permissionService->destroy($id)){
    return redirect()->route('permission.index')->with('success', 'Xóa ngôn ngữ thành công');
  }
  return redirect()->route('permission.index')->with('error', 'Xóa ngôn ngữ ko thành công');
}

public function switchBackendPermission($id){
  $permission = $this->permissionRepository->findById($id);  
  
  if( $this->permissionService->switch($id)){
    session(['app_locale'=>$permission->canonical]);
    \App::setLocale($permission->canonical);
  }
  return back();
}

}



