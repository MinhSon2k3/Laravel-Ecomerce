<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Hàm khởi tạo controller, lấy ngôn ngữ hiện tại của hệ thống.
     */
    public function __construct()
    {
        $this->language = $this->currentLanguage();
    }

    /**
     * Thay đổi trạng thái của một bản ghi cụ thể.
     * 
     * @param Request $request - Dữ liệu gửi lên từ client, bao gồm model cần thay đổi.
     * @return \Illuminate\Http\JsonResponse - Trả về kết quả thay đổi trạng thái dưới dạng JSON.
     */
    public function changeStatus(Request $request)
    {
        $post = $request->input(); // Lấy dữ liệu từ request

        // Xác định namespace của service xử lý model tương ứng
        $serviceInterfaceNamespace = '\App\Services\\' . ucfirst($post['model']) . 'Service';

        // Kiểm tra xem class service có tồn tại không
        if (class_exists($serviceInterfaceNamespace)) {
            // Khởi tạo service từ Service Container
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        // Gọi phương thức cập nhật trạng thái trong service
        $flag = $serviceInstance->updateStatus($post);

        // Trả về kết quả dưới dạng JSON
        return response()->json(['flag' => $flag]);
    }

    /**
     * Thay đổi trạng thái của nhiều bản ghi cùng lúc.
     * 
     * @param Request $request - Dữ liệu gửi lên từ client, bao gồm danh sách các bản ghi cần thay đổi.
     * @return \Illuminate\Http\JsonResponse - Trả về kết quả dưới dạng JSON.
     */
    public function changeStatusAll(Request $request)
    {
        $post = $request->input();

        // Xác định namespace của service xử lý model tương ứng
        $serviceInterfaceNamespace = '\App\Services\\' . ucfirst($post['model']) . 'Service';

        // Kiểm tra xem class service có tồn tại không
        if (class_exists($serviceInterfaceNamespace)) {
            // Khởi tạo service từ Service Container
            $serviceInstance = app($serviceInterfaceNamespace);
        }

        // Gọi phương thức cập nhật trạng thái của nhiều bản ghi
        $flag = $serviceInstance->updateStatusAll($post);

        // Trả về kết quả dưới dạng JSON
        return response()->json(['flag' => $flag]);
    }

    
    public function getMenu(Request $request)
    {
    $model = $request->input('model'); // Lấy model từ request
    $keyword = $request->input('keyword', ''); // Lấy keyword, mặc định là ''
    // Nếu model bị thiếu -> Trả về lỗi
    if (empty($model)) {
        return response()->json([
            'error' => 'Thiếu model!'
        ], 400);
    }

    // Xác định namespace của Repository để truy vấn dữ liệu
    $serviceInterfaceNamespace = '\App\Repositories\\' . ucfirst($model) . 'Repository';

    // Kiểm tra repository có tồn tại không
    if (!class_exists($serviceInterfaceNamespace)) {
        return response()->json([
            'error' => 'Repository không tồn tại!'
        ], 400);
    }

    $serviceInstance = app($serviceInterfaceNamespace);

    // Truyền cả model và keyword vào paginationAgrument()
    $arguments = $this->paginationAgrument($model, $keyword);

    $object = $serviceInstance->pagination(
        $arguments['colum'] ?? ['*'],
        $arguments['condition'] ?? [],
        $arguments['join'] ?? [],
        $arguments['extend'] ?? [],
        $arguments['relation'] ?? [],
        $arguments['orderBy'] ?? [],
        $arguments['perpage'] ?? 5
    );

    return response()->json([
        'data' => $object
    ]);
}



    
    private function paginationAgrument(string $model = '', string $keyword = null): array
    {
        // Chuyển tên model sang snake_case
        $model = Str::snake($model);
    
        // Thiết lập mảng JOIN với bảng ngôn ngữ liên quan
        $join = [
            [$model . '_languages as tb2', 'tb2.' . $model . '_id', '=', $model . 's.id'],
        ];
    
        // Điều kiện lọc mặc định theo ngôn ngữ
        $condition = [
            'where' => [
                ['tb2.language_id', '=', $this->language] // Lọc theo ngôn ngữ hiện tại
            ]
        ];
    
        // Nếu có từ khóa tìm kiếm, thêm điều kiện tìm kiếm theo `name`
        if (!empty($keyword)) {
            $condition['keyword'] = addslashes($keyword);
        }
    
        return [
            'colum' => ['id', 'name', 'canonical'],
            'condition' => $condition,
            'join' => $join,
            'extend' => [
                'path' => $model . '/index'
            ],
            'relation' => [],
            'orderBy' => [$model . 's.id', 'DESC'],
            'perpage' => 5
        ];
    }
    
    
}