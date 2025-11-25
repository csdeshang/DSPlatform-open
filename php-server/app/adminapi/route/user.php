<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('user', function () {



    // 用户推广关系 （需要放在 users/:id 之前）
    Route::get('users/relation/list', 'user.User/getUserRelationList');

    // 会员
    //  users/:id/balance (3段) 必须在 users/:id (2段) 前面 否则 PUT /users/123/balance 会被 users/:id 匹配
    // 修改会员余额
    Route::put('users/:id/balance', 'user.UserBalance/modifyUserBalance');
    // users/:id/points (3段) 必须在 users/:id (2段) 前面
    Route::put('users/:id/points', 'user.UserPoints/modifyUserPoints');
    // users/:id/growth (3段) 必须在 users/:id (2段) 前面
    Route::put('users/:id/growth', 'user.UserGrowth/modifyUserGrowth');
    // users/pages (2段) 必须在 users/:id (2段) 前面 否则 GET /users/pages 会被 users/:id 匹配
    Route::get('users/pages', 'user.User/getUserPages');
    Route::get('users/:id', 'user.User/getUserInfo');
    Route::put('users/:id', 'user.User/updateUser');
    Route::post('users', 'user.User/createUser');


    // 余额日志
    Route::get('balance-logs/pages', 'user.UserBalance/getUserBalanceLogPages');

    // 用户充值日志
    Route::get('recharge-logs/pages', 'user.UserRecharge/getUserRechargeLogPages');

    // 用户提现日志
    Route::get('withdrawal-logs/pages', 'user.UserWithdrawal/getUserWithdrawalLogPages');
    // withdrawal-logs/:id/operation (3段) 必须在 withdrawal-logs/:id (2段) 前面
    // 管理员操作提现
    Route::post('withdrawal-logs/:id/operation', 'user.UserWithdrawal/operationUserWithdrawal');
    Route::get('withdrawal-logs/:id', 'user.UserWithdrawal/getUserWithdrawalLogInfo');

    // 用户成长值日志
    Route::get('growth-logs/pages', 'user.UserGrowth/getUserGrowthLogPages');
    Route::get('growth-logs/:id', 'user.UserGrowth/getUserGrowthLogInfo');

    // 成长值等级
    // growth-levels/list (2段) 必须在 growth-levels/:id (2段) 前面 否则 GET /growth-levels/list 会被 growth-levels/:id 匹配
    Route::get('growth-levels/list', 'user.UserGrowthLevel/getUserGrowthLevelList');
    Route::get('growth-levels/:id', 'user.UserGrowthLevel/getUserGrowthLevelInfo');
    Route::put('growth-levels/:id', 'user.UserGrowthLevel/updateUserGrowthLevel');
    Route::post('growth-levels', 'user.UserGrowthLevel/createUserGrowthLevel');
    Route::delete('growth-levels/:id', 'user.UserGrowthLevel/deleteUserGrowthLevel');

    // 积分日志
    Route::get('points-logs/pages', 'user.UserPoints/getUserPointsLogPages');
    Route::get('points-logs/:id', 'user.UserPoints/getUserPointsLogInfo');

    // 用户行为日志
    Route::get('behavior-logs/pages', 'user.UserBehaviorLog/getUserBehaviorLogPages');
    Route::get('behavior-logs/:id', 'user.UserBehaviorLog/getUserBehaviorLogInfo');
    Route::delete('behavior-logs/:id', 'user.UserBehaviorLog/deleteUserBehaviorLog');

    // 用户绑定第三方账号列表
    Route::get('user-identities/list', 'user.UserIdentity/getUserIdentityList');

    
})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
