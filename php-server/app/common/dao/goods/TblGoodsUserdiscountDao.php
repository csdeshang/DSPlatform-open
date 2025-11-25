<?php

namespace app\common\dao\goods;

use app\common\dao\BaseDao;
use app\common\model\goods\TblGoodsUserdiscountModel;

/**
 * 商品平台会员等级价格数据访问对象
 * 
 * 负责商品平台会员等级价格的数据库交互操作
 */
class TblGoodsUserdiscountDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化TblGoodsUserdiscountModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new TblGoodsUserdiscountModel();
    }
    
    /**
     * 创建商品平台会员等级价格
     * 
     * @param array $data 会员价格数据
     * @return int 新创建的会员价格ID
     */
    public function createGoodsUserdiscount(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 批量创建商品平台会员等级价格
     * 
     * @param array $dataList 会员价格数据列表
     * @return bool 是否成功
     */
    public function createGoodsUserdiscountAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除商品平台会员等级价格
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteGoodsUserdiscount(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新商品平台会员等级价格
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateGoodsUserdiscount(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取商品平台会员等级价格列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按会员等级ID升序
     * @return array 会员价格列表
     */
    public function getGoodsUserdiscountList(array $condition, string $field = '*', string $order = 'user_level_id asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商品平台会员等级价格分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按会员等级ID升序
     * @return array 分页数据
     */
    public function getGoodsUserdiscountPages(array $condition, string $field = '*', string $order = 'user_level_id asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条商品平台会员等级价格信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 会员价格信息
     */
    public function getGoodsUserdiscountInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取商品平台会员等级价格信息
     * 
     * @param int $id 会员价格ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 会员价格信息
     */
    public function getGoodsUserdiscountById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)->field($field)->lock($lock)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取商品平台会员等级价格数量
     * 
     * @param array $condition 查询条件
     * @return int 会员价格数量
     */
    public function getGoodsUserdiscountCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取商品平台会员等级价格列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getGoodsUserdiscountColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
} 