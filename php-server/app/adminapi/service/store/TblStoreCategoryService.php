<?php

namespace app\adminapi\service\store;


use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\store\TblStoreCategoryDao;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;


// 店铺分类 多平台通用
class TblStoreCategoryService extends BaseAdminService
{

    /**
     * 获取店铺分类列表
     */
    public function getTblStoreCategoryList(array $data)
    {

        $condition = array(
            'platform' => $data['platform'],
        );
        $result = (new TblStoreCategoryDao())->getStoreCategoryList($condition);
        return $result;
    }


    /**
     * 获取商品分类详情
     */
    public function getTblStoreCategoryInfo(array $data)
    {

        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        $field = '*';
        $result = (new TblStoreCategoryDao())->getStoreCategoryInfo($condition, $field);
        return $result;
    }

    /**
     * 添加商品分类
     */
    public function createTblStoreCategory(array $data)
    {

        // 可放验证器
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']]
            ];
            $has_cate = (new TblStoreCategoryDao())->getStoreCategoryCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }
        //添加
        return (new TblStoreCategoryDao())->createStoreCategory($data);
    }

    /**
     * 编辑商品分类
     */
    public function updateTblStoreCategory(array $data)
    {

        // 可放验证器
        // 检测 pid
        $list = (new TblStoreCategoryDao())->getStoreCategoryList([]);
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['id'] . ' 设置为子分类，这将导致循环引用');
        }

        //更新
        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        $result = (new TblStoreCategoryDao())->updateStoreCategory($condition, $data);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::STORE_CATEGORY_TAG);

        return $result;
    }

    /**
     * 删除商品分类
     */
    public function deleteTblStoreCategory(int $id)
    {

        //判断是否有下级
        $has_child = (new TblStoreCategoryDao())->getStoreCategoryCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该菜单有下级菜单，无法删除');
        }

        $result = (new TblStoreCategoryDao())->deleteStoreCategory([['id', '=', $id]]);

        // 删除缓存
        KvManager::cache()->clear(CacheKeyManager::STORE_CATEGORY_TAG);

        return $result;
    }
}
