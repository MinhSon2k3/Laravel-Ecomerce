<?php
namespace App\Repositories;

use  App\Repositories\Interfaces\{Module}RepositoryInterface;
use App\Models\{Module};
use App\Repositories\BaseRepository;
class {Module}Repository extends BaseRepository implements {Module}RepositoryInterface
{

    protected $model;
    
    public function __construct({Module} $model)
    {
        $this->model = $model;
    }

  public function get{Module}ById(int $id = 0, $language_id = 0)
    {
        return $this->model->select([
                    '{tableName}s.id',
                    '{tableName}s.parent_id',
                    '{tableName}s.image',
                    '{tableName}s.icon',
                    '{tableName}s.album',
                    '{tableName}s.publish',
                    '{tableName}s.follow',
                    'tb2.name',
                    'tb2.description',
                    'tb2.content',
                    'tb2.meta_title',
                    'tb2.meta_keyword',
                    'tb2.meta_description',
                    'tb2.canonical'
                ])
                ->join('{tableName}_languages as tb2', 'tb2.{tableName}_id', '=', '{tableName}s.id')
                ->where('{tableName}s.id', $id)
                ->where('tb2.language_id', $language_id)
                ->first();
    }
}