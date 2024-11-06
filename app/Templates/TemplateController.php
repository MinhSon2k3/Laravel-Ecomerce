<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use  App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as  {ModuleTemplate}Repository;//dùng cho tra cứu theo id



class {ModuleTemplate}Controller  extends Controller
{
  protected ${moduleTemplate}Service;
  protected ${moduleTemplate}Repository;

  public function __construct(
    {ModuleTemplate}Service ${moduleTemplate}Service,
    {ModuleTemplate}Repository ${moduleTemplate}Repository,
  ) {

    $this->{moduleTemplate}Service = ${moduleTemplate}Service;
    $this->{moduleTemplate}Repository = ${moduleTemplate}Repository;
  }

  public function index(Request $request)
  {
    
    $this->authorize('modules','{moduleTemplate}.index');
    //controller->service->repository thực hiện nghiệp vụ
    ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.{moduleTemplate}') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.{moduleView}.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', '{moduleTemplate}s', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
    $this->authorize('modules','{moduleTemplate}.create');
    $template = 'backend.{moduleView}.create';
    $seo = [
      'meta_title' => __('messages.{moduleTemplate}') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo'));
  }
  //Khi nhấn vào submit create
  public  function store(Store{ModuleTemplate}Request $request ){ // validate các thông tin cần create
    if($this->{moduleTemplate}Service->create($request)){
      return redirect()->route('{moduleTemplate}.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('{moduleTemplate}.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','{moduleTemplate}.edit');
    ${moduleTemplate} = $this->{moduleTemplate}Repository->findById($id);

    $template = 'backend.{moduleView}.edit';
    $seo = [
        'meta_title' => __('messages.{moduleTemplate}') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','{moduleTemplate}'));
}
  public function update($id, Update{ModuleTemplate}Request $request){
    if($this->{moduleTemplate}Service->update($id,$request)){
      return redirect()->route('{moduleTemplate}.index')->with('success', 'Chỉnh sửa ngôn ngữ thành công');
    }
    return redirect()->route('{moduleTemplate}.index')->with('error', 'Chỉnh sửa ngôn ngữ ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','{moduleTemplate}.delete');
    ${moduleTemplate} = $this->{moduleTemplate}Repository->findById($id);  
    $template = 'backend.{moduleView}.delete';
    $seo = [
        'meta_title' => __('messages.{moduleTemplate}') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','{moduleTemplate}'));
}

public function destroy($id){
  if($this->{moduleTemplate}Service->destroy($id)){
    return redirect()->route('{moduleTemplate}.index')->with('success', 'Xóa ngôn ngữ thành công');
  }
  return redirect()->route('{moduleTemplate}.index')->with('error', 'Xóa ngôn ngữ ko thành công');
}

}



