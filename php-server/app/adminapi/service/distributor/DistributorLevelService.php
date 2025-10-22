<?php

namespace app\adminapi\service\distributor;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\user\UserDao;
use app\common\dao\distributor\DistributorLevelDao;
use app\common\dao\distributor\DistributorGoodsDao;
use app\common\dao\goods\TblGoodsDao;
use app\deshang\exceptions\CommonException;



class DistributorLevelService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    // 获取分销商等级列表
    public function getDistributorLevelList(): array
    {
        return (new DistributorLevelDao())->getDistributorLevelList([],'*','sort asc');
    }

    // 创建分销商等级
    public function createDistributorLevel(array $data): int
    {
        
        $result = (new DistributorLevelDao())->createDistributorLevel($data);
        if($result){
            // 重置 单独设置佣金比例
            $this->resetDistributorGoods();
        }
        return $result;
    }

    // 编辑分销商等级
    public function updateDistributorLevel(int $id, array $data): int
    {
        return (new DistributorLevelDao())->updateDistributorLevel([['id', '=', $id]], $data);
    }

    // 删除分销商等级   
    public function deleteDistributorLevel(int $id): int
    {
        if($id == 1){
            throw new CommonException('默认等级不能删除');
        }
        $result = (new DistributorLevelDao())->deleteDistributorLevel([['id', '=', $id]]);
        if($result){
            // 重置 单独设置佣金比例
            $this->resetDistributorGoods();
        }
        return $result;
    }

    // 重置 单独设置佣金比例 (类型修改了，设置的佣金比例需要重置)
    public function resetDistributorGoods()
    {
        // 删除 单独设置佣金比例 
        (new DistributorGoodsDao())->deleteDistributorGoods([['id', '>', 0]]);
        // 用户的分销等级 全部设置为 默认分销等级
        (new UserDao())->updateUser([['id', '>', 0]], ['distributor_level_id' => 1]);
        // 已开启佣金的商品  类型全部设置为 默认分销等级佣金比例
        (new TblGoodsDao())->updateGoods([['is_distributor_goods', '=', 1]], ['distributor_goods_type' => 0]);
        
    }

    // 获取分销商等级信息
    public function getDistributorLevelInfo(int $id): array
    {
        return (new DistributorLevelDao())->getDistributorLevelInfo([['id', '=', $id]]);
    }
}   