<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\PostCatalougeRepositoryInterface;
use App\Models\PostCatalouge;
use App\Repositories\BaseRepository;
class PostCatalougeRepository extends BaseRepository implements PostCatalougeRepositoryInterface
{

    protected $model;
    
    public function __construct(PostCatalouge $model)
    {
        $this->model = $model;
    }

    public function pagination(
        array $column=['*'],
        array $condition=[],
        array $join=[],
        array $extend=[],
        array $relations = [],
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
}