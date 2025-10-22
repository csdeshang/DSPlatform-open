<?php

use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

// 师傅管理
Route::group('technician', function () {
    // 师傅
    Route::get('technician/pages', 'technician.Technician/getTechnicianPages');
    Route::put('technician/:id/bind-store', 'technician.Technician/updateTechnicianBindStore');
    Route::put('technician/:id', 'technician.Technician/updateTechnician');
    Route::get('technician/:id', 'technician.Technician/getTechnicianInfo');
    
    
    // 师傅余额管理
    Route::get('balance-log/pages', 'technician.TechnicianBalance/getTechnicianBalanceLogPages');
    Route::post('balance/modifyTechnicianBalance', 'technician.TechnicianBalance/modifyTechnicianBalance');

    // 师傅评论管理
    Route::get('comment/pages', 'technician.TechnicianComment/getTechnicianCommentPages');
    Route::post('comment/toggle-field', 'technician.TechnicianComment/toggleTechnicianCommentField');

    // 师傅轨迹管理
    Route::get('track/pages', 'technician.TechnicianTrack/getTechnicianTrackPages');
    Route::get('track/:id', 'technician.TechnicianTrack/getTechnicianTrackInfo');
    Route::delete('track/:id', 'technician.TechnicianTrack/deleteTechnicianTrack');
    Route::post('track/clear', 'technician.TechnicianTrack/clearTechnicianTrack');

})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]); 