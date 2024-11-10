<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProvinceResource;
use  App\Services\Interfaces\UserServiceInterface as UserService;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use App\Repositories\Interfaces\ProvinceRepositoryInterface as ProvinceRepository;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected UserService $userService;
    protected UserRepository $userRepository;
    protected ProvinceRepository $provinceRepository;

    public function __construct(
        UserService $userService,
        UserRepository $userRepository,
        ProvinceRepository $provinceRepository
    ) {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        if ($users = $this->userService->paginate($request)) {
            return response()->json([
                'message' => 'Tải thành công',
                'data' => UserResource::collection($users), // Sử dụng collection để áp dụng cho từng user
                'status' => true,
            ], 201);
        }
        
        return response()->json([
            'message' => 'Tải không thành công',
            'status' => false,
        ], 422);
    }
    

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        // Trả về thông tin người dùng vừa tạo
        return response()->json([
            'message' => 'Tạo người dùng thành công',
            'data' => new UserResource($user),
            'status' => true,
        ], 201);
    }

  

    public function update(string $id, UpdateUserRequest $request): JsonResponse
    {
        $this->authorize('modules', 'user.edit');

        if ($user = $this->userService->update($id, $request)) {
            return response()->json([
                'message' => 'Chỉnh sửa thông tin thành công',
                'data' => new UserResource($user)
            ]);
        }

        return response()->json([
            'message' => 'Chỉnh sửa thông tin không thành công'
        ], 422);
    }

  
    public function destroy(string $id): JsonResponse
    {
        $this->authorize('modules', 'user.delete');

        if ($this->userService->destroy($id)) {
            return response()->json([
                'message' => 'Xóa thông tin thành công'
            ]);
        }

        return response()->json([
            'message' => 'Xóa thông tin không thành công'
        ], 422);
    }
}