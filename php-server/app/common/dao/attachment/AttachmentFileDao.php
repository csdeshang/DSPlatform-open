<?php

namespace app\common\dao\attachment;

use app\common\dao\BaseDao;
use app\common\model\attachment\AttachmentFileModel;

/**
 * 附件文件数据访问对象
 * 
 * 负责附件文件的数据库交互操作
 */
class AttachmentFileDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化AttachmentFileModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new AttachmentFileModel();
    }
    
    /**
     * 创建附件文件
     * 
     * @param array $data 附件文件数据
     * @return int 新创建的附件文件ID
     */
    public function createAttachmentFile(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }

    /**
     * 删除附件文件
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteAttachmentFile(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新附件文件
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateAttachmentFile(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取附件文件列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 附件文件列表
     */
    public function getAttachmentFileList(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        return $this->model->where($condition)->field($field)->order($order)->select()->toArray();
    }

    /**
     * 获取附件文件分页列表
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @param string $order 排序规则，默认按ID降序
     * @return array 分页数据
     */
    public function getAttachmentFilePages(array $condition, string $field = '*', string $order = 'id desc'): array
    {
        $result = $this->model->where($condition)->field($field)->order($order);
        return $this->getPaginate($result);
    }

    /**
     * 获取单条附件文件信息
     * 
     * @param array $condition 查询条件
     * @param string $field 查询字段，默认为所有字段
     * @return array 附件文件信息
     */
    public function getAttachmentFileInfo(array $condition, string $field = '*'): array
    {
        return $this->model->where($condition)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 根据ID获取附件文件信息
     * 
     * @param int $id 附件文件ID
     * @param string $field 查询字段，默认为所有字段
     * @return array 附件文件信息
     */
    public function getAttachmentFileById(int $id, string $field = '*'): array
    {
        return $this->model->where('id', $id)->field($field)->findOrEmpty()->toArray();
    }
    
    /**
     * 获取附件文件数量
     * 
     * @param array $condition 查询条件
     * @return int 附件文件数量
     */
    public function getAttachmentFileCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取附件文件列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getAttachmentFileColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }

    
}
