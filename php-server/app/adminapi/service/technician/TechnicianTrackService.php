<?php

namespace app\adminapi\service\technician;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\technician\TechnicianTrackDao;
use app\deshang\exceptions\CommonException;

class TechnicianTrackService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取师傅轨迹分页列表
     */
    public function getTechnicianTrackPages($data)
    {
        $condition = [];
        
        // 师傅ID搜索
        if (isset($data['technician_id']) && $data['technician_id'] != '') {
            $condition[] = ['technician_id', '=', $data['technician_id']];
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

        $result = (new TechnicianTrackDao())->getTechnicianTrackPages($condition);
        return $result;
    }

    /**
     * 获取师傅轨迹详情
     */
    public function getTechnicianTrackInfo($id)
    {
        if (empty($id)) {
            throw new CommonException('轨迹记录ID不能为空');
        }
        
        $result = (new TechnicianTrackDao())->getTechnicianTrackInfoById($id);
        if (empty($result)) {
            throw new CommonException('轨迹记录不存在');
        }
        
        return $result;
    }

    /**
     * 删除师傅轨迹记录
     */
    public function deleteTechnicianTrack($id)
    {
        if (empty($id)) {
            throw new CommonException('轨迹记录ID不能为空');
        }
        
        // 检查记录是否存在
        $info = (new TechnicianTrackDao())->getTechnicianTrackInfoById($id);
        if (empty($info)) {
            throw new CommonException('轨迹记录不存在');
        }
        
        $condition = [['id', '=', $id]];
        $result = (new TechnicianTrackDao())->deleteTechnicianTrack($condition);
        return $result;
    }

    /**
     * 清空师傅轨迹记录
     */
    public function clearTechnicianTrack($data)
    {
        $technician_id = $data['technician_id'] ?? 0;
        $days = $data['days'] ?? 30;
        
        if (empty($technician_id)) {
            throw new CommonException('师傅ID不能为空');
        }
        
        $condition = [['technician_id', '=', $technician_id]];
        
        // 如果days为0，清空所有记录；否则按天数清空
        if ($days > 0) {
            $keepTime = time() - ($days * 24 * 3600);
            $condition[] = ['create_at', '<', $keepTime];
        }
        
        $result = (new TechnicianTrackDao())->deleteTechnicianTrack($condition);
        return $result;
    }
} 