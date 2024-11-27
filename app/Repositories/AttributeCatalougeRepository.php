<?php

namespace App\Repositories;

use App\Models\AttributeCatalouge;
use App\Repositories\Interfaces\AttributeCatalougeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AttributeCatalougeRepository extends BaseRepository implements AttributeCatalougeRepositoryInterface
{
    protected $model;

    public function __construct(
        AttributeCatalouge $model
    ){
        $this->model = $model;
    }

    

    public function getAttributeCatalougeById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'attribute_catalouges.id',
                'attribute_catalouges.parent_id',
                'attribute_catalouges.image',
                'attribute_catalouges.icon',
                'attribute_catalouges.album',
                'attribute_catalouges.publish',
                'attribute_catalouges.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('attribute_catalouge_languages as tb2', 'tb2.attribute_catalouge_id', '=','attribute_catalouges.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
