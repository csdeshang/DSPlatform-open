<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 微信公众号
Route::group('wechat', function () {

    // 微信公众号消息模板列表
    Route::get('official/template/list', 'wechat.WechatOfficialTemplate/getWechatOfficialTemplateList');
    Route::post('official/template/sync', 'wechat.WechatOfficialTemplate/syncWechatOfficialTemplate');
    Route::post('official/template/delete', 'wechat.WechatOfficialTemplate/deleteWechatOfficialTemplate');

    // 微信公众号设置
    Route::get('official/setting', 'wechat.WechatOfficialSetting/getWechatOfficialSetting');
    Route::put('official/setting', 'wechat.WechatOfficialSetting/updateWechatOfficialSetting');

    // 微信公众号菜单
    Route::get('official/menu', 'wechat.WechatOfficialMenu/getWechatOfficialMenu');
    Route::post('official/menu/update', 'wechat.WechatOfficialMenu/updateWechatOfficialMenu');
    Route::post('official/menu/publish', 'wechat.WechatOfficialMenu/publishWechatOfficialMenu');

    // ============ 微信小程序模板管理 ============
    
    // 获取sys_notice_tpl表中支持微信小程序的模板列表
    Route::get('mini/template/list', 'wechat.WechatMiniTemplate/getWechatMiniTemplateList');
    Route::post('mini/template/sync', 'wechat.WechatMiniTemplate/syncWechatMiniTemplate');
    Route::post('mini/template/delete', 'wechat.WechatMiniTemplate/deleteWechatMiniTemplate');
    


    // 微信小程序设置
    Route::get('mini/setting', 'wechat.WechatMiniSetting/getWechatMiniSetting');
    Route::put('mini/setting', 'wechat.WechatMiniSetting/updateWechatMiniSetting');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);