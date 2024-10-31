<?php

namespace App\Repositories;

use  App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Models\Router;


class RouterRepository extends BaseRepository implements RouterRepositoryInterface
{
    protected $model;

    public function __construct(Router $model)
    {
        $this->model = $model;
    }

   
}
