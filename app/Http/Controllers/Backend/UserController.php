<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use  App\Services\Interfaces\UserServiceInterface as UserService;//thao tác create/update/delete/paginate
use  App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;//dùng cho tra cứu theo id
use  App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;//tra địa chỉ


class UserController extends Controller
{
  protected $userService;
  public function __construct(
    UserService $userService,
    UserRepository $userRepository,
    ProvinceRepository $provinceRepository
  ) {
    $this->userService = $userService;
    $this->userRepository = $userRepository;
    $this->provinceRepository = $provinceRepository;
  }

  public function index(Request $request)
  {
    //controller->service->repository thực hiện nghiệp vụ
    $users = $this->userService->paginate($request);
    $seo = [
      //Hàm config lấy giá trị từ file cấu hình của ứng dụng.
      'meta_title' => config('apps.user')
    ];
    // Định nghĩa đường dẫn tới template
    $template = 'backend.user.user.index';
    // Trả về view với layout 'backend.dashboard.layout' và truyền biến 'template' và 'users' tới view
    return view('backend.dashboard.layout', compact('template', 'users', 'seo'));
  }

  //khi ấn vào dòng thêm người dùng
  public function create()
  { 
  
    $provinces = $this->provinceRepository->all();
    $template = 'backend.user.user.create';
    $seo = [
      'meta_title' => config('apps.user')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo', 'provinces'));
  }
  //Khi nhấn vào submit create
  public  function store(StoreUserRequest $request ){ //StoreUserRequest validate các thông tin cần create
    if($this->userService->create($request)){
      return redirect()->route('user.index')->with('success', 'Thêm mới thành công');
    }
    return redirect()->route('user.index')->with('error', 'Thêm mới ko thành công');
  }

  //edit
  public function edit($id){
    $user = $this->userRepository->findById($id);
    $provinces = $this->provinceRepository->all();
   
    $template = 'backend.user.user.edit';
    $seo = [
        'meta_title' => config('apps.user')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo', 'provinces', 'user'));
}
  public function update($id, UpdateUserRequest $request)
  {
      if($this->userService->update($id, $request)) {
          // Thông báo thành công
          return redirect()->route('user.index')->with('success', 'Chỉnh sửa thông tin thành công');
      }
      // Thông báo lỗi
      return redirect()->route('user.index')->with('error', 'Chỉnh sửa thông tin không thành công');
  }


  public function delete($id){
    $user = $this->userRepository->findById($id);  
    $template = 'backend.user.user.delete';
    $seo = [
        'meta_title' => config('apps.user')
    ];
    return view('backend.dashboard.layout', compact('template', 'seo','user'));
}

public function destroy($id){
  if($this->userService->destroy($id)){
    return redirect()->route('user.index')->with('success', 'Xóa thông tin thành công');
  }
  return redirect()->route('user.index')->with('error', 'Xóa thông tin ko thành công');
}


}



