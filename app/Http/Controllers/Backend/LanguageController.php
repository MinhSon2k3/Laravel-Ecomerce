<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Http\Requests\StoreLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use  App\Services\Interfaces\LanguageServiceInterface as LanguageService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\LanguageRepositoryInterface as  LanguageRepository;//dùng cho tra cứu theo id



class LanguageController  extends Controller
{
  protected $languageService;
  protected $languageRepository;

  public function __construct(
    LanguageService $languageService,
    LanguageRepository $languageRepository,
  ) {

    $this->languageService = $languageService;
    $this->languageRepository = $languageRepository;
  }

  public function index(Request $request)
  {
    //controller->service->repository thực hiện nghiệp vụ
    $languagess = $this->languageService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => config('apps.language')
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.language.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'languagess', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 

    $template = 'backend.language.create';
    $seo = [
      'meta_title' => config('apps.language')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreLanguageRequest $request ){ // validate các thông tin cần create
    if($this->languageService->create($request)){
      return redirect()->route('language.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('language.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $language = $this->languageRepository->findById($id);

    $template = 'backend.language.edit';
    $seo = [
        'meta_title' => config('apps.language')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','language'));
}
  public function update($id, UpdateLanguageRequest $request){
    if($this->languageService->update($id,$request)){
      return redirect()->route('language.index')->with('success', 'Chỉnh sửa ngôn ngữ thành công');
    }
    return redirect()->route('language.index')->with('error', 'Chỉnh sửa ngôn ngữ ko thành công');
  }

  public function delete($id){
    $language = $this->languageRepository->findById($id);  
    $template = 'backend.language.delete';
    $seo = [
        'meta_title' => config('apps.language')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','language'));
}

public function destroy($id){
  if($this->languageService->destroy($id)){
    return redirect()->route('language.index')->with('success', 'Xóa ngôn ngữ thành công');
  }
  return redirect()->route('language.index')->with('error', 'Xóa ngôn ngữ ko thành công');
}

public function switchBackendLanguage($id){
  $this->languageService->switch($id);
  return back();
}
}



