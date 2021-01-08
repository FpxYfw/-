<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectController extends Controller
{
    public function addCollect(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $goodsId = $request->all('goodsId');
            // 判断是否已经收藏
            $iscollect = DB::table('nideshop_collect')->where(['user_id'=> $openId,'value_id' => $goodsId])->get();
            $iscollect = json_encode($iscollect, true);
            if ($iscollect == '[]') {
                $res = DB::table('nideshop_collect')->insert(['user_id' => $openId['openId'],'value_id' => $goodsId['goodsId']]);

                return 'collected';
            } else {
                DB::table('nideshop_collect')->where([
                    'user_id' => $openId,
                    'value_id' => $goodsId
                ])->delete();

                return 'uncollect';
            }
        } catch (Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
