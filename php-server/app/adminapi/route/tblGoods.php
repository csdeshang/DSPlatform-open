<?php


use think\facade\Route;
use app\adminapi\middleware\AdminAuthorizeToken;
use app\adminapi\middleware\AdminAuthorizeRole;
use app\adminapi\middleware\AdminAuthorizeLog;

Route::group('tbl-goods', function () {



    // 多平台通用 商品列表
    Route::get('pages', 'tblGoods.tblGoods/getTblGoodsPages');
    Route::get('list', 'tblGoods.tblGoods/getTblGoodsList');
    Route::get('info/:id', 'tblGoods.tblGoods/getTblGoodsInfo');
    // 创建商品 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    // Route::post('create', 'tblGoods.tblGoods/createTblGoods');
    // 更新商品 只能更新 TblGoods 表数据
    Route::put('update/:id', 'tblGoods.tblGoods/updateTblGoods');
    // 删除商品 涉及 其他关联数据 需要单独使用 对应的 平台方法进行处理
    // Route::delete('delete/:id', 'tblGoods.tblGoods/deleteTblGoods');
    // 更新商品系统状态
    Route::put('update-sys-status/:id', 'tblGoods.tblGoods/updateTblGoodsSysStatus');
    // 更新商品系统推荐状态
    Route::put('update-sys-recommend/:id', 'tblGoods.tblGoods/updateSysRecommend');

    // 多平台通用 商品评论
    Route::get('comment/pages', 'tblGoods.tblGoodsComment/getTblGoodsCommentPages');
    Route::post('comment/toggle-field', 'tblGoods.tblGoodsComment/toggleTblGoodsCommentField');



    // 多平台通用 公共商品分类信息
    Route::get('category/tree', 'tblGoods.tblGoodsCategory/getTblGoodsCategoryTree');
    Route::get('category/:id', 'tblGoods.tblGoodsCategory/getTblGoodsCategoryInfo');
    Route::post('category', 'tblGoods.tblGoodsCategory/createTblGoodsCategory');
    Route::put('category/:id', 'tblGoods.tblGoodsCategory/updateTblGoodsCategory');
    Route::delete('category/:id', 'tblGoods.tblGoodsCategory/deleteTblGoodsCategory');


    // 多平台通用 品牌
    Route::get('brand/tree', 'tblGoods.tblGoodsBrand/getTblGoodsBrandTree');
    Route::get('brand/:id', 'tblGoods.tblGoodsBrand/getTblGoodsBrandInfo');
    Route::post('brand', 'tblGoods.tblGoodsBrand/createTblGoodsBrand');
    Route::put('brand/:id', 'tblGoods.tblGoodsBrand/updateTblGoodsBrand');
    Route::delete('brand/:id', 'tblGoods.tblGoodsBrand/deleteTblGoodsBrand');





})->middleware([
    AdminAuthorizeToken::class,
    AdminAuthorizeRole::class,
    AdminAuthorizeLog::class
]);