<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsFlashsaleModel;

/**
 * 商品秒杀数据访问对象
 * 
 * 负责商品秒杀的数据库交互操作
 */
class TblGoodsFlashsaleDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsFlashsaleModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsFlashsaleModel();
    }
    
    /**
     * 创建商品秒杀
     * 
     * @param array $data 秒杀数据
     * @return int 新创建的秒杀ID
     */
    public function createGoodsFlashsale(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品限时折扣
     * 
     * @param array $dataList 秒杀数据列表
     * @return bool 是否成功
     */
    public function createGoodsFlashsaleAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品秒杀
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsFlashsale(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品秒杀
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsFlashsale(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品秒杀列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按开始时间降序
     * @return array 秒杀列表
     */
    public function getGoodsFlashsaleList(array $condition, string $field = '*', string $order = 'start_time desc, id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品秒杀分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按开始时间降序
     * @return array 分页数据
     */
    public function getGoodsFlashsalePages(array $condition, string $field = '*', string $order = 'start_time desc, id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品秒杀信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 秒杀信息
     */
    public function getGoodsFlashsaleInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品秒杀信息
     * 
     * @param int $id 秒杀ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 秒杀信息
     */
    public function getGoodsFlashsaleById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品秒杀数量
     * 
     * @param array $condition 查询条件
     * @return int 秒杀数量
     */
    public function getGoodsFlashsaleCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品秒杀列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsFlashsaleColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
    

    

}
