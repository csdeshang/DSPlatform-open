<?php

namespace app\common\dao\video;

use app\common\dao\BaseDao;
use app\common\model\video\VideoShortGoodsRelModel;

/**
 * 短视频商品关联数据访问对象
 * 
 * 负责短视频与商品关联的数据库交互操作
 */
class VideoShortGoodsRelDao extends BaseDao
{
    /**
     * 构造函数
     * 
     * 初始化VideoShortGoodsRelModel模型实例
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new VideoShortGoodsRelModel();
    }
    
    /**
     * 创建短视频商品关联
     * 
     * @param array $data 关联数据
     * @return int 新创建的关联ID
     */
    public function createVideoShortGoodsRel(array $data): int
    {
        $result = $this->model->create($data);
        return $result->id;
    }
    
    /**
     * 批量创建短视频商品关联
     * 
     * @param array $dataList 关联数据列表
     * @return bool 是否成功
     */
    public function createVideoShortGoodsRelAll(array $dataList): bool
    {
        return $this->model->saveAll($dataList) ? true : false;
    }

    /**
     * 删除短视频商品关联
     * 
     * @param array $condition 删除条件
     * @return int 受影响的行数
     */
    public function deleteVideoShortGoodsRel(array $condition): int
    {
        return $this->model->where($condition)->delete();
    }
    
    /**
     * 更新短视频商品关联
     * 
     * @param array $condition 更新条件
     * @param array $data 更新数据
     * @return bool 是否更新成功
     */
    public function updateVideoShortGoodsRel(array $condition, array $data): bool
    {
        $result = $this->model::update($data, $condition);
        return true;
    }

    /**
     * 获取短视频商品关联数量
     * 
     * @param array $condition 查询条件
     * @return int 关联数量
     */
    public function getVideoShortGoodsRelCount(array $condition): int
    {
        return $this->model->where($condition)->count();
    }
    
    /**
     * 获取短视频商品关联列
     * 
     * @param array $condition 查询条件
     * @param string $column 列名
     * @return array 列数据
     */
    public function getVideoShortGoodsRelColumn(array $condition, string $column): array
    {
        return $this->model->where($condition)->column($column);
    }






    
}
