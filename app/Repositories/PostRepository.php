<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Repositories\BaseRepository;
class PostRepository extends BaseRepository implements PostRepositoryInterface
{

    protected $model;
    
    public function __construct(Post $model)
    {
        $this->model = $model;
    }

  public function getPostById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
                    'post_s.id',
                    'post_s.parent_id',
                    'post_s.image',
                    'post_s.icon',
                    'post_s.album',
                    'post_s.publish',
                    'post_s.follow',
                    'tb2.name',
                    'tb2.description',
                    'tb2.content',
                    'tb2.meta_title',
                    'tb2.meta_keyword',
                    'tb2.meta_description',
                    'tb2.canonical'
                ])
                ->join('post__languages as tb2', 'tb2.post__id', '=', 'post_s.id')
                ->where('post_s.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}