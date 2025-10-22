<?php

namespace app\adminapi\controller\stat;
use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\stat\StatDistributorService;



class StatDistributor extends BaseAdminController
{
    // 获取分销商概览数据
    public function getStatDistributorOverview()
    {
        // 获取是否强制刷新参数，并转换为布尔值
        $forceRefresh = strtolower(input('param.forceRefresh', 'false')) === 'true';

        // 构建缓存键
        $key = 'admin_stat_distributor_overview';
        
        // 如果不强制刷新且缓存存在，则直接返回缓存数据
        if (!$forceRefresh && $cache = cache($key)) {
            return ds_json_success('获取缓存数据成功', $cache);
        }
        
        // 获取新数据
        $result = (new StatDistributorService())->getStatDistributorOverview();
        
        // 设置缓存，有效期为 2 小时
        cache($key, $result, 3600 * 2);
        
        // 根据是否使用缓存返回不同的消息
        $message = $forceRefresh ? '强制刷新数据成功' : '获取最新数据成功';
        return ds_json_success($message, $result);
    }
}