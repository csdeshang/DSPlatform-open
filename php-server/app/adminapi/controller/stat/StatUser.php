<?php

namespace app\adminapi\controller\stat;
use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\stat\StatUserService;

/**
 * @OA\Tag(name="admin-api/stat/StatUser", description="用户统计接口")
 */
class StatUser extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/stat/users/overview",
     *     summary="获取用户概览统计数据",
     *     tags={"admin-api/stat/StatUser"},
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
    public function getStatUserOverview()
    {
        // 获取是否强制刷新参数，并转换为布尔值
        $forceRefresh = strtolower(input('param.forceRefresh', 'false')) === 'true';

        // 构建缓存键
        $key = 'admin_stat_user_overview';
        
        // 如果不强制刷新且缓存存在，则直接返回缓存数据
        if (!$forceRefresh && $cache = cache($key)) {
            return ds_json_success('获取缓存数据成功', $cache);
        }
        
        // 获取新数据
        $result = (new StatUserService())->getStatUserOverview();
        
        // 设置缓存，有效期为 12 小时
        cache($key, $result, 3600 * 12);
        
        // 根据是否使用缓存返回不同的消息
        $message = $forceRefresh ? '强制刷新数据成功' : '获取最新数据成功';
        return ds_json_success($message, $result);
    }
}