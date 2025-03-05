<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\MenuCatalougeRepositoryInterface  as MenuCatalougeRepository;
use App\Services\Interfaces\MenuCatalougeServiceInterface  as MenuCatalougeService;
use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Http\Requests\StoreMenuCatalougeRequest;


class MenuController extends Controller
{
    protected $menuCatalougeRepository;
    protected $menuCatalougeService;
    protected $menuService;

    public function __construct(
        MenuService $menuService,
        MenuCatalougeRepository $menuCatalougeRepository,
        MenuCatalougeService $menuCatalougeService
    ){
        $this->menuCatalougeRepository = $menuCatalougeRepository;
        $this->menuService = $menuService;
        $this->menuCatalougeService = $menuCatalougeService;
        $this->language=$this->currentLanguage();
    }

   public function createCatalouge(StoreMenuCatalougeRequest $request){
      $menuCatalouge=$this->menuCatalougeService->create($request);
      if($menuCatalouge!== FALSE){
        return response()->json([
                'code'=>0,
                'message'=>'Tạo nhóm menu thành công',
                'data'=>$menuCatalouge
          ]);;
      }
      return response()->json([
                'code'=>1,
                'message'=>'Tạo nhóm menu không thành công'
      ]);
   }

   public function drag(Request $request){
    $json=json_decode($request->string('json'),TRUE);
    $menuCatalougeId=$request->integer('menu_catalouge_id');

    $flag=$this->menuService->dragUpdate($json,$menuCatalougeId);

   }

}