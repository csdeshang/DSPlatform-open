<?php

use think\facade\Route;

use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 骑手/配送员
Route::group('rider', function () {


    // 骑手
    // riders/:id/balance (3段) 必须在 riders/:id (2段) 前面 否则 PUT /riders/123/balance 会被 riders/:id 匹配
    Route::put('riders/:id/balance', 'rider.RiderBalance/modifyRiderBalance');
    // riders/pages (2段) 必须在 riders/:id (2段) 前面 否则 GET /riders/pages 会被 riders/:id 匹配
    Route::get('riders/pages', 'rider.Rider/getRiderPages');
    Route::get('riders/:id', 'rider.Rider/getRiderInfo');
    Route::put('riders/:id', 'rider.Rider/updateRider');
    Route::post('riders', 'rider.Rider/createRider');

    //骑手余额
    // balance-logs/pages (2段) 必须在 balance-logs/:id (2段) 前面（如果将来添加详情路由）
    Route::get('balance-logs/pages', 'rider.RiderBalance/getRiderBalanceLogPages');


    //骑手配送规则
    // fee-rules/pages (2段) 必须在 fee-rules/:id (2段) 前面 否则 GET /fee-rules/pages 会被 fee-rules/:id 匹配
    Route::get('fee-rules/pages', 'rider.RiderFeeRule/getRiderFeeRulePages');
    Route::post('fee-rules', 'rider.RiderFeeRule/createRiderFeeRule');
    Route::get('fee-rules/:id', 'rider.RiderFeeRule/getRiderFeeRuleInfo');
    Route::put('fee-rules/:id', 'rider.RiderFeeRule/updateRiderFeeRule');
    Route::delete('fee-rules/:id', 'rider.RiderFeeRule/deleteRiderFeeRule');

    // 骑手评论管理
    // comments/:id/toggle-field (3段) 必须在 comments/:id (2段) 前面 否则 PATCH /comments/123/toggle-field 会被 comments/:id 匹配
    Route::patch('comments/:id/toggle-field', 'rider.RiderComment/toggleRiderCommentField');
    // comments/pages (2段) 必须在 comments/:id (2段) 前面 否则 GET /comments/pages 会被 comments/:id 匹配
    Route::get('comments/pages', 'rider.RiderComment/getRiderCommentPages');

    // 骑手轨迹管理
    // tracks/:rider_id/all (3段) 必须在 tracks/:id (2段) 前面 否则 DELETE /tracks/123/all 会被 tracks/:id 匹配
    Route::delete('tracks/:rider_id/all', 'rider.RiderTrack/clearRiderTrack');
    // tracks/pages (2段) 必须在 tracks/:id (2段) 前面 否则 GET /tracks/pages 会被 tracks/:id 匹配
    Route::get('tracks/pages', 'rider.RiderTrack/getRiderTrackPages');
    Route::get('tracks/:id', 'rider.RiderTrack/getRiderTrackInfo');
    Route::delete('tracks/:id', 'rider.RiderTrack/deleteRiderTrack');


})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);