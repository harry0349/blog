<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('admin/login', 'Admin\LoginController@login');
Route::any('admin/captcha', 'Admin\LoginController@captcha');

Route::group(['middleware' => ['admin.login'],'prefix'=>'admin','namespace'=>'Admin'], function () {
	
    Route::get('quit', 'LoginController@quit');
    Route::get('index', 'IndexController@index');
    Route::get('info', 'IndexController@info');
    Route::any('pass', 'IndexController@pass');

    Route::resource('category', 'CategoryController');
    Route::post('category/search', 'CategoryController@search');
    Route::post('cate/changeorder', 'CategoryController@changeOrder');

    Route::resource('article', 'ArticleController');

    Route::post('links/changeorder', 'LinksController@changeOrder');
    Route::resource('links', 'LinksController');

    Route::post('navs/changeorder', 'NavsController@changeOrder');
    Route::resource('navs', 'NavsController');

    Route::post('config/changeorder', 'ConfigController@changeOrder');
    Route::resource('config', 'ConfigController');

    Route::get('redis/test1', 'TestController@test1');

    Route::any('upload', 'CommonController@upload');
});

Route::get('test', function(){
  if (\Cache::has('test')) {
    var_dump(Cache::has('test'));
    echo '存在chche,读取'.'<br />';
    echo \Cache::get('test');
  } else{
    echo '不存在cache,现在创建'.'<br />';
    $time = \Carbon\Carbon::now()->addMinutes(10);
    $redis = \Cache::add('test', '我是缓存资源', $time);
    echo \Cache::get('test');
  }
});