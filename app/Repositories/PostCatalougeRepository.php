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

  public function getPostCatalougeById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
                    'post_catalouges.id',
                    'post_catalouges.parent_id',
                    'post_catalouges.image',
                    'post_catalouges.icon',
                    'post_catalouges.album',
                    'post_catalouges.publish',
                    'post_catalouges.follow',
                    'tb2.name',
                    'tb2.description',
                    'tb2.content',
                    'tb2.meta_title',
                    'tb2.meta_keyword',
                    'tb2.meta_description',
                    'tb2.canonical'
                ])
                ->join('post_catalouge_languages as tb2', 'tb2.post_catalouge_id', '=', 'post_catalouges.id')
                ->where('post_catalouges.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}