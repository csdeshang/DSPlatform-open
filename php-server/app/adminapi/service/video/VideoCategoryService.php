<?php

namespace app\adminapi\service\video;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\video\VideoCategoryDao;
use app\deshang\exceptions\CommonException;

class VideoCategoryService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取视频分类列表
     */
    public function getVideoCategoryList(array $data)
    {
        $condition = array(
            'type' => $data['type'],
        );
        $result = (new VideoCategoryDao())->getVideoCategoryList($condition);
        return $result;
    }



    /**
     * 获取视频分类详情
     */
    public function getVideoCategoryInfo(array $data)
    {
        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        $field = '*';
        $result = (new VideoCategoryDao())->getVideoCategoryInfo($condition, $field);
        return $result;
    }

    /**
     * 创建视频分类
     */
    public function createVideoCategory(array $data)
    {
        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [
                ['id', '=', $data['pid']]
            ];
            $has_cate = (new VideoCategoryDao())->getVideoCategoryCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }
        
        // 添加创建和更新时间
        $data['create_at'] = time();
        $data['update_at'] = time();
        
        //添加
        return (new VideoCategoryDao())->createVideoCategory($data);
    }

    /**
     * 更新视频分类
     */
    public function updateVideoCategory(array $data)
    {
        // 检测 pid
        $list = (new VideoCategoryDao())->getVideoCategoryList([]);
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['pid'] . ' 设置为子分类，这将导致循环引用');
        }

        // 添加更新时间
        $data['update_at'] = time();

        //更新
        $condition = array();
        $condition[] = array('id', '=', $data['id']);

        return (new VideoCategoryDao())->updateVideoCategory($condition, $data);
    }

    /**
     * 删除视频分类
     */
    public function deleteVideoCategory(int $id)
    {
        //判断是否有下级
        $has_child = (new VideoCategoryDao())->getVideoCategoryCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该分类有下级分类，无法删除');
        }

        return (new VideoCategoryDao())->deleteVideoCategory([['id', '=', $id]]);
    }

    /**
     * 切换视频分类字段状态
     * @param array $data
     * @return int
     */
    public function toggleVideoCategoryField($data)
    {
        $id = $data['id'];
        $field = $data['field'];
        
        // 验证字段是否允许切换
        $allowedFields = ['is_show'];
        if (!in_array($field, $allowedFields)) {
            throw new CommonException('不允许切换此字段');
        }
        
        // 获取当前值
        $currentInfo = (new VideoCategoryDao())->getVideoCategoryInfoById($id);
        if (empty($currentInfo)) {
            throw new CommonException('视频分类不存在');
        }
        
        // 切换值
        $currentValue = $currentInfo[$field];
        $newValue = $currentValue == '1' ? '0' : '1';
        
        // 更新数据
        $condition = [['id', '=', $id]];
        $updateData = [$field => $newValue, 'update_at' => time()];
        
        $result = (new VideoCategoryDao())->updateVideoCategory($condition, $updateData);
        return $result;
    }
}
