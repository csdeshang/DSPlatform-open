<?php

namespace app\common\dao\merchant;

use app\common\dao\BaseDao;
use app\common\model\merchant\MerchantModel;
use app\common\dao\user\UserDao;
use app\deshang\exceptions\CommonException;

/**
 * 商户数据访问对象
 * 
 * 负责商户的数据库交互操作
 */
class MerchantDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化MerchantModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new MerchantModel();
    }

    /**
     * 创建商户
     * 
     * @param array $data 商户数据
     * @return int 新创建的商户ID
     */
    public function createMerchant(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除商户
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteMerchant(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新商户
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateMerchant(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取商户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 商户列表
     */
    public function getMerchantList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取商户分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getMerchantPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['apply_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取带关联信息的商户列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelMerchantPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->append(['apply_status_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }


    /**
     * 获取单条商户信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 商户信息
     */
    public function getMerchantInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }


    /**
     * 获取带关联信息的单条商户信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 商户信息
     */
    public function getWithRelMerchantInfo(array $condition, string $field = '*'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'user' => function ($query) {
                        // 设置字段 一定需要关联字段  否则返回为 NULL
                        $query->field('id,username,nickname,avatar');
                    },
                ]
            )
            ->field($field)
            ->findOrEmpty()
            ->toArray();
        return $result;
    }



    /**
     * 根据ID获取商户信息
     * 
     * @param int $id 商户ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 商户信息
     */
    public function getMerchantInfoById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }

    /**
     * 获取商户数量
     * 
     * @param array $condition 查询条件
     * @return int 商户数量
     */
    public function getMerchantCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取商户列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getMerchantColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    /**
     * 检测商户名称是否已存在
     * 
     * @param array $condition 查询条件
     * @return bool 是否存在重复
     */
    public function checkMerchantNameExists(array $condition): bool
    {
        $merchant = $this->model->where($condition)->findOrEmpty();
        return !$merchant->isEmpty();
    }
}
