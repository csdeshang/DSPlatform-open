<?php

namespace app\adminapi\service\system;


use app\deshang\base\service\BaseAdminService;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;

use app\common\dao\system\SysArticleDao;


// 系统文章
class SysArticleService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }



    /**
     * 获取文章列表
     */
    public function getSysArticlePages(array $data)
    {
        $condition = [];

        if (isset($data['title']) && $data['title'] != '') {
            $condition[] = ['title', 'like', '%' . $data['title'] . '%'];
        }

        $result = (new SysArticleDao())->getSysArticlePages($condition);
        return $result;
    }


    /**
     * 获取文章详情
     */
    public function getSysArticleInfo(array $data)
    {

        $condition = [];
        $condition[] = ['id', '=', $data['id']];

        $result = (new SysArticleDao())->getSysArticleInfo($condition);
        return $result;
    }

    /**
     * 添加文章
     */
    public function createSysArticle(array $data)
    {
        //添加
        $result = (new SysArticleDao())->createSysArticle($data);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_TAG);
        
        return $result;
    }

    /**
     * 编辑文章
     */
    public function updateSysArticle(int $id, array $data)
    {
        //更新
        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new SysArticleDao())->updateSysArticle($condition, $data);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_TAG);
        
        return $result;
    }

    /**
     * 删除文章
     */
    public function deleteBatchSysArticle(array $ids)
    {
        $condition = [];
        $condition[] = ['id', 'in', $ids];
        $result = (new SysArticleDao())->deleteSysArticle($condition);
        
        // 清理缓存
        KvManager::cache()->clear(CacheKeyManager::SYS_ARTICLE_TAG);
        
        return $result;
    }
}
