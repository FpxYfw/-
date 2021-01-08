<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public function getListAction(Request $request)
    {
        try {
            $openId = $request->all('openId');
            $addressList = DB::table('nideshop_address')->where('user_id', $openId['openId'])->orderBy('is_default', 'desc')->get();
            $data = $addressList;
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

            $detailData = DB::table('nideshop_address')->where('id',$id['id'])->get();

            $data = $detailData;
            return $data;
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
    public function saveAction(Request $request)
    {
        try {
            $addressId = $request->all('addressId');
            $res = $request->all();

            if ($res['checked']) {
                $isDefault = DB::table('nideshop_address')->where([
                    'user_id' => $res['openId'],
                    'is_default' => 1
                ])->get();

                $isDefault = json_encode($isDefault);

                if ($isDefault !== '[]') {
                    DB::table('nideshop_address')->where([
                        'user_id' => $res['openId'],
                        'is_default' => 1
                    ])->update([
                        'is_default' => 0
                    ]);
                }
            }
            if ($addressId['addressId'] == 'false') {
                $data = DB::table('nideshop_address')->insert([
                    'name' => $res['userName'],
                    'mobile' => $res['telNumber'],
                    'address' => $res['address'],
                    'address_detail' => $res['detailaddress'],
                    'user_id' => $res['openId'],
                    'is_default' => $res['checked'] == 'true' || $res['checked'] ? 1 : 0
                ]);
                if ($data) {
                    return 'true';
                } else {
                    return 'false';
                }
            } else {
                $data = DB::table('nideshop_address')->where([
                    'id' => $addressId['addressId']
                ])->update([
                    'name' => $res['userName'],
                    'mobile' => $res['telNumber'],
                    'address' => $res['address'],
                    'address_detail' => $res['detailaddress'],
                    'user_id' => $res['openId'],
                    'is_default' => $res['checked'] == 'true' || $res['checked'] ? 1 : 0
                ]);
                if ($data) {
                    return 'true';
                } else {
                    return 'false';
                }
            }
        } catch (\Exception $e) {
            echo $e->getCode();
            echo $e->getMessage();
        }
    }
}
