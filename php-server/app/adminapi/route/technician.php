<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 师傅管理
Route::group('technician', function () {
    // 师傅
    // technicians/:id/bind-store (3段) 必须在 technicians/:id (2段) 前面 否则 PUT /technicians/123/bind-store 会被 technicians/:id 匹配
    Route::put('technicians/:id/bind-store', 'technician.Technician/updateTechnicianBindStore');
    // technicians/:id/balance (3段) 必须在 technicians/:id (2段) 前面 否则 PUT /technicians/123/balance 会被 technicians/:id 匹配
    Route::put('technicians/:id/balance', 'technician.TechnicianBalance/modifyTechnicianBalance');
    // technicians/pages (2段) 必须在 technicians/:id (2段) 前面 否则 GET /technicians/pages 会被 technicians/:id 匹配
    Route::get('technicians/pages', 'technician.Technician/getTechnicianPages');
    Route::get('technicians/:id', 'technician.Technician/getTechnicianInfo');
    Route::put('technicians/:id', 'technician.Technician/updateTechnician');
    
    
    // 师傅余额管理
    // balance-logs/pages (2段) 必须在 balance-logs/:id (2段) 前面（如果有的话）
    Route::get('balance-logs/pages', 'technician.TechnicianBalance/getTechnicianBalanceLogPages');

    // 师傅评论管理
    // comments/pages (2段) 必须在 comments/:id (2段) 前面 否则 GET /comments/pages 会被 comments/:id 匹配
    Route::get('comments/pages', 'technician.TechnicianComment/getTechnicianCommentPages');
    // comments/:id/toggle-field (3段) 必须在 comments/:id (2段) 前面
    Route::patch('comments/:id/toggle-field', 'technician.TechnicianComment/toggleTechnicianCommentField');

    // 师傅轨迹管理
    // tracks/pages (2段) 必须在 tracks/:id (2段) 前面 否则 GET /tracks/pages 会被 tracks/:id 匹配
    Route::get('tracks/pages', 'technician.TechnicianTrack/getTechnicianTrackPages');
    // tracks/:technician_id/all (3段) 必须在 tracks/:id (2段) 前面 否则 DELETE /tracks/123/all 会被 tracks/:id 匹配
    Route::delete('tracks/:technician_id/all', 'technician.TechnicianTrack/clearTechnicianTrack');
    Route::get('tracks/:id', 'technician.TechnicianTrack/getTechnicianTrackInfo');
    Route::delete('tracks/:id', 'technician.TechnicianTrack/deleteTechnicianTrack');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]); 