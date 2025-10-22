<?php

namespace app\common\dao\rider;

use app\common\dao\BaseDao;
use app\common\model\rider\RiderTrackModel;

/**
 * 骑手轨迹数据访问对象
 * 
 * 负责骑手位置轨迹的数据库交互操作
 */
class RiderTrackDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化RiderTrackModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new RiderTrackModel();
    }
    
    /**
     * 创建骑手轨迹记录
     * 
     * @param array $data 轨迹数据
     * @return int 新创建的轨迹记录ID
     */
    public function createRiderTrack(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除骑手轨迹记录
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteRiderTrack(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新骑手轨迹记录
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateRiderTrack(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取骑手轨迹列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 轨迹列表
     */
    public function getRiderTrackList(array $condition, string $field = '*', string $order = 'create_at desc' , $limit = 10): array
    {
        return $this->model->where($condition)->field($field)->order($order)->limit($limit)->select()->toArray();
    }
    
    /**
     * 获取骑手轨迹分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getRiderTrackPages(array $condition, string $field = '*', string $order = 'create_at desc'): array
    {
        $result = $this->model->where($condition)
            ->field($field)
            ->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条轨迹记录
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 轨迹信息
     */
    public function getRiderTrackInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取轨迹记录
     * 
     * @param int $id 轨迹记录ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 轨迹信息
     */
    public function getRiderTrackInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取骑手轨迹数量
     * 
     * @param array $condition 查询条件
     * @return int 轨迹记录数量
     */
    public function getRiderTrackCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
}
