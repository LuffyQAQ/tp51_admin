<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// Route::get('think', function () {
//     return 'hello,ThinkPHP5!';
// });

// Route::get('hello/:name', 'index/hello');

// return [

// ];



Route::get('captcha','admin/captcha');
Route::get('admin/login','admin/Admin/login');
Route::get('admin/geetest','admin/Admin/geetest');
Route::post('admin/logging','admin/Admin/logging');
Route::get('admin/logout','admin/Admin/logout');
Route::get('admin/clear','admin/Admin/clear');
Route::get('admin/err','admin/Admin/err');

Route::group('admin',function (){
    //首页
    Route::get('index','admin/Index/index');
    Route::get('content','admin/Index/content');
    //备份数据库
    Route::get('data/index','admin/Data/index');
    Route::any('data/optimize','admin/Data/optimize');
    Route::any('data/repair','admin/Data/repair');
    Route::any('data/export','admin/Data/export');
    Route::get('data/import','admin/Data/import');
    Route::get('data/revert','admin/Data/revert');
    Route::get('data/delete','admin/Data/delete');

    //用户组管理
    Route::get('group/index','admin/Group/index');
    Route::get('group/create','admin/Group/create');
    Route::post('group/save','admin/Group/save');
    Route::put('group/checked/:id','admin/Group/checked');
    Route::put('group/unchecked/:id','admin/Group/unchecked');
    Route::get('group/delete/:id','admin/Group/delete');
    Route::get('group/edit/:id','admin/Group/edit');
    Route::post('group/update/:id','admin/Group/update');

    //角色管理
    Route::get('role/index','admin/Role/index');
    Route::get('role/create','admin/Role/create');
    Route::post('role/save','admin/Role/save');
    Route::get('role/edit/:id','admin/Role/edit');
    Route::post('role/update/:id','admin/Role/update');
    Route::get('role/delete/:id','admin/Role/delete');
    //规则管理
    Route::get('rule/indexList','admin/Rule/indexList');
    Route::get('rule/create','admin/Rule/create');
    Route::post('rule/save','admin/Rule/save');
    Route::get('rule/index/:id','admin/Rule/index');
    Route::post('rule/update/:id','admin/Rule/update');
    Route::put('rule/checked/:id','admin/Rule/checked');
    Route::put('rule/unchecked/:id','admin/Rule/unchecked');
    Route::get('rule/edit/:id','admin/Rule/edit');
    Route::post('rule/usave/:id','admin/Rule/usave');
    Route::get('rule/delete/:id','admin/Rule/delete');
    Route::post('rule/deleted','admin/Rule/deleted');
})->middleware(['CheckLogin', 'CheckAuth']);

