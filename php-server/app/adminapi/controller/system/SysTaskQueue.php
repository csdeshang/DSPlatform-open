<?php

namespace app\adminapi\controller\system;

use app\adminapi\service\system\SysTaskQueueService;
use app\deshang\base\controller\BaseAdminController;

/**
 * @OA\Tag(name="admin-api/system/SysTaskQueue", description="系统任务队列（消息队列）相关接口")
 */
class SysTaskQueue extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/task-queues/pages",
     *     tags={"admin-api/system/SysTaskQueue"},
     *     summary="获取任务队列分页列表",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="queue_type",
     *         in="query",
     *         required=false,
     *         description="任务类型",
     *         @OA\Schema(type="string", example="inventory_update")
     *     ),
     *     @OA\Parameter(
     *         name="queue_group",
     *         in="query",
     *         required=false,
     *         description="队列分组",
     *         @OA\Schema(type="string", example="default")
     *     ),
     *     @OA\Parameter(
     *         name="biz_key",
     *         in="query",
     *         required=false,
     *         description="业务幂等键（唯一）",
     *         @OA\Schema(type="string", example="order:123")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="任务状态：0待处理 1完成 2失败 3处理中",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         required=false,
     *         description="优先级过滤（越大越高）",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *     @OA\Parameter(
     *         name="retry_count",
     *         in="query",
     *         required=false,
     *         description="重试次数过滤（≥）",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *     @OA\Parameter(
     *         name="consume_ms_min",
     *         in="query",
     *         required=false,
     *         description="最小耗时（毫秒）",
     *         @OA\Schema(type="integer", example=0)
     *     ),
     *     @OA\Parameter(
     *         name="consume_ms_max",
     *         in="query",
     *         required=false,
     *         description="最大耗时（毫秒）",
     *         @OA\Schema(type="integer", example=1000)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取任务队列分页列表",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getSysTaskQueuePages()
    {
        $data = array(
            'queue_type' => input('param.queue_type'),
            'queue_group' => input('param.queue_group'),
            'biz_key' => input('param.biz_key'),
            'status' => input('param.status'),
            'priority' => input('param.priority'),
            'retry_count' => input('param.retry_count'),
            'consume_ms_min' => input('param.consume_ms_min'),
            'consume_ms_max' => input('param.consume_ms_max'),
        );
        
        $this->validate($data, 'app\adminapi\controller\system\validate\SysTaskQueueValidate.pages');
        
        $result = (new SysTaskQueueService())->getSysTaskQueuePages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/task-queues/{id}",
     *     tags={"admin-api/system/SysTaskQueue"},
     *     summary="获取任务队列详情",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="成功获取任务详情",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="任务未找到"
     *     )
     * )
     */
    public function getSysTaskQueueInfo($id)
    {
        $data = array(
            'id' => $id,
        );
        
        $this->validate($data, 'app\adminapi\controller\system\validate\SysTaskQueueValidate.info');
        
        $result = (new SysTaskQueueService())->getSysTaskQueueInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/task-queues/recover-orphaned",
     *     tags={"admin-api/system/SysTaskQueue"},
     *     summary="恢复孤儿任务",
     *     description="恢复超过30分钟未更新的 PROCESSING 状态任务，将其状态重置为 PENDING，让任务重新处理",
     *     @OA\Response(
     *         response=200,
     *         description="成功恢复孤儿任务",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="count", type="integer", example=5, description="恢复的任务数量")
     *             )
     *         )
     *     )
     * )
     */
    public function recoverOrphanedTasks()
    {
        $result = (new SysTaskQueueService())->recoverOrphanedTasks();
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/task-queues/retry-failed",
     *     tags={"admin-api/system/SysTaskQueue"},
     *     summary="批量恢复失败任务",
     *     description="恢复所有失败状态的任务，将其状态重置为 PENDING，重置重试次数和错误信息，让任务重新处理",
     *     @OA\Response(
     *         response=200,
     *         description="成功恢复失败任务",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="count", type="integer", example=10, description="恢复的任务数量")
     *             )
     *         )
     *     )
     * )
     */
    public function retryFailedTasks()
    {
        $result = (new SysTaskQueueService())->retryFailedTasks();
        return ds_json_success('操作成功', $result);
    }
}
