<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('system', function () {
    

    // 系统信息
    Route::get('system-info', 'system.SysInfo/getSysInfo');



    // 系统配置
    Route::get('configs/list', 'system.SysConfig/getSysConfigList');
    // 批量更新特定类型配置（具体路径必须在动态路径前面）
    // 更新网站配置
    Route::put('configs/website', 'system.SysConfig/editWebsite');
    // 编辑登录配置
    Route::put('configs/login', 'system.SysConfig/editLoginConfig');
    // 编辑成长值规则
    Route::put('configs/growth-rules', 'system.SysConfig/editGrowthRules');
    // 编辑积分规则
    Route::put('configs/points-rules', 'system.SysConfig/editPointsRules');
    // 编辑商品规则
    Route::put('configs/goods-rules', 'system.SysConfig/editGoodsRules');
    // 邮件配置
    Route::put('configs/email', 'system.SysConfig/editEmailConfig');
    Route::post('configs/email/test', 'system.SysConfig/testEmailSend');
    // 编辑订单自动取消规则
    Route::put('configs/order-auto', 'system.SysConfig/editOrderAutoConfig');
    // 编辑提现配置
    Route::put('configs/withdrawal-rules', 'system.SysConfig/editUserWithdrawalRules');
    // 编辑分销配置
    Route::put('configs/distributor', 'system.SysConfig/editDistributorConfig');
    // 获取单个配置 通过config_key获取
    // 注意：使用 configs/items/:config_key 而不是 configs/:config_key 避免与批量更新路径冲突
    // 例如：configs/website 是批量更新，configs/items/website 是单个配置项更新
    Route::get('configs/items/:config_key', 'system.SysConfig/getSysConfigInfoByKey');
    // 编辑单个配置 通过config_key更新
    // 注意：使用 configs/items/:config_key 避免与批量更新路径冲突
    Route::put('configs/items/:config_key', 'system.SysConfig/updateSysConfigInfo');




    //地区相关修改
    // areas/list (2段) 必须在 areas/:id (2段) 前面 否则 GET /areas/list 会被 areas/:id 匹配
    Route::get('areas/list', 'system.SysArea/getSysAreaList');
    // areas/options (2段) 必须在 areas/:id (2段) 前面
    Route::get('areas/options', 'system.SysArea/getSysAreaOptions');
    Route::get('areas/:id', 'system.SysArea/getSysAreaInfo');
    Route::put('areas/:id', 'system.SysArea/updateSysArea');
    Route::post('areas', 'system.SysArea/createSysArea');
    Route::delete('areas/:id', 'system.SysArea/deleteSysArea');


    //平台相关
    // platforms/list (2段) 必须在 platforms/:id (2段) 前面
    Route::get('platforms/list', 'system.SysPlatform/getSysPlatformList');
    Route::put('platforms/:id', 'system.SysPlatform/updateSysPlatform');


    //系统错误日志
    Route::get('error-logs/pages', 'system.SysErrorLogs/getSysErrorLogsPages');
    Route::get('error-logs/:id', 'system.SysErrorLogs/getSysErrorLogsInfo');

    //系统访问日志
    Route::get('access-logs/pages', 'system.SysAccessLogs/getSysAccesslogsPages');
    Route::get('access-logs/:id', 'system.SysAccessLogs/getSysAccesslogsInfo');


    //物流相关修改
    // expresses/pages (2段) 必须在 expresses/:id (2段) 前面 否则 GET /expresses/pages 会被 expresses/:id 匹配
    Route::get('expresses/pages', 'system.SysExpress/getSysExpressPages');
    // expresses/list (2段) 必须在 expresses/:id (2段) 前面
    Route::get('expresses/list', 'system.SysExpress/getSysExpressList');
    Route::get('expresses/:id', 'system.SysExpress/getSysExpressInfo');
    Route::put('expresses/:id', 'system.SysExpress/updateSysExpress');
    Route::post('expresses', 'system.SysExpress/createSysExpress');
    Route::delete('expresses/:id', 'system.SysExpress/deleteSysExpress');


    //消息通知模板
    // notice-tpls/list (2段) 必须在 notice-tpls/:id (2段) 前面 否则 GET /notice-tpls/list 会被 notice-tpls/:id 匹配
    Route::get('notice-tpls/list', 'system.SysNoticeTpl/getSysNoticeTplList');
    // notice-tpls/test (2段) 必须在 notice-tpls/:id (2段) 前面
    Route::post('notice-tpls/test', 'system.SysNoticeTpl/testSysNoticeTpl');
    // notice-tpls/:id/toggle-field (3段) 必须在 notice-tpls/:id (2段) 前面
    Route::patch('notice-tpls/:id/toggle-field', 'system.SysNoticeTpl/toggleSysNoticeTplField');
    Route::get('notice-tpls/:id', 'system.SysNoticeTpl/getSysNoticeTplInfo');
    Route::put('notice-tpls/:id', 'system.SysNoticeTpl/updateSysNoticeTpl');

    //消息通知记录
    // notice-logs/pages (2段) 必须在 notice-logs/:id (2段) 前面 否则 GET /notice-logs/pages 会被 notice-logs/:id 匹配
    Route::get('notice-logs/pages', 'system.SysNoticeLog/getSysNoticeLogPages');
    Route::get('notice-logs/:id', 'system.SysNoticeLog/getSysNoticeLogInfo');

    //短信通知记录
    // notice-sms-logs/pages (2段) 必须在 notice-sms-logs/:id (2段) 前面 否则 GET /notice-sms-logs/pages 会被 notice-sms-logs/:id 匹配
    Route::get('notice-sms-logs/pages', 'system.SysNoticeSmsLog/getSysNoticeSmsLogPages');
    Route::get('notice-sms-logs/:id', 'system.SysNoticeSmsLog/getSysNoticeSmsLogInfo');

    //短信服务商配置相关
    // sms-provider/list (2段) 必须在 sms-provider/:provider (2段) 前面 否则 GET /sms-provider/list 会被 sms-provider/:provider 匹配
    Route::get('sms-provider/list', 'system.SysSmsProvider/getSmsProviderList');
    Route::get('sms-provider/:provider', 'system.SysSmsProvider/getSmsProviderInfo');
    Route::put('sms-provider/:provider', 'system.SysSmsProvider/updateSmsProviderConfig');
    Route::post('sms-provider/test', 'system.SysSmsProvider/testSmsProvider');

    //快递查询服务商配置相关
    // express-provider/list (2段) 必须在 express-provider/:provider (2段) 前面 否则 GET /express-provider/list 会被 express-provider/:provider 匹配
    Route::get('express-provider/list', 'system.SysExpressProvider/getExpressProviderList');
    // express-provider/test (2段) 必须在 express-provider/:provider (2段) 前面
    Route::post('express-provider/test', 'system.SysExpressProvider/testExpressProvider');
    Route::get('express-provider/:provider', 'system.SysExpressProvider/getExpressProviderInfo');
    Route::put('express-provider/:provider', 'system.SysExpressProvider/updateExpressProviderConfig');


    //文件存储配置相关
    // storage-provider/list (2段) 必须在 storage-provider/:provider (2段) 前面 否则 GET /storage-provider/list 会被 storage-provider/:provider 匹配
    Route::get('storage-provider/list', 'system.SysStorageProvider/getStorageProviderList');
    Route::get('storage-provider/:provider', 'system.SysStorageProvider/getStorageProviderInfo');
    Route::put('storage-provider/:provider', 'system.SysStorageProvider/updateStorageProviderConfig');

    //地图服务提供商配置相关
    // lbs-provider/list (2段) 必须在 lbs-provider/:provider (2段) 前面 否则 GET /lbs-provider/list 会被 lbs-provider/:provider 匹配
    Route::get('lbs-provider/list', 'system.SysLbsProvider/getLbsProviderList');
    Route::get('lbs-provider/:provider', 'system.SysLbsProvider/getLbsProviderInfo');
    Route::put('lbs-provider/:provider', 'system.SysLbsProvider/updateLbsProviderConfig');

    //协议相关
    // agreements/list (2段) 必须在 agreements/:id (2段) 前面 否则 GET /agreements/list 会被 agreements/:id 匹配
    Route::get('agreements/list', 'system.SysAgreement/getSysAgreementList');
    Route::get('agreements/:id', 'system.SysAgreement/getSysAgreementInfo');
    Route::put('agreements/:id', 'system.SysAgreement/updateSysAgreement');


    //系统文章分类
    // article-categories/tree (2段) 必须在 article-categories/:id (2段) 前面 否则 GET /article-categories/tree 会被 article-categories/:id 匹配
    Route::get('article-categories/tree', 'system.SysArticleCategory/getSysArticleCategoryTree');
    Route::get('article-categories/:id', 'system.SysArticleCategory/getSysArticleCategoryInfo');
    Route::put('article-categories/:id', 'system.SysArticleCategory/updateSysArticleCategory');
    Route::post('article-categories', 'system.SysArticleCategory/createSysArticleCategory');
    Route::delete('article-categories/:id', 'system.SysArticleCategory/deleteSysArticleCategory');


    //系统文章
    // articles/pages (2段) 必须在 articles/:id (2段) 前面 否则 GET /articles/pages 会被 articles/:id 匹配
    Route::get('articles/pages', 'system.SysArticle/getSysArticlePages');
    // articles/batch (2段) 必须在 articles/:id (2段) 前面
    Route::delete('articles/batch', 'system.SysArticle/deleteBatchSysArticle');
    Route::get('articles/:id', 'system.SysArticle/getSysArticleInfo');
    Route::put('articles/:id', 'system.SysArticle/updateSysArticle');
    Route::post('articles', 'system.SysArticle/createSysArticle');


    //系统清理
    Route::delete('clear/cache', 'system.SysClear/clearCache');
    Route::delete('clear/logs', 'system.SysClear/clearLogs');
    Route::delete('clear/access-logs', 'system.SysClear/clearSysAccessLogs');
    Route::delete('clear/error-logs', 'system.SysClear/clearSysErrorLog');
    Route::delete('clear/admin-logs', 'system.SysClear/clearAdminLogs');

    //打印机服务商相关
    // printer-provider/list (2段) 必须在 printer-provider/:provider (2段) 前面 否则 GET /printer-provider/list 会被 printer-provider/:provider 匹配
    Route::get('printer-provider/list', 'system.SysPrinterProvider/getPrinterProviderList');
    Route::get('printer-provider/:provider', 'system.SysPrinterProvider/getPrinterProviderInfo');
    Route::put('printer-provider/:provider', 'system.SysPrinterProvider/updatePrinterProviderConfig');

    //系统任务队列
    // task-queues/pages (2段) 必须在 task-queues/:id (2段) 前面 否则 GET /task-queues/pages 会被 task-queues/:id 匹配
    Route::get('task-queues/pages', 'system.SysTaskQueue/getSysTaskQueuePages');
    // task-queues/recover-orphaned (2段) 必须在 task-queues/:id (2段) 前面
    Route::post('task-queues/recover-orphaned', 'system.SysTaskQueue/recoverOrphanedTasks');
    // task-queues/retry-failed (2段) 必须在 task-queues/:id (2段) 前面
    Route::post('task-queues/retry-failed', 'system.SysTaskQueue/retryFailedTasks');
    Route::get('task-queues/:id', 'system.SysTaskQueue/getSysTaskQueueInfo');


})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
