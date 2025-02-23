<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\MenuCatalougeRepositoryInterface;
use App\Models\MenuCatalouge;
use App\Repositories\BaseRepository;
class MenuCatalougeRepository extends BaseRepository implements MenuCatalougeRepositoryInterface
{

    protected $model;
    
    public function __construct(MenuCatalouge $model)
    {
        $this->model = $model;
    }

  public function getMenuById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
                    'menus.id',
                    'menus.Menu_catalouge_id',
                    'menus.image',
                    'menus.icon',
                    'menus.album',
                    'menus.publish',
                    'menus.follow',
                    'tb2.name',
                    'tb2.description',
                    'tb2.content',
                    'tb2.meta_title',
                    'tb2.meta_keyword',
                    'tb2.meta_description',
                    'tb2.canonical'
                ])
                ->join('Menu_languages as tb2', 'tb2.Menu_id', '=', 'menus.id')
                ->where('menus.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}