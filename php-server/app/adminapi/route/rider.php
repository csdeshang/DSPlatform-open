<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 骑手/配送员
Route::group('rider', function () {


    // 骑手
    Route::get('rider/pages', 'rider.Rider/getRiderPages');
    Route::post('rider', 'rider.Rider/createRider');
    Route::get('rider/:id', 'rider.Rider/getRiderInfo');
    Route::put('rider/:id', 'rider.Rider/updateRider');

    //骑手余额
    Route::get('balance-log/pages', 'rider.RiderBalance/getRiderBalanceLogPages');
    Route::post('balance/modifyRiderBalance', 'rider.RiderBalance/modifyRiderBalance');
    Route::get('balance/getRiderBalanceInfo/:id', 'rider.RiderBalance/getRiderBalanceInfo');


    //骑手配送规则
    Route::get('fee-rule/pages', 'rider.RiderFeeRule/getRiderFeeRulePages');
    Route::post('fee-rule', 'rider.RiderFeeRule/createRiderFeeRule');
    Route::get('fee-rule/:id', 'rider.RiderFeeRule/getRiderFeeRuleInfo');
    Route::put('fee-rule/:id', 'rider.RiderFeeRule/updateRiderFeeRule');

    // 骑手评论管理
    Route::get('comment/pages', 'rider.RiderComment/getRiderCommentPages');
    Route::post('comment/toggle-field', 'rider.RiderComment/toggleRiderCommentField');

    // 骑手轨迹管理
    Route::get('track/pages', 'rider.RiderTrack/getRiderTrackPages');
    Route::get('track/:id', 'rider.RiderTrack/getRiderTrackInfo');
    Route::delete('track/:id', 'rider.RiderTrack/deleteRiderTrack');
    Route::post('track/clear', 'rider.RiderTrack/clearRiderTrack');


})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);