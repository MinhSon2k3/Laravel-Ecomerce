<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\MenuCatalougeRepositoryInterface  as MenuCatalougeRepository;
use App\Services\Interfaces\MenuCatalougeServiceInterface  as MenuCatalougeService;
use App\Http\Requests\StoreMenuCatalougeRequest;


class MenuController extends Controller
{
    protected $menuCatalougeRepository;
    protected $menuCatalougeService;

    public function __construct(
        MenuCatalougeRepository $menuCatalougeRepository,
        MenuCatalougeService $menuCatalougeService
    ){
        $this->menuCatalougeRepository = $menuCatalougeRepository;
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

}