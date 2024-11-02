<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Repositories\BaseRepository;
class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    protected $model;
    
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function pagination(
        array $column=['*'],
        array $condition=[],
        array $join=[],
        array $extend=[],
        array $relations = [],
        array $orderBy=[],
        int $perpage=5,
       
         ){
            
        $query = $this->model->select($column)->where(function($query) use ($condition){
            if(isset($condition['keyword']) && !empty($condition['keyword'])){
                $query->where('name','LIKE','%'.$condition['keyword'].'%')
                ->orWhere('email','LIKE','%'.$condition['keyword'].'%')
                ->orWhere('phone','LIKE','%'.$condition['keyword'].'%')
                ->orWhere('address','LIKE','%'.$condition['keyword'].'%');
            }
            if(isset($condition['publish']) && $condition['publish']!=0){
              
                $query->where('publish','=',$condition['publish']);
            }
            if(isset($condition['user_catalouge_id']) && $condition['user_catalouge_id']!=0){
              
                $query->where('user_catalouge_id','=',$condition['user_catalouge_id']);
            }
            return $query;
        })->with('user_catalouges');//lấy thông tin từ model user_catalouges của public function user_catalouge() của model User để show chung
        if(!empty($join)){
         $query->join(...$join);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
     }
}