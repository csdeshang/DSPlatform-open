<?php

namespace app\adminapi\service\system;


use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

use app\common\dao\system\SysArticleCategoryDao;


// 系统文章分类
class SysArticleCategoryService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 获取文章分类列表
     */
    public function getSysArticleCategoryList(array $data)
    {

        $condition = [];

        $result = (new SysArticleCategoryDao())->getSysArticleCategoryList($condition);
        return $result;
    }


    /**
     * 获取文章分类详情
     */
    public function getSysArticleCategoryInfo(array $data)
    {

        $condition = [];
        $condition[] = ['id', '=', $data['id']];

        $field = '*';
        $result = (new SysArticleCategoryDao())->getSysArticleCategoryInfo($condition, $field);
        return $result;
    }

    /**
     * 添加文章分类
     */
    public function createSysArticleCategory(array $data)
    {

        // 可放验证器
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']],
            ];
            $has_cate = (new SysArticleCategoryDao())->getSysArticleCategoryCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }
        //添加
        $result = (new SysArticleCategoryDao())->createSysArticleCategory($data);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_CATEGORY_TAG);
        
        return $result;
    }

    /**
     * 编辑文章分类
     */
    public function updateSysArticleCategory(int $id,array $data):int
    {

        // 可放验证器
        // 检测 pid
        $list = (new SysArticleCategoryDao())->getSysArticleCategoryList([]);
        $result = checkParentId($data['pid'], $id, $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $id . ' 不能将父分类ID ' . $id . ' 设置为子分类，这将导致循环引用');
        }

        //更新
        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new SysArticleCategoryDao())->updateSysArticleCategory($condition, $data);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_CATEGORY_TAG);
        
        return $result;
    }

    /**
     * 删除文章分类
     */
    public function deleteSysArticleCategory(int $id)
    {

        //判断是否有下级
        $has_child = (new SysArticleCategoryDao())->getSysArticleCategoryCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该分类有下级分类，无法删除');
        }

        $result = (new SysArticleCategoryDao())->deleteSysArticleCategory([['id', '=', $id]]);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_CATEGORY_TAG);
        
        return $result;
    }
}
