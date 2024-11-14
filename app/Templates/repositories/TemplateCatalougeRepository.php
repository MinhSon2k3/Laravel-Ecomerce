<?php

namespace App\Repositories;

use App\Models\{$class}Catalouge;
use App\Repositories\Interfaces\{$class}CatalougeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class {$class}CatalougeRepository extends BaseRepository implements {$class}CatalougeRepositoryInterface
{
    protected $model;

    public function __construct(
        {$class}Catalouge $model
    ){
        $this->model = $model;
    }

    

    public function get{$class}CatalougeById(int $id = 0, $language_id = 0){
        return $this->model->select([
                '{module}_catalouges.id',
                '{module}_catalouges.parent_id',
                '{module}_catalouges.image',
                '{module}_catalouges.icon',
                '{module}_catalouges.album',
                '{module}_catalouges.publish',
                '{module}_catalouges.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('{module}_catalouge_language as tb2', 'tb2.{module}_catalouge_id', '=','{module}_catalouges.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
