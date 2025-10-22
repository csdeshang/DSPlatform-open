<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('system', function () {
    

    // 系统信息
    Route::get('info', 'system.SysInfo/getSysInfo');



    // 系统配置 
    Route::get('config/getSysConfigList', 'system.SysConfig/getSysConfigList');
    //获取单个配置 通过config_key获取
    Route::get('config/info/:config_key', 'system.SysConfig/getSysConfigInfoByKey');
    // 编辑单个配置
    Route::post('config/updateSysConfigInfo', 'system.SysConfig/updateSysConfigInfo');


    //更新网站配置
    Route::post('config/editWebsite', 'system.SysConfig/editWebsite');
    // 编辑登录配置
    Route::post('config/editLoginConfig', 'system.SysConfig/editLoginConfig');
    // 编辑成长值规则
    Route::post('config/editGrowthRules', 'system.SysConfig/editGrowthRules');
    // 编辑积分规则
    Route::post('config/editPointsRules', 'system.SysConfig/editPointsRules');
    // 编辑商品规则
    Route::post('config/editGoodsRules', 'system.SysConfig/editGoodsRules');
    //邮件配置
    Route::post('config/editEmailConfig', 'system.SysConfig/editEmailConfig');
    Route::post('config/testEmailSend', 'system.SysConfig/testEmailSend');
    // 编辑订单自动取消规则
    Route::post('config/editOrderAutoConfig', 'system.SysConfig/editOrderAutoConfig');
    // 编辑提现配置
    Route::post('config/editUserWithdrawalRules', 'system.SysConfig/editUserWithdrawalRules');
    // 编辑分销配置
    Route::post('config/editDistributorConfig', 'system.SysConfig/editDistributorConfig');




    //地区相关修改
    Route::get('area/list', 'system.SysArea/getSysAreaList');
    Route::get('area/options', 'system.SysArea/getSysAreaOptions');
    Route::get('area/:id', 'system.SysArea/getSysAreaInfo');
    Route::put('area/:id', 'system.SysArea/updateSysArea');
    Route::post('area', 'system.SysArea/createSysArea');
    Route::delete('area/:id', 'system.SysArea/deleteSysArea');


    //平台相关
    Route::get('platform/list', 'system.SysPlatform/getSysPlatformList');
    Route::put('platform/:id', 'system.SysPlatform/updateSysPlatform');




    //系统错误日志
    Route::get('error-logs/pages', 'system.SysErrorLogs/getSysErrorLogsPages');
    Route::get('error-logs/:id', 'system.SysErrorLogs/getSysErrorLogsInfo');

    //系统访问日志
    Route::get('access-logs/pages', 'system.SysAccessLogs/getSysAccesslogsPages');
    Route::get('access-logs/:id', 'system.SysAccessLogs/getSysAccesslogsInfo');


    //物流相关修改
    Route::get('express/pages', 'system.SysExpress/getSysExpressPages');
    Route::get('express/list', 'system.SysExpress/getSysExpressList');
    Route::get('express/:id', 'system.SysExpress/getSysExpressInfo');
    Route::put('express/:id', 'system.SysExpress/updateSysExpress');
    Route::post('express', 'system.SysExpress/createSysExpress');
    Route::delete('express/:id', 'system.SysExpress/deleteSysExpress');


    //消息通知模板
    Route::get('notice/tpl/list', 'system.SysNoticeTpl/getSysNoticeTplList');
    Route::get('notice/tpl/:id', 'system.SysNoticeTpl/getSysNoticeTplInfo');
    Route::put('notice/tpl/:id', 'system.SysNoticeTpl/updateSysNoticeTpl');
    Route::post('notice/tpl/test', 'system.SysNoticeTpl/testSysNoticeTpl');
    Route::post('notice/tpl/toggle-field', 'system.SysNoticeTpl/toggleSysNoticeTplField');

    //消息通知记录
    Route::get('notice/log/pages', 'system.SysNoticeLog/getSysNoticeLogPages');
    Route::get('notice/log/:id', 'system.SysNoticeLog/getSysNoticeLogInfo');

    //消息通知记录
    Route::get('notice/sms/log/pages', 'system.SysNoticeSmsLog/getSysNoticeSmsLogPages');
    Route::get('notice/sms/log/:id', 'system.SysNoticeSmsLog/getSysNoticeSmsLogInfo');

    //短信服务商配置相关
    Route::get('sms/provider/list', 'system.SysSmsProvider/getSmsProviderList');
    Route::get('sms/provider/info', 'system.SysSmsProvider/getSmsProviderInfo');
    Route::post('sms/provider/update', 'system.SysSmsProvider/updateSmsProviderConfig');
    Route::post('sms/provider/test', 'system.SysSmsProvider/testSmsProvider');

    //快递查询服务商配置相关
    Route::get('express-provider/list', 'system.SysExpressProvider/getExpressProviderList');
    Route::get('express-provider/info', 'system.SysExpressProvider/getExpressProviderInfo');
    Route::post('express-provider/update', 'system.SysExpressProvider/updateExpressProviderConfig');
    Route::post('express-provider/query', 'system.SysExpressProvider/testExpressProvider');


    //文件存储配置相关
    Route::get('storage/provider/list', 'system.SysStorageProvider/getStorageProviderList');
    Route::get('storage/provider/info', 'system.SysStorageProvider/getStorageProviderInfo');
    Route::post('storage/provider/update', 'system.SysStorageProvider/updateStorageProviderConfig');

    //地图服务提供商配置相关
    Route::get('lbs/provider/list', 'system.SysLbsProvider/getLbsProviderList');
    Route::get('lbs/provider/info', 'system.SysLbsProvider/getLbsProviderInfo');
    Route::post('lbs/provider/update', 'system.SysLbsProvider/updateLbsProviderConfig');

    //协议相关
    Route::get('agreement/list', 'system.SysAgreement/getSysAgreementList');
    Route::get('agreement/:id', 'system.SysAgreement/getSysAgreementInfo');
    Route::put('agreement/:id', 'system.SysAgreement/updateSysAgreement');


    //系统文章分类
    Route::get('article/category/tree', 'system.SysArticleCategory/getSysArticleCategoryTree');
    Route::get('article/category/:id', 'system.SysArticleCategory/getSysArticleCategoryInfo');
    Route::put('article/category/:id', 'system.SysArticleCategory/updateSysArticleCategory');
    Route::post('article/category', 'system.SysArticleCategory/createSysArticleCategory');
    Route::delete('article/category/:id', 'system.SysArticleCategory/deleteSysArticleCategory');


    //系统文章
    Route::get('article/pages', 'system.SysArticle/getSysArticlePages');
    Route::get('article/:id', 'system.SysArticle/getSysArticleInfo');
    Route::put('article/:id', 'system.SysArticle/updateSysArticle');
    Route::post('article/create', 'system.SysArticle/createSysArticle');
    Route::post('article/del-batch', 'system.SysArticle/deleteBatchSysArticle');


    //系统清理
    Route::post('clear/cache', 'system.SysClear/clearCache');
    Route::post('clear/logs', 'system.SysClear/clearLogs');
    Route::post('clear/access-logs', 'system.SysClear/clearSysAccessLogs');
    Route::post('clear/error-logs', 'system.SysClear/clearSysErrorLog');
    Route::post('clear/admin-logs', 'system.SysClear/clearAdminLogs');

    //打印机服务商相关
    Route::get('printer/provider/list', 'system.SysPrinterProvider/getPrinterProviderList');
    Route::get('printer/provider/info', 'system.SysPrinterProvider/getPrinterProviderInfo');
    Route::post('printer/provider/update', 'system.SysPrinterProvider/updatePrinterProviderConfig');



})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
