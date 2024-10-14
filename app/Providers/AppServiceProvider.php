<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{

    public $serviceBindings=[
        //USER
        'App\Services\Interfaces\UserServiceInterface'=>'App\Services\UserService',
        'App\Repositories\Interfaces\UserRepositoryInterface'=>'App\Repositories\UserRepository',
         //LANGUAGE
        'App\Services\Interfaces\LanguageServiceInterface'=>'App\Services\LanguageService',
        'App\Repositories\Interfaces\LanguageRepositoryInterface'=>'App\Repositories\LanguageRepository',
         //USER-CATALOGUE  
        'App\Services\Interfaces\UserCatalougeServiceInterface'=>'App\Services\UserCatalougeService',
        'App\Repositories\Interfaces\UserCatalougeRepositoryInterface'=>'App\Repositories\UserCatalougeRepository',
        //ADDRESS
        'App\Repositories\Interfaces\ProvinceRepositoryInterface'=>'App\Repositories\ProvinceRepository',
        'App\Repositories\Interfaces\DistrictRepositoryInterface'=>'App\Repositories\DistrictRepository',
        //POST-CATALOUGE
        'App\Services\Interfaces\PostCatalougeServiceInterface'=>'App\Services\PostCatalougeService',
        'App\Repositories\Interfaces\PostCatalougeRepositoryInterface'=>'App\Repositories\PostCatalougeRepository',
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
