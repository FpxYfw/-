<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 首页相关的接口
Route::get('/index/index', 'HomeController@index');

// 分类相关的接口
Route::get('/category/categoryNav', 'CategoryController@categoryNav');
Route::get('/category/indexaction', 'CategoryController@indexAction');
Route::get('/category/currentaction', 'CategoryController@currentAction');

// 搜索相关的接口
Route::get('/search/indexaction', 'SearchController@indexAction');
Route::post('/search/addhistoryaction', 'SearchController@addHistoryAction');
Route::get('/search/helperaction', 'SearchController@helperAction');     // 搜索提示 
Route::post('/search/clearhistoryAction', 'SearchController@clearHistoryAction');  // 清楚搜索历史

// 商品相关的接口
Route::get('/goods/detailaction', 'GoodsController@detailAction');
Route::get('/goods/goodsList', 'GoodsController@goodsList');

// 收藏相关的接口
Route::post('/collect/addcollect', 'CollectController@addCollect');

// 订单相关的接口
Route::get('/order/detailAction', 'OrderController@detailAction');
Route::post('/order/submitAction', 'OrderController@submitAction');

// 购物车相关的接口
Route::get('/cart/cartList', 'Cartcontroller@cartList');
Route::post('/cart/addCart', 'Cartcontroller@addCart');

// 收货地址相关的接口
Route::get('/address/getListAction', 'AddressController@getListAction');
Route::get('/address/detailAction', 'AddressController@detailAction');
Route::post('/address/saveAction', 'AddressController@saveAction');

// 专题相关的接口
Route::get('/topic/listaction', 'TopicController@listAction');
Route::get('/topic/detailaction', 'TopicController@detailAction');