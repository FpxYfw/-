<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
{
    public function detailAction(Request $request)
    {
        try {
            $goodsId = $request->all('id');
            $openId = $request->all('openId');
            
            // 商品信息
            $info = DB::table('nideshop_goods')->where('id' , $goodsId)->get();

            // 获取商品的图片
            $gallery = DB::table('nideshop_goods_gallery')->where('goods_id', $goodsId)->get();

            // 商品参数
            // 关联查询两张表 leftJoin
            $attribute = DB::table('nideshop_goods_attribute')->leftJoin('nideshop_attribute','nideshop_goods_attribute.attribute_id','nideshop_attribute.id')->where('nideshop_goods_attribute.goods_id', $goodsId)->get();

            // 常见问题
            $issue = DB::table('nideshop_goods_issue')->get();

            // 大家都在看
            $productList = DB::table('nideshop_goods')->where('category_id', $info[0]->category_id)->get();

            // 判断是否收藏过
            $iscollect = DB::table('nideshop_collect')->where(['user_id' => $openId,'value_id' => $goodsId])->get();

            $collected = false;
            if ($iscollect == '[]') {
                $collected = true;
            }
            // 判断该用户的购物车里是否含有此商品
            $oldNumber = DB::table('nideshop_cart')->where('user_id', $openId)->get('number');
            
            $allnumber = 0;
            if (count($oldNumber) > 0) {
                for ($i = 0; $i < count($oldNumber); $i++) { 
                    $element = $oldNumber[$i];
                    $allnumber += $element->number;
                }
            }

            $data = [
                'info' => $info[0] || [],
                'gallery' => $gallery,
                'attribute' => $attribute,
                'issue' => $issue,
                'productList' => $productList,
                'collected' => $collected,
                'allnumber' => $allnumber
            ];

            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    public function goodsList(Request $request)
    {
        try {
            $categoryId = $request->all('categoryId');
            $goodsList = [];
            if ($categoryId) {
                $goodsList = DB::table('nideshop_goods')->where('category_id', $categoryId)->get();

                $currentNav = DB::table('nideshop_category')->where('id', $categoryId)->get();

                if (count($goodsList) == 0 ) {
                    $res = DB::table('nideshop_category')->where('parent_id', $categoryId)->get('id');
                    $subIds = [];
                    // 把对象中的id取出来保存成一个数组
                    foreach ($res as $key => $value) {
                        $subIds[] = $value->id;
                    }
                    $goodsList = DB::table('nideshop_goods')->whereIn('category_id', $subIds)->limit(50)->get();
                }
            }

            $data = [
                'goodsList' => $goodsList,
                'currentNav' => $currentNav[0]
            ];
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
