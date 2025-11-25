<?php

namespace app\common\dao\kms;

use app\common\dao\BaseDao;
use app\common\model\kms\KmsLessonModel;

/**
 * KMS课时数据访问对象
 * 
 * 负责KMS课时的数据库交互操作
 */
class KmsLessonDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化KmsLessonModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new KmsLessonModel();
    }

    /**
     * 创建课时
     * 
     * @param array $data 课时数据
     * @return int 新创建的课时ID
     */
    public function createKmsLesson(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除课时
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteKmsLesson(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }

    /**
     * 更新课时
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return int 受影响的行数
     */
    public function updateKmsLesson(array $condition, array $data): int
    {
        $result = $this->model::update($data, $condition);
        return $result->getNumRows();
    }

    /**
     * 获取课时列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 课时列表
     */
    public function getKmsLessonList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)
        ->append(['lesson_type_desc', 'is_free_desc'])
        ->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取课时分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getKmsLessonPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->append(['lesson_type_desc', 'is_free_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取带关联信息的课时列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getWithRelKmsLessonPages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name,goods_image');
                    },
                    'store' => function ($query) {
                        $query->field('id,store_name');
                    }
                ]
            )
            ->append(['lesson_type_desc', 'is_free_desc'])
            ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条课时信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 课时信息
     */
    public function getKmsLessonInfo(array $condition, string $field = '*', bool $lock = false): array
    {
        return $this->model->where($condition)
        ->append(['lesson_type_desc', 'is_free_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }

    /**
     * 获取带关联信息的单条课时信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 课时信息
     */
    public function getWithRelKmsLessonInfo(array $condition, string $field = '*'): array
    {
        $result = $this->model->where($condition)
            ->with(
                [
                    'goods' => function ($query) {
                        $query->field('id,goods_name,goods_image');
                    },
                    'store' => function ($query) {
                        $query->field('id,store_name');
                    }
                ]
            )
            ->append(['lesson_type_desc', 'is_free_desc'])
            ->field($field)
            ->findOrEmpty()
            ->toArray();
        return $result;
    }

    /**
     * 根据ID获取课时信息
     * 
     * @param int $id 课时ID
     * @param string $field 查询字段，默认为所有字段
     * @param bool $lock 是否加锁，默认为 false
     * @return array 课时信息
     */
    public function getKmsLessonInfoById(int $id, string $field = '*', bool $lock = false): array
    {
        return $this->model->where('id', $id)
        ->append(['lesson_type_desc', 'is_free_desc'])
        ->field($field)->lock($lock)->findOrEmpty()->toArray();
    }




    /**
     * 获取课时数量
     * 
     * @param array $condition 查询条件
     * @return int 课时数量
     */
    public function getKmsLessonCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取课时列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getKmsLessonColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}
