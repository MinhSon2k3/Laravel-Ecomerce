<?php

namespace App\Repositories;

use  App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models;


class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy danh sách dữ liệu theo điều kiện với phân trang.
     */
    public function pagination(
       array $column=['*'], // Cột cần lấy
       array $condition=[], // Điều kiện lọc
       array $join=[], // Các bảng cần join
       array $extend=[], // Tham số mở rộng
       array $relations=[], // Các quan hệ cần load
       array $orderBy=[], // Điều kiện sắp xếp
       int $perpage=5, // Số lượng bản ghi trên mỗi trang
    ){
       $query = $this->model->select($column)->where(function($query) use ($condition){
        if(isset($condition['keyword']) && !empty($condition['keyword'])){
            $query->where('name','LIKE','%'.$condition['keyword'].'%'); // Lọc theo keyword
        }
        if(isset($condition['publish']) && $condition['publish']!=0){
            $query->where('publish','=',$condition['publish']); // Lọc theo trạng thái publish
        }
        return $query;
       });

       if(!empty($join)){
        foreach($join as $key =>$val){
            $query->join($val[0],$val[1],$val[2],$val[3]); // Thực hiện join bảng
        }
       }

       if(!empty($relations)){
        foreach($relations as $relation){
            $query->withCount($relation); // Đếm số bản ghi liên quan
        }
       }   

       if(!empty($orderBy)){
            $query->orderBy($orderBy[0],$orderBy[1]); // Thực hiện sắp xếp
       }

       return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
       // Bảo toàn các tham số query string trên URL khi phân trang
       // Tùy chỉnh đường dẫn phân trang
    }

    /**
     * Tạo mới một bản ghi.
     */
    public function create(array $payload =[]){
        $model = $this->model->create($payload);
        return $model->fresh(); // Lấy lại dữ liệu sau khi tạo
    }

    /**
     * Chèn nhiều bản ghi một lúc.
     */
    public function createBatch(array $payload=[]){
        return $this->model->insert($payload);
    }

    /**
     * Cập nhật bản ghi theo ID.
     */
    public function update(int $id = 0, array $payload = []){
        $model = $this->model->find($id);
        if ($model) {
            $model->update($payload);
            return $model;
        }

        return null; // Trả về null nếu không tìm thấy bản ghi
    }

    /**
     * Cập nhật hoặc chèn mới nếu chưa có.
     */
    public function updateOrInsert(array $condition = [], array $payload = []){
        return $this->model->updateOrInsert($condition, $payload);
    }

    /**
     * Cập nhật nhiều bản ghi có giá trị trong danh sách chỉ định.
     */
    public function updateByWhereIn(string $whereInField='',array $whereIn=[],array $payload = []){
        $this->model->whereIn($whereInField,$whereIn)->update($payload);
    }

    /**
     * Xóa bản ghi theo ID.
     */
    public function destroy(int $id = 0){
        return $this->model->find($id)?->delete();
    }

    /**
     * Lấy tất cả bản ghi, có thể kèm theo quan hệ.
     */
    public function all(array $relation = []){
        return $this->model->with($relation)->get();
    }

    /**
     * Tìm kiếm bản ghi theo ID, có thể kèm theo quan hệ và chọn cột cụ thể.
     */
    public function findById(int $modelId, array $column = ['*'], array $relation = []){
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
    }

    /**
     * Tạo bản ghi trong bảng trung gian (pivot) cho quan hệ đa ngôn ngữ.
     */
    public function createTranslatePivot($model, array $payload=[]){
        return $model->languages()->attach($model->id, $payload);
    }

    /**
     * Tạo bản ghi trong bảng trung gian cho quan hệ nhiều-nhiều.
     */
    public function createPivot($model, array $payload=[], string $relation=''){
        return $model->{$relation}()->attach($model->id, $payload);
    }

    /**
     * Cập nhật nhiều bản ghi dựa trên điều kiện.
     */
    public function updateByWhere(array $condition=[], array $payload=[]){
        $query = $this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->update($payload);
    }

    /**
     * Tìm bản ghi đầu tiên khớp với điều kiện.
     */
    public function findByCondition(array $condition=[]){
        $query = $this->model->newQuery(); 
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->first();
    }

    /**
     * Tìm bản ghi theo điều kiện, kèm theo quan hệ.
     */
    public function findByConditionAndRelation(array $condition=[], array $relation=[]){
        $query = $this->model->newQuery(); 
        foreach($condition as $key => $val){
            $query->where($val[0], $val[1], $val[2]);
        }
        return $query->with($relation)->get();
    }
}