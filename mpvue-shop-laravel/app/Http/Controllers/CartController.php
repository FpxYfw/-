<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function cartList(Request $request)
    {
        try {

            $openId = $request->all('openId');

            $cartList = DB::table('nideshop_cart')->where('user_id',$openId)->get();

            $data = $cartList;
            return $data;
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    public function addCart(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $goodsId = $request->all('goodsId');
            $number = $request->all('number');
            $res = $request->all();

            $haveGoods = DB::table('nideshop_cart')->where([
                'user_id' => $openId['openId'],
                'goods_id' => $goodsId['goodsId']
                ])->get();

            $haveGoods = json_encode($haveGoods);
                
            if ($haveGoods == '[]') {
                $goods = DB::table('nideshop_goods')->where('id', $goodsId['goodsId'])->get();

                $goods = json_encode($goods);
                if ($goods !== '[]') {
                    $goods = [
                        $goods[0]->retail_price,
                        $goods[0]->name,
                        $goods[0]->list_pic_url,
                    ];
                } else {
                    $test = DB::table('nideshop_cart')->insert([
                        'user_id'=> $openId['openId'],
                        'goods_id'=> $goodsId['goodsId'],
                        'number' => $res['number'],
                        'goods_name'=> $res['name'],
                        'retail_price' => $res['retail_price'],
                        'list_pic_url' => $res['list_pic_url']
                    ]);
                }
            } else {
                $oldNumber = DB::table('nideshop_cart')->where([
                    'user_id' => $openId['openId'],
                    'goods_id' => $goodsId['goodsId']
                ])->get('number');
                DB::table('nideshop_cart')->where([
                    'user_id' => $openId['openId'],
                    'goods_id' => $goodsId['goodsId']
                ])->update([
                    'number' => $oldNumber[0]->number + $res['number']
                ]);
            }
            return 'success';
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
