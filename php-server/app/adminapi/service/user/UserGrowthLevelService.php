<?php

namespace app\adminapi\service\user;

use app\common\dao\user\UserGrowthLevelDao;
use app\deshang\base\service\BaseAdminService;
use app\common\dao\goods\TblGoodsUserdiscountDao;
use app\common\dao\goods\TblGoodsDao;
use app\common\dao\user\UserDao;

// 用户成长等级服务

class UserGrowthLevelService extends BaseAdminService
{
    public function getUserGrowthLevelList($condition)
    {
        $result = (new UserGrowthLevelDao())->getUserGrowthLevelList($condition,'*','min_growth asc');
        return $result;
    }

    public function createUserGrowthLevel(array $data)
    {
        $result = (new UserGrowthLevelDao())->createUserGrowthLevel($data);
        if($result) {
            // 重置商品会员等级价格
            $this->resetGoodsUserdiscount();
        }
        return $result;
    }

    public function updateUserGrowthLevel($id,array $data):int
    {
        $condition = [];
        $condition['id'] = $id;

        $result = (new UserGrowthLevelDao())->updateUserGrowthLevel($condition,$data);
        if($result) {
            // 重置商品会员等级价格
            $this->resetGoodsUserdiscount();
        }
        return $result;
    }

    public function getUserGrowthLevelInfo(int $id)
    {
        $result = (new UserGrowthLevelDao())->getUserGrowthLevelInfoById($id);
        return $result;
    }

    public function deleteUserGrowthLevel(int $id)
    {
        $condition = [];
        $condition['id'] = $id;
        $result = (new UserGrowthLevelDao())->deleteUserGrowthLevel($condition);
        if($result) {
            // 重置商品会员等级价格
            $this->resetGoodsUserdiscount();
        }
        return $result;
    }

    /**
     * 重置商品会员等级价格
     * 当会员等级有变更时，清空所有会员等级价格设置
     */
    public function resetGoodsUserdiscount()
    {
        // 删除所有会员等级价格配置
        (new TblGoodsUserdiscountDao())->deleteGoodsUserdiscount([['id', '>', 0]]);
        
        // 更新所有已开启会员价格的商品状态为未开启
        (new TblGoodsDao())->updateGoods([['is_userdiscount_goods', '=', 1]], ['is_userdiscount_goods' => 0]);
        
        // 可选：重置用户会员等级，如果需要的话
        // (new UserDao())->updateUser([['growth_level_id', '>', 0]], ['growth_level_id' => 1]);
    }
}
