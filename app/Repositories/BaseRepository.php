<?php

namespace App\Repositories;

use  App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;


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
       if(isset($relations) && !empty($relations)){
        foreach($relations as $relation){
            $query->withCount($relation);
        }
       }   
       if(!empty($join)){
        $query->join(...$join);
       }
       return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
    }

    public function create(array $payload =[]){
        $model=$this->model->create($payload);
        return $model->fresh();
    }

    public function update(int $id = 0, array $payload = [])
    {
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

    public function all()
    {
        return  $this->model->all();;
    }

    public function findById(int $modelId, array $column = ['*'], array $relation = [])
    {
        return $this->model->select($column)->with($relation)->findOrFail($modelId);
       /* Lấy một model ($this->model).
        Chọn các cột được chỉ định ($column).
        Nạp các quan hệ được chỉ định ($relation).
        Tìm kiếm và trả về một bản ghi với ID là $modelId.*/
    }

}
