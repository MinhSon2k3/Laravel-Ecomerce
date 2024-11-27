<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class RepositoryServiceProvider extends ServiceProvider
{

    public $serviceBindings=[
        //USER
        'App\Repositories\Interfaces\UserRepositoryInterface'=>'App\Repositories\UserRepository',
         //USER-CATALOGUE  
        'App\Repositories\Interfaces\UserCatalougeRepositoryInterface'=>'App\Repositories\UserCatalougeRepository',
         //Permission
        'App\Repositories\Interfaces\PermissionRepositoryInterface'=>'App\Repositories\PermissionRepository',
        //POST-CATALOUGE
        'App\Repositories\Interfaces\PostCatalougeRepositoryInterface'=>'App\Repositories\PostCatalougeRepository',
        //POST
        'App\Repositories\Interfaces\PostRepositoryInterface'=>'App\Repositories\PostRepository',
        //LANGUAGE
        'App\Repositories\Interfaces\LanguageRepositoryInterface'=>'App\Repositories\LanguageRepository',
        //Generate
        'App\Repositories\Interfaces\GenerateRepositoryInterface'=>'App\Repositories\GenerateRepository',
        //ADDRESS
        'App\Repositories\Interfaces\ProvinceRepositoryInterface'=>'App\Repositories\ProvinceRepository',
        'App\Repositories\Interfaces\DistrictRepositoryInterface'=>'App\Repositories\DistrictRepository',
        //Router
        'App\Repositories\Interfaces\RouterRepositoryInterface'=>'App\Repositories\RouterRepository',
        
        'App\Repositories\Interfaces\ProductCatalougeRepositoryInterface' => 'App\Repositories\ProductCatalougeRepository',
        'App\Repositories\Interfaces\ProductRepositoryInterface' => 'App\Repositories\ProductRepository',
        'App\Repositories\Interfaces\AttributeCatalougeRepositoryInterface' => 'App\Repositories\AttributeCatalougeRepository',
        'App\Repositories\Interfaces\AttributeRepositoryInterface' => 'App\Repositories\AttributeRepository',
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach($this->serviceBindings as $key => $val ){
            $this->app->bind($key,$val);
        }
          
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        schema::defaultStringLength(191);
    }
}
