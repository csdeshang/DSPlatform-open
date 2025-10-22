<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;


Route::group('user', function () {



    
    // 用户推广关系 （需要放在 user/:id 之前）
    Route::get('user/relation/list', 'user.User/getUserRelationList');
    // 会员
    Route::get('user/pages', 'user.User/getUserPages');
    Route::post('user', 'user.User/createUser');
    Route::get('user/:id', 'user.User/getUserInfo');
    Route::put('user/:id', 'user.User/updateUser');


    //余额
    Route::get('balance-log/pages', 'user.UserBalance/getUserBalanceLogPages');
    Route::post('balance/modifyUserBalance', 'user.UserBalance/modifyUserBalance');
    Route::get('balance/getUserBalanceInfo/:id', 'user.UserBalance/getUserBalanceInfo');

    // 用户充值日志
    Route::get('recharge-log/pages', 'user.UserRecharge/getUserRechargeLogPages');

    // 用户提现日志
    Route::get('withdrawal-log/pages', 'user.UserWithdrawal/getUserWithdrawalLogPages');
    Route::get('withdrawal-log/info/:id', 'user.UserWithdrawal/getUserWithdrawalLogInfo');
    // 管理员操作提现
    Route::post('withdrawal-log/operation/:id', 'user.UserWithdrawal/operationUserWithdrawal');

    // 用户成长值
    Route::post('growth/modifyUserGrowth', 'user.UserGrowth/modifyUserGrowth');
    Route::get('growth-log/pages', 'user.UserGrowth/getUserGrowthLogPages');
    Route::get('growth-log/:id', 'user.UserGrowth/getUserGrowthLogInfo');

    // 成长值等级
    Route::get('growth-level/list', 'user.UserGrowthLevel/getUserGrowthLevelList');
    Route::get('growth-level/:id', 'user.UserGrowthLevel/getUserGrowthLevelInfo');
    Route::put('growth-level/:id', 'user.UserGrowthLevel/updateUserGrowthLevel');
    Route::post('growth-level', 'user.UserGrowthLevel/createUserGrowthLevel');
    Route::delete('growth-level/:id', 'user.UserGrowthLevel/deleteUserGrowthLevel');

    // 积分日志
    Route::post('points/modifyUserPoints', 'user.UserPoints/modifyUserPoints');
    Route::get('points-log/pages', 'user.UserPoints/getUserPointsLogPages');
    Route::get('points-log/:id', 'user.UserPoints/getUserPointsLogInfo');

    // 用户行为日志
    Route::get('behavior-log/pages', 'user.UserBehaviorLog/getUserBehaviorLogPages');
    Route::get('behavior-log/:id', 'user.UserBehaviorLog/getUserBehaviorLogInfo');

    // 用户绑定第三方账号列表
    Route::get('identity/list', 'user.UserIdentity/getUserIdentityList');
})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);
