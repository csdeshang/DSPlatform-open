<?php

namespace app\common\dao\editable;

use app\common\dao\BaseDao;
use app\common\model\editable\EditablePageModel;

/**
 * 可编辑页面数据访问对象
 * 
 * 负责可编辑页面的数据库交互操作
 */
class EditablePageDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化EditablePageModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new EditablePageModel();
    }

    /**
     * 创建可编辑页面
     * 
     * @param array $data 页面数据
     * @return int 新创建的页面ID
     */
    public function createEditablePage(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除可编辑页面
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteEditablePage(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新可编辑页面
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateEditablePage(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取可编辑页面列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 页面列表
     */
    public function getEditablePageList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取可编辑页面分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按创建时间降序
     * @return array 分页数据
     */
    public function getEditablePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)
        ->with([
            'platform' => function ($query) {
                $query->field('id,name,platform,scene');
            }
        ])
        ->append(['type_desc'])
        ->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条可编辑页面信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 页面信息
     */
    public function getEditablePageInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取可编辑页面信息
     * 
     * @param int $id 页面ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 页面信息
     */
    public function getEditablePageById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取可编辑页面数量
     * 
     * @param array $condition 查询条件
     * @return int 页面数量
     */
    public function getEditablePageCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }

    /**
     * 获取可编辑页面列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getEditablePageColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }
}




