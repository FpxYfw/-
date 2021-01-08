<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\ElasticaHandler;

class OrderController extends Controller
{
    public function submitAction(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $goodsId = $request->all('goodsId');
            $allPrice = $request->all('allPrice');
            

            $isOrder = DB::table('nideshop_order')->where('user_id', $openId)->get();

            $isOrder = json_encode($isOrder);
            if ($isOrder !== '[]') {
                $data = DB::table('nideshop_order')->where('user_id', $openId)->update([
                    'user_id' => $openId['openId'],
                    'goods_id' => $goodsId['goodsId'],
                    'allprice' => $allPrice['allPrice']
                ]);
                $data = json_encode($data);

                if ($data == '1') {
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                $data = DB::table('nideshop_order')->insert([
                    'user_id' => $openId['openId'],
                    'goods_id' => $goodsId['goodsId'],
                    'allprice' => $allPrice['allPrice']
                ]);
                $data = json_encode($data);

                if ($data == 'true') {
                    return true;
                } else {
                    return false;
                }
            }
            

        }  catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    public function detailAction(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $addressId = $request->all('addressId');

            $orderDetail = DB::table('nideshop_order')->where('user_id', $openId)->get();

            $goodsIds = $orderDetail[0]->goods_id;

            $list = DB::table('nideshop_cart')->where(['user_id'=> $openId,'goods_id'=>'1009024'])->get();
            $addressList;
            if ($addressId) {
                $addressList = DB::table('nideshop_address')->where(['user_id' => $openId['openId'],'id' => $addressId['addressId']])->orderBy('is_default', 'desc')->get();
            } else {
                $addressList = DB::table('nideshop_address')->where('user_id', $openId['openId'])->orderBy('is_default', 'desc')->get();
            }
            $data = [
                'price' => $orderDetail[0]->allprice,
                'goodsList' => $list,
                'address' => $addressList[0]
            ];
            return $data;
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
