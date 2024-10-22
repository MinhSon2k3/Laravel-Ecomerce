<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Classes\Nestedsetbie;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Services\Interfaces\PostServiceInterface as PostService;//thao tác create/update/delete/paginate
use App\Repositories\Interfaces\PostRepositoryInterface as PostRepository;//dùng cho tra cứu theo id



class PostController  extends Controller
{   
  protected $postService;
  protected $postRepository;


  public function __construct(
    PostService $postService,
    PostRepository $postRepository,
  ) {

    $this->postService = $postService;
    $this->postRepository = $postRepository;
    $this->nestedsetbie=new Nestedsetbie([
      'table'=>'posts',
      'foreignkey'=>'post_id',
      'language_id'=>3
  ]);
    $this->language=$this->currentLanguage();
  }

  public function index(Request $request)
  {
    //controller->service->repository thực hiện nghiệp vụ
    $posts = $this->postService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => config('apps.post')
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.post.post.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'posts' tới view
    return view('backend.dashboard.layout', compact('template', 'posts', 'seo'));
  }

  //khi ấn vào dòng thêm
  public function create()
  { 

    $template = 'backend.post.post.create';
    $seo = [
      'meta_title' => config('apps.post')
    ];
    $dropdown=$this->nestedsetbie->Dropdown();
    dd($dropdown);
    return view('backend.dashboard.layout', compact('template', 'seo','dropdown'));
  }
  //Khi nhấn vào submit create
  public  function store(StorePostRequest $request ){ // validate các thông tin cần create
    if($this->postService->create($request)){
      return redirect()->route('post.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('post.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $post = $this->postRepository->getPostById($id,$this->language);
    $dropdown=$this->nestedsetbie->Dropdown();
    $template = 'backend.post.edit';
    $seo = [
        'meta_title' => config('apps.post')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','post','dropdown'));
}
  public function update($id, UpdatePostRequest $request){
    if($this->postService->update($id,$request)){
      return redirect()->route('post.index')->with('success', 'Chỉnh sửa bài viết thành công');
    }
    return redirect()->route('post.index')->with('error', 'Chỉnh sửa bài viết ko thành công');
  }

  public function delete($id){
    $post = $this->postRepository->getPostById($id,$this->language);
    $template = 'backend.post.delete';
    $seo = [
        'meta_title' => config('apps.post')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','post'));
}

public function destroy($id,DeletePostRequest $request){
  if($this->postService->destroy($id)){
    return redirect()->route('post.index')->with('success', 'Xóa bài viết thành công');
  }
  return redirect()->route('post.index')->with('error', 'Xóa bài viết ko thành công');
}


}



