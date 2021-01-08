<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    // 关键字
    public function indexAction(Request $request)
    {
        try {
            $openId = $request->all('openId');

            // 默认关键字
            $defaultKeyword = DB::table('nideshop_keywords')->where('is_default', 1)->limit(1)->get();

            // 取出热门关键字
            $hotKeywordList = DB::table('nideshop_keywords')->distinct('keyword')->limit(10)->get();

            $historyData = DB::table('nideshop_search_history')->where('user_id', $openId)->limit(10)->get();

            $data = [
                'defaultKeyword' => $defaultKeyword[0],
                'hotKeywordList' => $hotKeywordList,
                'historyData' => $historyData
            ];
            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    // 添加搜索历史
    public function addHistoryAction(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $keyword = $request->all('keyword');
            $oldData = DB::table('nideshop_search_history')->where([
                'user_id' => $openId, 
                'keyword' => $keyword
            ])->get();
            if ($oldData == '[]') {
                $insert = [
                    'user_id' => $openId['openId'],
                    'keyword' => $keyword['keyword'],
                    'add_time' => time()
                ];
                $data = DB::table('nideshop_search_history')->insert($insert);
                if ($data) {
                    return $data = 'success';
                } else {
                    return $data = 'fail';
                }
            } else {
                return '已有记录';
            }

        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    // 清除历史记录
    public function clearHistoryAction(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $data = DB::table('nideshop_search_history')->where('user_id' , $openId)->delete();

            if ($data) {
                return '清楚成功';
            } else {
                return null;
            }
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }

    public function helperAction(Request $request)
    {
        try {
            $keyword = $request->all('keyword');

            $order = $request->all('order');

            if (!$order) {
                $order = '';
                $orderBy = 'id';
            } else {
                $orderBy = 'retail_price';
            }
            $res = DB::table('nideshop_goods')->orderBy($orderBy,$order)->where('name','like', '%' . $keyword['keyword']. '%')->limit(10)->get();

            $change = JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES;
            
            $keywords = trim(json_encode($res, $change),'[]');
            if ($keywords) {
                return $keywords;
            } else {
                return $keywords = [];
            }
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
