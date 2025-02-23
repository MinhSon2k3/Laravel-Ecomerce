<?php
namespace App\Services;

use  App\Services\Interfaces\MenuCatalougeServiceInterface;
use  App\Services\BaseService;
use  App\Classes\Nestedsetbie;
use App\Repositories\Interfaces\MenuCatalougeRepositoryInterface as MenuCatalougeRepository;//tương tác với database
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class MenuCatalougeService  extends BaseService implements MenuCatalougeServiceInterface
{
    protected $menuCatalougeRepository;

    public function __construct( MenuCatalougeRepository $menuCatalougeRepository){
        $this->menuCatalougeRepository=$menuCatalougeRepository;
    }
  

    public function create($request){
        DB::beginTransaction();
        try{
            $payload=$request->only('name','keyword');
            $menuCatalouge=$this->menuCatalougeRepository->create($payload);
            DB::commit();
            return [
                'flag'=>TRUE,
                'name'=>$menuCatalouge->name,
                'id'=>$menuCatalouge->id
            ];
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e->getMessage());
            return false;
        }

    }



}