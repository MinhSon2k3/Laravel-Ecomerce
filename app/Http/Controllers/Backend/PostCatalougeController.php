<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\Nestedsetbie;
use Illuminate\Http\Request;
use App\Models\PostCatalouge;
use App\Http\Requests\StorePostCatalougeRequest;
use App\Http\Requests\UpdatePostCatalougeRequest;
use App\Http\Requests\DeletePostCatalougeRequest;
use App\Services\Interfaces\PostCatalougeServiceInterface as PostCatalougeService;//thao tác create/update/delete/paginate
use App\Repositories\Interfaces\PostCatalougeRepositoryInterface as PostCatalougeRepository;//dùng cho tra cứu theo id



class PostCatalougeController  extends Controller
{   
  protected $postCatalougeService;
  protected $postCatalougeRepository;


  public function __construct(
    PostCatalougeService $postCatalougeService,
    PostCatalougeRepository $postCatalougeRepository,
  ) {

    $this->postCatalougeService = $postCatalougeService;
    $this->postCatalougeRepository = $postCatalougeRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'post_catalouges',
      'foreignkey'=>'post_catalouge_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    $this->authorize('modules','post.catalouge.index');
    //controller->service->repository thực hiện nghiệp vụ
    $postCatalouges = $this->postCatalougeService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => __('messages.postCatalogue') 
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.post.catalouge.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'postCatalouges' tới view
    return view('backend.dashboard.layout', compact('template', 'postCatalouges', 'seo'));
  }

  //khi ấn vào dòng thêm
  public function create()
  { 
    $this->authorize('modules','post.catalouge.create');
    $template = 'backend.post.catalouge.create';
    $seo = [
      'meta_title' => __('messages.postCatalogue') 
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(StorePostCatalougeRequest $request ){ // validate các thông tin cần create
    if($this->postCatalougeService->create($request)){
      return redirect()->route('post.catalouge.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('post.catalouge.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $this->authorize('modules','post.catalouge.edit');
    $postCatalouge = $this->postCatalougeRepository->getPostCatalougeById($id,$this->language);
    $dropdown=$this->nestedsetbie->Dropdown();
    $template = 'backend.post.catalouge.edit';
    $seo = [
        'meta_title' => __('messages.postCatalogue') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','postCatalouge','dropdown'));
}
  public function update($id, UpdatePostCatalougeRequest $request){
    if($this->postCatalougeService->update($id,$request)){
      return redirect()->route('post.catalouge.index')->with('success', 'Chỉnh sửa bài viết thành công');
    }
    return redirect()->route('post.catalouge.index')->with('error', 'Chỉnh sửa bài viết ko thành công');
  }

  public function delete($id){
    $this->authorize('modules','post.catalouge.delete');
    $postCatalouge = $this->postCatalougeRepository->getPostCatalougeById($id,$this->language);
    $template = 'backend.post.catalouge.delete';
    $seo = [
        'meta_title' => __('messages.postCatalogue') 
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','postCatalouge'));
}

public function destroy($id,DeletePostCatalougeRequest $request){
  if($this->postCatalougeService->destroy($id)){
    return redirect()->route('post.catalouge.index')->with('success', 'Xóa bài viết thành công');
  }
  return redirect()->route('post.catalouge.index')->with('error', 'Xóa bài viết ko thành công');
}


}



