<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Models\Menu;
use App\Repositories\BaseRepository;
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{

    protected $model;
    
    public function __construct(Menu $model)
    {
        $this->model = $model;
    }

  public function getMenuById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
                    'menus.id',
                    'menus.menu_catalouge_id',
                    'menus.publish',
                    'tb2.name',
                ])
                ->join('menu_languages as tb2', 'tb2.menu_id', '=', 'menus.id')
                ->where('menus.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}