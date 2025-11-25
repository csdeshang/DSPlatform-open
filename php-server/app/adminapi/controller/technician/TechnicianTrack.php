<?php

namespace app\adminapi\controller\technician;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\technician\TechnicianTrackService;

/**
 * @OA\Tag(name="admin-api/technician/TechnicianTrack", description="师傅轨迹管理接口")
 */
class TechnicianTrack extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/technician/tracks/pages",
     *     summary="获取师傅轨迹分页列表",
     *     tags={"admin-api/technician/TechnicianTrack"},
     *     @OA\Parameter(
     *         name="technician_id",
     *         in="query",
     *         required=false,
     *         description="师傅ID",
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
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTechnicianTrackPages()
    {
        $data = [
            'technician_id' => input('param.technician_id', ''),
            'start_time' => input('param.start_time', ''),
            'end_time' => input('param.end_time', ''),
            'address' => input('param.address', ''),
        ];

        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianTrackValidate.pages');

        $result = (new TechnicianTrackService())->getTechnicianTrackPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/technician/tracks/{id}",
     *     summary="获取师傅轨迹详情",
     *     tags={"admin-api/technician/TechnicianTrack"},
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
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTechnicianTrackInfo($id)
    {
        $data = ['id' => $id];
        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianTrackValidate.info');

        $result = (new TechnicianTrackService())->getTechnicianTrackInfo($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/technician/tracks/{id}",
     *     summary="删除师傅轨迹记录",
     *     tags={"admin-api/technician/TechnicianTrack"},
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
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="删除成功")
     *         )
     *     )
     * )
     */
    public function deleteTechnicianTrack($id)
    {
        $data = ['id' => $id];
        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianTrackValidate.delete');

        $result = (new TechnicianTrackService())->deleteTechnicianTrack($id);
        return ds_json_success('删除成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/technician/tracks/{technician_id}/all",
     *     summary="清空师傅轨迹记录",
     *     tags={"admin-api/technician/TechnicianTrack"},
     *     @OA\Parameter(
     *         name="technician_id",
     *         in="path",
     *         description="师傅ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         description="保留天数，0表示清空所有",
     *         required=false,
     *         @OA\Schema(type="integer", example=30)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="清空成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="msg", type="string", example="清空成功")
     *         )
     *     )
     * )
     */
    public function clearTechnicianTrack($technician_id)
    {
        $data = [
            'technician_id' => (int)$technician_id,
            'days' => input('param.days', 30),
        ];

        $this->validate($data, 'app\adminapi\controller\technician\validate\TechnicianTrackValidate.clear');

        $result = (new TechnicianTrackService())->clearTechnicianTrack($data);
        return ds_json_success('清空成功', $result);
    }
}
