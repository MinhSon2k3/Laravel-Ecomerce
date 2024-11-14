<?php

namespace App\Repositories;

use App\Models\ProductCatalouge;
use App\Repositories\Interfaces\ProductCatalougeRepositoryInterface;
use App\Repositories\BaseRepository;

/**
 * Class UserService
 * @package App\Services
 */
class ProductCatalougeRepository extends BaseRepository implements ProductCatalougeRepositoryInterface
{
    protected $model;

    public function __construct(
        ProductCatalouge $model
    ){
        $this->model = $model;
    }

    

    public function getProductCatalougeById(int $id = 0, $language_id = 0){
        return $this->model->select([
                'product_catalouges.id',
                'product_catalouges.parent_id',
                'product_catalouges.image',
                'product_catalouges.icon',
                'product_catalouges.album',
                'product_catalouges.publish',
                'product_catalouges.follow',
                'tb2.name',
                'tb2.description',
                'tb2.content',
                'tb2.meta_title',
                'tb2.meta_keyword',
                'tb2.meta_description',
                'tb2.canonical',
            ]
        )
        ->join('product_catalouge_languages as tb2', 'tb2.product_catalouge_id', '=','product_catalouges.id')
        ->where('tb2.language_id', '=', $language_id)
        ->find($id);
    }

}
