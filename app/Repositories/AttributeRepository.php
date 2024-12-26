<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class AttributeRepository extends BaseRepository implements AttributeRepositoryInterface
{
    protected $model;

    public function __construct(
        Attribute $model
    ){
        $this->model = $model;
    }

    

    public function getAttributeById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'attributes.id',
                'attributes.attribute_catalouge_id',
                'attributes.image',
                'attributes.icon',
                'attributes.album',
                'attributes.publish',
                'attributes.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('attribute_languages as tb2', 'tb2.attribute_id', '=','attributes.id')
        ->with('attribute_catalouges')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }
    public function searchAttributes(string $keyword = '', array $option = [], int $languageId){
        return $this->model->whereHas('attribute_catalouges', function($query) use ($option){
            $query->where('attribute_catalouge_id', $option['attributeCatalougeId']);
        })->whereHas('attribute_language', function($query) use ($keyword){
            $query->where('name', 'like', '%'.$keyword.'%');
        })->get();
    }

    public function findAttributeByIdArray(array $attributeArray=[],int $languageId){
        return $this->model->select([
            'attributes.id',
            'tb2.name'
        ])
        ->join('attribute_languages as tb2', 'tb2.attribute_id', '=', 'attributes.id')
        ->where('tb2.language_id', '=', $languageId)
        ->whereIn('attributes.id', $attributeArray)
        ->get();
    }

}
