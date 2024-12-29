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

    public function pagination(
       array $column=['*'],
       array $condition=[],
       array $join=[],
       array $extend=[],
       array $relations=[],
       array $orderBy=[],
       int $perpage=5,
        ){
       $query = $this->model->select($column)->where(function($query) use ($condition){
        if(isset($condition['keyword']) && !empty($condition['keyword'])){
            $query->where('name','LIKE','%'.$condition['keyword'].'%');
        }
        if(isset($condition['publish']) && $condition['publish']!=0){
              
            $query->where('publish','=',$condition['publish']);
        }
        return $query;
       });
       if(isset($join) && is_array($join) && count($join)){
        foreach($join as $key =>$val){
            $query->join($val[0],$val[1],$val[2],$val[3]);
        }
       }
       if(isset($relations) && !empty($relations)){
        foreach($relations as $relation){
            $query->withCount($relation);
        }
       }   
       if(isset($orderBy) && !empty($orderBy)){
            $query->orderBy($orderBy[0],$orderBy[1]);
       }
       return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
       //withQueryString Bảo toàn các tham số query string trên URL trong quá trình phân trang (ví dụ: từ khóa tìm kiếm).
       //withPath Tùy chỉnh đường dẫn cho phân trang bằng cách ghép APP_URL và đường dẫn được cung cấp trong $extend['path'].
    }

    public function create(array $payload =[]){
        $model=$this->model->create($payload);
        return $model->fresh();
    }

    public function createBatch(array $payload=[]){
        return $this->model->insert($payload);
    }

    public function update(int $id = 0, array $payload = []){
        $model = $this->model->find($id);
        if ($model) {
            $model->update($payload);
            return $model;
        }

        return $model->update($payload); // or handle the case when model is not found
    }

    public function updateByWhereIn(string $whereInField='',array $whereIn=[],array $payload = []){
        $this->model->whereIn($whereInField,$whereIn)->update($payload);//UPDATE model SET publish[0]=publish[1] WHERE $whereInField IN ($whereIn);

    }

    public function destroy(int $id = 0){
       return  $model = $this->model->find($id)->delete();
    }

    public function all(array $relation = [])
    {
        return $this->model->with($relation)->get();
    }
    

    public function findById(int $modelId, array $column = ['*'], array $relation = []){
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
       /* Lấy một model ($this->model).
        Chọn các cột được chỉ định ($column).
        Nạp các quan hệ được chỉ định ($relation).
        Tìm kiếm và trả về một bản ghi với ID là $modelId.*/
    }

    public function createTranslatePivot($model,array $payload=[]){
   
        return $model->languages()->attach($model->id,$payload);
        //Phương thức attach() được sử dụng để thêm một bản ghi mới vào bảng trung gian
    }

    public function createPivot($model,array $payload=[], string $relation=''){
   
        return $model->{$relation}()->attach($model->id,$payload);
        //Phương thức attach() được sử dụng để thêm một bản ghi mới vào bảng trung gian
    }
    
    public function updateByWhere($condition=[],array $payload=[]){
        $query=$this->model->newQuery();
        foreach($condition as $key => $val){
            $query->where($val[0],$val[1],$val[2]);
        }
        return $query->update($payload);
    }

    public function findByCondition($condition=[]){
        $query=$this->model->newQuery(); 
        foreach($condition as $key => $val){
            $query->where($val[0],$val[1],$val[2]);
        }
        return $query->first();
    }
}
