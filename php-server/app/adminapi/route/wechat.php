<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 微信公众号
Route::group('wechat', function () {

    // 微信公众号消息模板列表
    Route::get('official/templates/list', 'wechat.WechatOfficialTemplate/getWechatOfficialTemplateList');
    Route::post('official/templates/sync', 'wechat.WechatOfficialTemplate/syncWechatOfficialTemplate');
    // official/templates/:key (3段) 必须在 official/templates/list (3段) 后面，但实际不会冲突因为HTTP方法不同
    Route::delete('official/templates/:key', 'wechat.WechatOfficialTemplate/deleteWechatOfficialTemplate');

    // 微信公众号设置
    Route::get('official/settings', 'wechat.WechatOfficialSetting/getWechatOfficialSetting');
    Route::put('official/settings', 'wechat.WechatOfficialSetting/updateWechatOfficialSetting');

    // 微信公众号菜单
    Route::post('official/menus/publish', 'wechat.WechatOfficialMenu/publishWechatOfficialMenu');
    Route::get('official/menus', 'wechat.WechatOfficialMenu/getWechatOfficialMenu');
    Route::put('official/menus', 'wechat.WechatOfficialMenu/updateWechatOfficialMenu');

    // ============ 微信小程序模板管理 ============
    
    // 获取sys_notice_tpl表中支持微信小程序的模板列表
    Route::get('mini/templates/list', 'wechat.WechatMiniTemplate/getWechatMiniTemplateList');
    Route::post('mini/templates/sync', 'wechat.WechatMiniTemplate/syncWechatMiniTemplate');
    // mini/templates/:key (3段) 必须在 mini/templates/list (3段) 后面，但实际不会冲突因为HTTP方法不同
    Route::delete('mini/templates/:key', 'wechat.WechatMiniTemplate/deleteWechatMiniTemplate');
    


    // 微信小程序设置
    Route::get('mini/settings', 'wechat.WechatMiniSetting/getWechatMiniSetting');
    Route::put('mini/settings', 'wechat.WechatMiniSetting/updateWechatMiniSetting');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);