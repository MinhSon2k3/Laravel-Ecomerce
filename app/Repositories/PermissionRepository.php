<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Models\Permission;
use App\Repositories\BaseRepository;
class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{

    protected $model;
    
    public function __construct(Permission $model)
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
                ->orWhere('canonical','LIKE','%'.$condition['keyword'].'%');
            }
            if(isset($condition['publish']) && $condition['publish']!=0){
              
                $query->where('publish','=',$condition['publish']);
            }
            return $query;
        });
        if(!empty($join)){
         $query->join(...$join);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL').$extend['path']);
     }

     public function findCurrentPermission(){
        return $this->model->select('canonical')->where('current','=',1)->first();
     }
}