<?php

namespace app\adminapi\service\technician;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\technician\TechnicianDao;
use app\common\dao\technician\TechnicianGoodsRelDao;
use app\common\dao\store\TblStoreDao;
use app\common\enum\technician\TechnicianEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

/**
 * 管理端师傅服务类
 */
class TechnicianService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取师傅分页列表
     * @param array $data 查询条件
     * @return array
     */
    public function getTechnicianPages($data)
    {
        $condition = [];
        
        // 师傅名称搜索
        if (!empty($data['name'])) {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }
        
        // 手机号搜索
        if (!empty($data['mobile'])) {
            $condition[] = ['mobile', 'like', '%' . $data['mobile'] . '%'];
        }
        
        // 申请状态筛选
        if (isset($data['apply_status']) && $data['apply_status'] !== '') {
            $condition[] = ['apply_status', '=', $data['apply_status']];
        }
        
        // 师傅状态筛选
        if (isset($data['technician_status']) && $data['technician_status'] !== '') {
            $condition[] = ['technician_status', '=', $data['technician_status']];
        }
        
        // 是否可用筛选
        if (isset($data['is_enabled']) && $data['is_enabled'] !== '') {
            $condition[] = ['is_enabled', '=', $data['is_enabled']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }

        $result = (new TechnicianDao())->getWithRelTechnicianPages($condition);
        return $result;
    }

    /**
     * 获取师傅详情
     * @param int $id 师傅ID
     * @return array
     */
    public function getTechnicianInfo($id)
    {
        $condition = [
            ['id', '=', $id]
        ];
        $result = (new TechnicianDao())->getTechnicianInfo($condition);
        if (empty($result)) {
            throw new CommonException('师傅不存在');
        }
        return $result;
    }


    public function updateTechnician($id,$data)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];

        // 验证师傅是否存在
        $technician = (new TechnicianDao())->getTechnicianInfo($condition);
        if (empty($technician)) {
            throw new CommonException('师傅不存在');
        }



        $result = (new TechnicianDao())->updateTechnician($condition,$data);
        return $result;
    }

    /**
     * 更新师傅绑定店铺
     * @param int $id 师傅ID
     * @param array $data 修改数据
     */
    public function updateTechnicianBindStore($id, array $data)
    {
        // 验证师傅是否存在
        $technician = (new TechnicianDao())->getTechnicianInfo([['id', '=', $id]]);
        if (!$technician) {
            throw new CommonException('师傅不存在');
        }

        // 验证店铺是否存在
        $store = (new TblStoreDao())->getStoreInfo([['id', '=', $data['store_id']]]);
        if (!$store) {
            throw new CommonException('店铺不存在');
        }
        //店铺平台类型是否为家政
        if ($store['platform'] != 'house') {
            throw new CommonException('只有家政店铺才能绑定师傅');
        }

        // 清除师傅与商品的关联关系
        (new TechnicianGoodsRelDao())->deleteTechnicianGoodsRel([['technician_id', '=', $id]]);

        // 更新师傅的店铺绑定
        $updateData = [
            'store_id' => $data['store_id'],
        ];
        
        (new TechnicianDao())->updateTechnician([['id', '=', $id]], $updateData);
        
        return true;
    }

} 