<?php

namespace app\adminapi\controller\stat;
use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\stat\StatStoreService;

/**
 * @OA\Tag(name="admin-api/stat/StatStore", description="店铺统计接口")
 */
class StatStore extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/stat/stores/overview",
     *     summary="获取店铺概览统计数据",
     *     tags={"admin-api/stat/StatStore"},
     *     @OA\Parameter(
     *         name="platform",
     *         in="query",
     *         required=false,
     *         description="平台类型",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="forceRefresh",
     *         in="query",
     *         required=false,
     *         description="是否强制刷新缓存",
     *         @OA\Schema(type="string", example="false")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="获取数据成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="获取最新数据成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getStatStoreOverview()
    {
        // 获取是否强制刷新参数，并转换为布尔值
        $forceRefresh = strtolower(input('param.forceRefresh', 'false')) === 'true';

        $data = array(
            'platform' => input('param.platform', ''),
        );

        // 构建缓存键，确保平台参数有值
        $key = 'admin_stat_store_overview_' . md5(serialize($data));
        
        // 如果不强制刷新且缓存存在，则直接返回缓存数据
        if (!$forceRefresh && $cache = cache($key)) {
            return ds_json_success('获取缓存数据成功', $cache);
        }
        
        // 获取新数据
        $result = (new StatStoreService())->getStatStoreOverview($data);
        
        // 设置缓存，有效期为 12 小时
        cache($key, $result, 3600 * 12);
        
        // 根据是否使用缓存返回不同的消息
        $message = $forceRefresh ? '强制刷新数据成功' : '获取最新数据成功';
        return ds_json_success($message, $result);
    }
}