<?php

namespace app\adminapi\service\pointsGoods;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\pointsGoods\PointsGoodsCategoryDao;
use app\deshang\exceptions\CommonException;

class PointsGoodsCategoryService extends BaseAdminService
{
    /**
     * 获取积分商品分类列表
     * 
     * @param array $data 查询参数
     * @return array 分类列表
     */
    public function getPointsGoodsCategoryList(array $data)
    {
        $condition = [];
        
        // 分类名称模糊搜索
        if (isset($data['name']) && !empty($data['name'])) {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }
        
        // 父级分类筛选
        if (isset($data['pid']) && $data['pid'] !== '') {
            $condition[] = ['pid', '=', $data['pid']];
        }
        
        // 是否显示筛选
        if (isset($data['is_show']) && in_array($data['is_show'], [0, 1])) {
            $condition[] = ['is_show', '=', $data['is_show']];
        }

        $result = (new PointsGoodsCategoryDao())->getPointsGoodsCategoryList($condition);

        return $result;
    }

    /**
     * 获取积分商品分类详情
     * 
     * @param array $data 查询参数
     * @return array 分类详情
     */
    public function getPointsGoodsCategoryInfo(array $data)
    {
        $condition = [['id', '=', $data['id']]];
        $result = (new PointsGoodsCategoryDao())->getPointsGoodsCategoryInfo($condition);
        return $result;
    }

    /**
     * 创建积分商品分类
     * 
     * @param array $data 分类数据
     * @return int 新创建的分类ID
     */
    public function createPointsGoodsCategory(array $data)
    {
        // 设置创建时间
        $data['create_at'] = time();

        // 判断父级分类是否存在
        if ($data['pid'] != 0) {
            $condition = [['id', '=', $data['pid']]];
            $has_cate = (new PointsGoodsCategoryDao())->getPointsGoodsCategoryCount($condition);
            if ($has_cate == 0) {
                throw new CommonException('父级分类不存在');
            }
        }

        return (new PointsGoodsCategoryDao())->createPointsGoodsCategory($data);
    }

    /**
     * 更新积分商品分类
     * 
     * @param array $data 分类数据
     * @return bool 是否更新成功
     */
    public function updatePointsGoodsCategory(array $data)
    {
        // 设置更新时间
        $data['update_at'] = time();

        // 检测 pid 防止循环引用
        $list = (new PointsGoodsCategoryDao())->getPointsGoodsCategoryList([]);
        $result = checkParentId($data['pid'], $data['id'], $list, 'id', 'pid');
        if (!$result) {
            throw new CommonException('分类ID ' . $data['id'] . ' 不能将父分类ID ' . $data['pid'] . ' 设置为子分类，这将导致循环引用');
        }

        $condition = [['id', '=', $data['id']]];
        return (new PointsGoodsCategoryDao())->updatePointsGoodsCategory($condition, $data);
    }

    /**
     * 删除积分商品分类
     * 
     * @param int $id 分类ID
     * @return bool 是否删除成功
     */
    public function deletePointsGoodsCategory(int $id)
    {
        // 判断是否有下级
        $has_child = (new PointsGoodsCategoryDao())->getPointsGoodsCategoryCount([['pid', '=', $id]]);
        if ($has_child > 0) {
            throw new CommonException('该分类有下级分类，无法删除');
        }

        $condition = [['id', '=', $id]];
        return (new PointsGoodsCategoryDao())->deletePointsGoodsCategory($condition);
    }
}
