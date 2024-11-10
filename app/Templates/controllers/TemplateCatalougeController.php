<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\Nestedsetbie;
use Illuminate\Http\Request;
use App\Models\{ModuleTemplate};
use App\Http\Requests\Store{ModuleTemplate}Request;
use App\Http\Requests\Update{ModuleTemplate}Request;
use App\Http\Requests\Delete{ModuleTemplate}Request;
use App\Services\Interfaces\{ModuleTemplate}ServiceInterface as {ModuleTemplate}Service;//thao tác create/update/delete/paginate
use App\Repositories\Interfaces\{ModuleTemplate}RepositoryInterface as {ModuleTemplate}Repository;//dùng cho tra cứu theo id



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
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'{tableName}',
      'foreignkey'=>'{foreignkey}',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    $this->authorize('modules','{moduleView}.index');
    //controller->service->repository thực hiện nghiệp vụ
    ${moduleTemplate}s = $this->{moduleTemplate}Service->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.postCatalogue') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.{moduleView}.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và '{moduleTemplate}s' tới view
    return view('backend.dashboard.layout', compact('template', '{moduleTemplate}s', 'seo'));
  }

  //khi ấn vào dòng thêm
  public function create()
  { 
    $this->authorize('modules','{moduleView}.create');
    $template = 'backend.{moduleView}.create';
    $seo = [
      'meta_title' => __('messages.postCatalogue') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(Store{moduleTemplate}Request $request ){ // validate các thông tin cần create
    if($this->{moduleTemplate}Service->create($request)){
      return redirect()->route('{moduleView}.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('{moduleView}.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','{moduleView}.edit');
    ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id,$this->language);
    $dropdown=$this->nestedsetbie->Dropdown();
    $template = 'backend.{moduleView}.edit';
    $seo = [
        'meta_title' => __('messages.postCatalogue') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','{moduleTemplate}','dropdown'));
}
  public function update($id, Update{moduleTemplate}Request $request){
    if($this->{moduleTemplate}Service->update($id,$request)){
      return redirect()->route('{moduleView}.index')->with('success', 'Chỉnh sửa bài viết thành công');
    }
    return redirect()->route('{moduleView}.index')->with('error', 'Chỉnh sửa bài viết ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','{moduleView}.delete');
    ${moduleTemplate} = $this->{moduleTemplate}Repository->get{ModuleTemplate}ById($id,$this->language);
    $template = 'backend.{moduleView}.delete';
    $seo = [
        'meta_title' => __('messages.postCatalogue') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','{moduleTemplate}'));
}

public function destroy($id,Delete{moduleTemplate}Request $request){
  if($this->{moduleTemplate}Service->destroy($id)){
    return redirect()->route('{moduleView}.index')->with('success', 'Xóa bài viết thành công');
  }
  return redirect()->route('{moduleView}.index')->with('error', 'Xóa bài viết ko thành công');
}


}



