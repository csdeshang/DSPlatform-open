<?php

namespace app\common\dao\technician;

use app\common\dao\BaseDao;
use app\common\model\technician\TechnicianScheduleModel;

/**
 * 师傅排班数据访问对象
 * 
 * 负责师傅排班的数据库交互操作
 */
class TechnicianScheduleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TechnicianScheduleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TechnicianScheduleModel();
    }
    
    /**
     * 创建师傅排班
     * 
     * @param array $data 师傅排班数据
     * @return int 新创建的记录ID
     */
    public function createTechnicianSchedule(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }
    
    /**
     * 批量创建师傅排班
     * 
     * @param array $dataList 师傅排班数据列表
     * @return bool 是否创建成功
     */
    public function createTechnicianScheduleBatch(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }
    
    /**
     * 删除师傅排班
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteTechnicianSchedule(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新师傅排班
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateTechnicianSchedule(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }
    
    /**
     * 获取师傅排班列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按日期升序、时间段升序
     * @return array 师傅排班列表
     */
    public function getTechnicianScheduleList(array $condition, string $field = '*', string $order = 'date asc, time_period asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }
    
    /**
     * 获取师傅排班信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 师傅排班信息
     */
    public function getTechnicianScheduleInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取师傅特定日期的排班
     * 
     * @param int $technicianId 师傅ID
     * @param string $date 日期，格式：Y-m-d
     * @param string $field 查询字段，默认为所有字段
     * @return array 排班列表
     */
    public function getTechnicianScheduleByDate(int $technicianId, string $date, string $field = '*'): array
    {
        return $this->model
            ->where('technician_id', $technicianId)
            ->where('date', $date)
            ->field($field)
            ->order('time_period asc')
            ->select()
            ->toArray();
    }
    
    /**
     * 获取师傅日期范围内的排班
     * 
     * @param int $technicianId 师傅ID
     * @param string $startDate 开始日期，格式：Y-m-d
     * @param string $endDate 结束日期，格式：Y-m-d
     * @param string $field 查询字段，默认为所有字段
     * @return array 排班列表
     */
    public function getTechnicianScheduleByDateRange(int $technicianId, string $startDate, string $endDate, string $field = '*'): array
    {
        return $this->model
            ->where('technician_id', $technicianId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->field($field)
            ->order('date asc, time_period asc')
            ->select()
            ->toArray();
    }
    
    /**
     * 获取可预约的时间段列表
     * 
     * @param int $technicianId 师傅ID
     * @param string $date 日期，格式：Y-m-d
     * @param string $field 查询字段，默认为所有字段
     * @return array 可预约时间段列表
     */
    public function getAvailableScheduleByDate(int $technicianId, string $date, string $field = '*'): array
    {
        return $this->model
            ->where('technician_id', $technicianId)
            ->where('date', $date)
            ->where('status', TechnicianScheduleModel::STATUS_AVAILABLE)
            ->field($field)
            ->order('time_period asc')
            ->select()
            ->toArray();
    }
    
    /**
     * 预约时间段
     * 
     * @param int $scheduleId 排班ID
     * @param int $orderId 订单ID
     * @return bool 是否预约成功
     */
    public function bookSchedule(int $scheduleId, int $orderId): bool
    {
        return $this->model
            ->where('id', $scheduleId)
            ->where('status', TechnicianScheduleModel::STATUS_AVAILABLE)
            ->update([
                'status' => TechnicianScheduleModel::STATUS_BOOKED,
                'order_id' => $orderId
            ]);
    }
    
    /**
     * 取消预约
     * 
     * @param int $scheduleId 排班ID
     * @return bool 是否取消成功
     */
    public function cancelBooking(int $scheduleId): bool
    {
        return $this->model
            ->where('id', $scheduleId)
            ->where('status', TechnicianScheduleModel::STATUS_BOOKED)
            ->update([
                'status' => TechnicianScheduleModel::STATUS_AVAILABLE,
                'order_id' => null
            ]);
    }
} 