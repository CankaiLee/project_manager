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

    /* ******************* 菜单 Start ******************** */

    Route::get('/menu/all', 'MenuController@all'); // 菜单列表
    Route::post('/menu/add', 'MenuController@add'); // 添加菜单
    Route::post('/menu/mod', 'MenuController@mod'); // 编辑菜单
    Route::get('/menu/del', 'MenuController@del'); // 删除菜单
    Route::post('/menu/set_status', 'MenuController@set_status'); // 设置启用或者禁用

    /* ******************* 菜单 End ******************** */

    /* ******************* 文件上传 Start ******************** */

    Route::post('/upload', 'UploadController@up');

    /* ******************* 文件上传 End ******************** */

    /* ******************* 项目 Start ******************** */

    Route::get('/project/all', 'ProjectController@all'); // 菜单列表
    Route::post('/project/add', 'ProjectController@add'); // 添加菜单
    Route::post('/project/mod', 'ProjectController@mod'); // 编辑菜单
    Route::get('/project/del', 'ProjectController@del'); // 删除菜单
    Route::post('/project/set_status', 'ProjectController@set_status'); // 设置状态

    /* ******************* 项目 End ******************** */
});
