<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // 获取分类列表，导航栏
    public function categoryNav(Request $request)
    {
        try {
            $change = JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;

            $categoryId = $request->all('id');

            // 获取分类
            $currentNav = DB::table('nideshop_category')->where('id',$categoryId)->get();

            // 获取它的同类
            $navData = DB::table('nideshop_category')->where('parent_id', $currentNav[0]->parent_id)->get();

            $navData = trim($navData, '[]');
            $data = [
                'navData' => $navData,
                'currentNav' => $currentNav[0]
            ];

            $data = json_encode($data, $change);
            // dd($data);
            return $data;

        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    // 分类页面
    public function indexAction(Request $request)
    {
        try {
            $change = JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;
            $categoryId = $request->all('id');
            $data = DB::table('nideshop_category')->where('parent_id', 0)->get();

            $currentCategory = [];
            
            if ($categoryId) {
                $currentCategory = DB::table('nideshop_category')->where('parent_id', $categoryId)->get();
            }

            $categoryList = trim(json_encode($data,$change),'[]');

            return $categoryList;
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }


    // 点击左侧菜单获取的分类商品
    public function currentAction(Request $request)
    {
        try {
            $change = JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;
            $categoryId = $request->all('id');
            
            $currentOne = DB::table('nideshop_category')->where('id', $categoryId)->get();
            $subList = DB::table('nideshop_category')->where('parent_id', $currentOne[0]->id)->get();

            $data = [
                'currentOne' => $currentOne,
                'subList' => $subList
            ];
            $data = trim(json_encode($data,$change),'[]');

            return $data;
           
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
    
}
