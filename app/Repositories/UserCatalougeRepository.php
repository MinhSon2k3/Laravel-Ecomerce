<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\UserCatalougeRepositoryInterface;
use App\Models\UserCatalouge;
use App\Repositories\BaseRepository;
class UserCatalougeRepository extends BaseRepository implements UserCatalougeRepositoryInterface
{

    protected $model;
    
    public function __construct(UserCatalouge $model)
    {
        $this->model = $model;
    }

   
}