<?php

namespace app\common\dao\attachment;

use app\common\dao\BaseDao;
use app\common\model\attachment\AttachmentCateModel;

/**
 * 附件分类数据访问对象
 * 
 * 负责附件分类的数据库交互操作
 */
class AttachmentCateDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AttachmentCateModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AttachmentCateModel();
    }
    
    /**
     * 创建附件分类
     * 
     * @param array $data 附件分类数据
     * @return int 新创建的附件分类ID
     */
    public function createAttachmentCate(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除附件分类
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAttachmentCate(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新附件分类
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateAttachmentCate(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取附件分类列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 附件分类列表
     */
    public function getAttachmentCateList(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取附件分类分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按排序值升序，ID升序
     * @return array 分页数据
     */
    public function getAttachmentCatePages(array $condition, string $field = '*', string $order = 'sort asc, id asc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条附件分类信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 附件分类信息
     */
    public function getAttachmentCateInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取附件分类信息
     * 
     * @param int $id 附件分类ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 附件分类信息
     */
    public function getAttachmentCateById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取附件分类数量
     * 
     * @param array $condition 查询条件
     * @return int 附件分类数量
     */
    public function getAttachmentCateCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }


    /**
     * 获取附件分类列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAttachmentCateColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    
}
