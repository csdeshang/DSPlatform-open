<?php

namespace app\adminapi\service\goods;


use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\goods\TblGoodsCategoryDao;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;


// 商品分类 多平台通用
class TblGoodsCategoryService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 获取商品分类列表
     */
    public function getTblGoodsCategoryList(array $data)
    {

        $condition = array(
            'platform' => $data['platform'],
        );
        $result = (new TblGoodsCategoryDao())->getGoodsCategoryList($condition);
        return $result;
    }


    /**
     * 获取商品分类详情
     */
    public function getTblGoodsCategoryInfo(array $data)
    {

        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        $field = '*';
        $result = (new TblGoodsCategoryDao())->getGoodsCategoryInfo($condition, $field);
        return $result;
    }

    /**
     * 添加商品分类
     */
    public function createTblGoodsCategory(array $data)
    {

        // 可放验证器
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']]
            ];
            $has_cate = (new TblGoodsCategoryDao())->getGoodsCategoryCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }
        //添加
        return (new TblGoodsCategoryDao())->createGoodsCategory($data);
    }

    /**
     * 编辑商品分类
     */
    public function updateTblGoodsCategory(array $data)
    {

        // 可放验证器
        // 检测 pid
        $list = (new TblGoodsCategoryDao())->getGoodsCategoryList([]);
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['id'] . ' 设置为子分类，这将导致循环引用');
        }

        //更新
        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        $result = (new TblGoodsCategoryDao())->updateGoodsCategory($condition, $data);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::GOODS_CATEGORY_TAG);

        return $result;
    }

    /**
     * 删除商品分类
     */
    public function deleteTblGoodsCategory(int $id)
    {

        //判断是否有下级
        $has_child = (new TblGoodsCategoryDao())->getGoodsCategoryCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该菜单有下级菜单，无法删除');
        }

        $result = (new TblGoodsCategoryDao())->deleteGoodsCategory([['id', '=', $id]]);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::GOODS_CATEGORY_TAG);

        return $result;
    }
}
