<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-goods', function () {



    // 多平台通用 商品列表
    // goods/:id/sys-status (3段) 必须在 goods/:id (2段) 前面 否则 PATCH /goods/123/sys-status 会被 goods/:id 匹配
    Route::patch('goods/:id/sys-status', 'tblGoods.TblGoods/updateTblGoodsSysStatus');
    // goods/:id/sys-recommend (3段) 必须在 goods/:id (2段) 前面 否则 PATCH /goods/123/sys-recommend 会被 goods/:id 匹配
    Route::patch('goods/:id/sys-recommend', 'tblGoods.TblGoods/updateSysRecommend');
    // goods/pages (2段) 必须在 goods/:id (2段) 前面 否则 GET /goods/pages 会被 goods/:id 匹配
    Route::get('goods/pages', 'tblGoods.TblGoods/getTblGoodsPages');
    // goods/list (2段) 必须在 goods/:id (2段) 前面 否则 GET /goods/list 会被 goods/:id 匹配
    Route::get('goods/list', 'tblGoods.TblGoods/getTblGoodsList');
    // 获取商品详情
    Route::get('goods/:id', 'tblGoods.TblGoods/getTblGoodsInfo');
    // 更新商品 只能更新 TblGoods 表数据
    Route::put('goods/:id', 'tblGoods.TblGoods/updateTblGoods');

    
    // 创建商品 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    // Route::post('goods', 'tblGoods.TblGoods/createTblGoods');


    // 删除商品 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    // Route::delete('goods/:id', 'tblGoods.TblGoods/deleteTblGoods');

    // 多平台通用 商品评论
    // comments/:id/toggle-field (3段) 必须在 comments/:id (2段) 前面 否则 PATCH /comments/123/toggle-field 会被 comments/:id 匹配
    Route::patch('comments/:id/toggle-field', 'tblGoods.TblGoodsComment/toggleTblGoodsCommentField');
    // comments/pages (2段) 必须在 comments/:id (2段) 前面 否则 GET /comments/pages 会被 comments/:id 匹配
    Route::get('comments/pages', 'tblGoods.TblGoodsComment/getTblGoodsCommentPages');



    // 多平台通用 公共商品分类信息
    // categories/tree (2段) 必须在 categories/:id (2段) 前面 否则 GET /categories/tree 会被 categories/:id 匹配
    Route::get('categories/tree', 'tblGoods.TblGoodsCategory/getTblGoodsCategoryTree');
    Route::get('categories/:id', 'tblGoods.TblGoodsCategory/getTblGoodsCategoryInfo');
    Route::post('categories', 'tblGoods.TblGoodsCategory/createTblGoodsCategory');
    Route::put('categories/:id', 'tblGoods.TblGoodsCategory/updateTblGoodsCategory');
    Route::delete('categories/:id', 'tblGoods.TblGoodsCategory/deleteTblGoodsCategory');


    // 多平台通用 品牌
    // brands/tree (2段) 必须在 brands/:id (2段) 前面 否则 GET /brands/tree 会被 brands/:id 匹配
    Route::get('brands/tree', 'tblGoods.TblGoodsBrand/getTblGoodsBrandTree');
    Route::get('brands/:id', 'tblGoods.TblGoodsBrand/getTblGoodsBrandInfo');
    Route::post('brands', 'tblGoods.TblGoodsBrand/createTblGoodsBrand');
    Route::put('brands/:id', 'tblGoods.TblGoodsBrand/updateTblGoodsBrand');
    Route::delete('brands/:id', 'tblGoods.TblGoodsBrand/deleteTblGoodsBrand');





})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);