<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function listAction(Request $request)
    {
        try {
            $page = $request->all('page');

            $size = 5;

            $res = DB::table('nideshop_topic')->limit($size)->offset(($page['page'] - 1) * $page['page'])->get();
            $data1 = DB::table('nideshop_topic')->paginate(5);

            $data = [
                'page' => $page,
                'total' => 4,
                'data' => $res
            ];

            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
    public function detailAction(Request $request)
    {
        try {
            $id = $request->all('id');
            $res = [];
            
            if ($id['id']) {
                $res = DB::table('nideshop_topic')->where([
                    'id' => $id['id']
                ])->get();
            }

            $recommendList = DB::table('nideshop_topic')->limit(4)->get();

            $data = [
                'data' => $res[0],
                'recommendList' => $recommendList
            ];

            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
