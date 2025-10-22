<?php

namespace app\adminapi\service\rider;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\rider\RiderTrackDao;
use app\deshang\exceptions\CommonException;

class RiderTrackService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取骑手轨迹分页列表
     */
    public function getRiderTrackPages($data)
    {
        $condition = [];
        
        // 骑手ID搜索
        if (isset($data['rider_id']) && $data['rider_id'] != '') {
            $condition[] = ['rider_id', '=', $data['rider_id']];
        }
        
        // 时间范围搜索
        if (isset($data['start_time']) && $data['start_time'] != '') {
            $condition[] = ['create_at', '>=', strtotime($data['start_time'])];
        }
        
        if (isset($data['end_time']) && $data['end_time'] != '') {
            $condition[] = ['create_at', '<=', strtotime($data['end_time'])];
        }
        
        // 地址关键词搜索
        if (isset($data['address']) && $data['address'] != '') {
            $condition[] = ['address', 'like', '%' . $data['address'] . '%'];
        }

        $result = (new RiderTrackDao())->getRiderTrackPages($condition);
        return $result;
    }

    /**
     * 获取骑手轨迹详情
     */
    public function getRiderTrackInfo($id)
    {
        if (empty($id)) {
            throw new CommonException('轨迹记录ID不能为空');
        }
        
        $result = (new RiderTrackDao())->getRiderTrackInfoById($id);
        if (empty($result)) {
            throw new CommonException('轨迹记录不存在');
        }
        
        return $result;
    }

    /**
     * 删除骑手轨迹记录
     */
    public function deleteRiderTrack($id)
    {
        if (empty($id)) {
            throw new CommonException('轨迹记录ID不能为空');
        }
        
        // 检查记录是否存在
        $info = (new RiderTrackDao())->getRiderTrackInfoById($id);
        if (empty($info)) {
            throw new CommonException('轨迹记录不存在');
        }
        
        $condition = [['id', '=', $id]];
        $result = (new RiderTrackDao())->deleteRiderTrack($condition);
        return $result;
    }

    /**
     * 清空骑手轨迹记录
     */
    public function clearRiderTrack($data)
    {
        $rider_id = $data['rider_id'] ?? 0;
        $days = $data['days'] ?? 30;
        
        if (empty($rider_id)) {
            throw new CommonException('骑手ID不能为空');
        }
        
        $condition = [['rider_id', '=', $rider_id]];
        
        // 如果days为0，清空所有记录；否则按天数清空
        if ($days > 0) {
            $keepTime = time() - ($days * 24 * 3600);
            $condition[] = ['create_at', '<', $keepTime];
        }
        
        $result = (new RiderTrackDao())->deleteRiderTrack($condition);
        return $result;
    }
}
