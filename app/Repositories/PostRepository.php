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
                    'posts.id',
                    'posts.post_catalouge_id',
                    'posts.image',
                    'posts.icon',
                    'posts.album',
                    'posts.publish',
                    'posts.follow',
                    'tb2.name',
                    'tb2.description',
                    'tb2.content',
                    'tb2.meta_title',
                    'tb2.meta_keyword',
                    'tb2.meta_description',
                    'tb2.canonical'
                ])
                ->join('post_languages as tb2', 'tb2.post_id', '=', 'posts.id')
                ->where('posts.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}