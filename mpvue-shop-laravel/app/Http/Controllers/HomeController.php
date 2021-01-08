<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $data = [];
            // 解决 json 格式数据中文的转义，以及https地址转义
            $change = JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;

            // 轮播图数据
            $banner = DB::table('nideshop_ad')->where('ad_position_id',1)->get();
            // tab 类型
            $channel = DB::table('nideshop_channel')->get();
            
            // 品牌列表
            $brandList = DB::table('nideshop_brand')->where('is_new', 1)->orderBy('new_sort_order','desc')->limit(4)->get();
            
            // 新品首发
            $newGoods = DB::table('nideshop_goods')->whereIn('id', [1181000, 1135002, 1134030, 1134032])->where('is_new', 1)->get();
            
            // 人气推荐
            $hotGoods = DB::table('nideshop_goods')->where('is_hot',1)->limit(5)->get();
            
            // 专题精选
            $topicList = DB::table('nideshop_topic')->limit(3)->get();
            
            // 类别列表 -- 好物   未实现
            $categoryList = DB::table('nideshop_category')->where('parent_id',0)->get();

            $newCategoryList = [];

            for ($i = 0; $i < count($categoryList); $i++) { 
                $item = $categoryList[$i];
                $item = [];
                    foreach ($categoryList as $key => $value) {
                        $item[] = $categoryList[$key]->id;
                    }
                    
                    $res = DB::table('nideshop_category')->where('parent_id',$item)->get();

                    $childCategoryIds = [];
                    
                    foreach ($res as $key => $value) {
                        $childCategoryIds[] = $res[$key]->id;
                    }

                    $categoryGoods = DB::table('nideshop_goods')->where('category_id', $childCategoryIds)->limit(7)->get();

                    $newCategoryList = [
                        'id' => $childCategoryIds,
                        'name' => $res,
                        'goodsList' => $categoryGoods
                    ];

                }
                

            $data = [
                'banner' => $banner,
                'channel' => $channel,
                'brandList' => $brandList,
                'newGoods' => $newGoods,
                'hotGoods' => $hotGoods,
                'topicList' => $topicList,
                'newCategoryList' => $newCategoryList
            ];
            
            
            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
