<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::post('/user/register', 'UserController@register'); // 注册
Route::post('/user/login', 'UserController@login'); // 登录


Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('/user/profile', 'UserController@profile'); // 个人资料
    Route::get('/user/logout', 'UserController@logout'); // 登出
});
