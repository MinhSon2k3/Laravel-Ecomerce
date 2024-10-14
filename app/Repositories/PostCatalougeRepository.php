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

   
}