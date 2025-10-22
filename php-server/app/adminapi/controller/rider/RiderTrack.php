<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\rider\RiderTrackService;

/**
 * @OA\Tag(name="admin-api/rider/RiderTrack", description="骑手轨迹管理接口")
 */
class RiderTrack extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/rider/track/pages",
     *     summary="获取骑手轨迹分页列表",
     *     tags={"admin-api/rider/RiderTrack"},
     *     @OA\Parameter(
     *         name="rider_id",
     *         in="query",
     *         required=false,
     *         description="骑手ID",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_time",
     *         in="query",
     *         required=false,
     *         description="开始时间",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="end_time",
     *         in="query",
     *         required=false,
     *         description="结束时间",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         required=false,
     *         description="地址关键词",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getRiderTrackPages()
    {
        $data = [
            'rider_id' => input('param.rider_id', ''),
            'start_time' => input('param.start_time', ''),
            'end_time' => input('param.end_time', ''),
            'address' => input('param.address', ''),
        ];

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderTrackValidate.pages');

        $result = (new RiderTrackService())->getRiderTrackPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/rider/track/{id}",
     *     summary="获取骑手轨迹详情",
     *     tags={"admin-api/rider/RiderTrack"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="轨迹记录ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getRiderTrackInfo($id)
    {
        $data = ['id' => $id];
        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderTrackValidate.info');

        $result = (new RiderTrackService())->getRiderTrackInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/rider/track/{id}",
     *     summary="删除骑手轨迹记录",
     *     tags={"admin-api/rider/RiderTrack"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="轨迹记录ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="删除成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="删除成功")
     *         )
     *     )
     * )
     */
    public function deleteRiderTrack($id)
    {
        $data = ['id' => $id];
        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderTrackValidate.delete');

        $result = (new RiderTrackService())->deleteRiderTrack($id);
        return ds_json_success('删除成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/rider/track/clear",
     *     summary="清空骑手轨迹记录",
     *     tags={"admin-api/rider/RiderTrack"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="清空条件",
     *         @OA\JsonContent(
     *             @OA\Property(property="rider_id", type="integer", example=1, description="骑手ID"),
     *             @OA\Property(property="days", type="integer", example=30, description="保留天数")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="清空成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="清空成功")
     *         )
     *     )
     * )
     */
    public function clearRiderTrack()
    {
        $data = [
            'rider_id' => input('param.rider_id'),
            'days' => input('param.days', 30),
        ];

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderTrackValidate.clear');

        $result = (new RiderTrackService())->clearRiderTrack($data);
        return ds_json_success('清空成功', $result);
    }
}
