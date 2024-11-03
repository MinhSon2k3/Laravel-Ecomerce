<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\generate;
use App\Http\Requests\StoregenerateRequest;
use App\Http\Requests\UpdategenerateRequest;
use  App\Services\Interfaces\GenerateServiceInterface as GenerateService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\GenerateRepositoryInterface as  GenerateRepository;//dùng cho tra cứu theo id



class GenerateController  extends Controller
{
  protected $generateService;
  protected $generateRepository;

  public function __construct(
    GenerateService $generateService,
    GenerateRepository $generateRepository,
  ) {

    $this->generateService = $generateService;
    $this->generateRepository = $generateRepository;
  }

  public function index(Request $request)
  {
    
    $this->authorize('modules','generate.index');
    //controller->service->repository thực hiện nghiệp vụ
    $generates = $this->generateService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.generate') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.generate.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'generates', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
    $this->authorize('modules','generate.create');
    $template = 'backend.generate.create';
    $seo = [
      'meta_title' => __('messages.generate') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo'));
  }
  //Khi nhấn vào submit create
  public  function store(StoregenerateRequest $request ){ // validate các thông tin cần create
    if($this->generateService->create($request)){
      return redirect()->route('generate.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('generate.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','generate.edit');
    $generate = $this->generateRepository->findById($id);

    $template = 'backend.generate.edit';
    $seo = [
        'meta_title' => __('messages.generate') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','generate'));
}
  public function update($id, UpdategenerateRequest $request){
    if($this->generateService->update($id,$request)){
      return redirect()->route('generate.index')->with('success', 'Chỉnh sửa ngôn ngữ thành công');
    }
    return redirect()->route('generate.index')->with('error', 'Chỉnh sửa ngôn ngữ ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','generate.delete');
    $generate = $this->generateRepository->findById($id);  
    $template = 'backend.generate.delete';
    $seo = [
        'meta_title' => __('messages.generate') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','generate'));
}

public function destroy($id){
  if($this->generateService->destroy($id)){
    return redirect()->route('generate.index')->with('success', 'Xóa ngôn ngữ thành công');
  }
  return redirect()->route('generate.index')->with('error', 'Xóa ngôn ngữ ko thành công');
}

public function switchBackendgenerate($id){
  $generate = $this->generateRepository->findById($id);  
  
  if( $this->generateService->switch($id)){
    session(['app_locale'=>$generate->canonical]);
    \App::setLocale($generate->canonical);
  }
  return back();
}
}



