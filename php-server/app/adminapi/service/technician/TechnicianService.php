<?php

namespace app\adminapi\service\technician;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\technician\TechnicianDao;
use app\common\dao\technician\TechnicianGoodsRelDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\order\TblOrderDeliveryDao;
use app\common\enum\technician\TechnicianEnum;
use app\common\enum\order\TblOrderDeliveryEnum;
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

        // 根据参数控制是否显示已删除数据，空或不传时不筛选（显示全部）
        if (isset($data['is_deleted']) && $data['is_deleted'] !== '' && $data['is_deleted'] !== null && in_array((int)$data['is_deleted'], [0, 1], true)) {
            $condition[] = ['is_deleted', '=', (int)$data['is_deleted']];
        }
        
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
        if (isset($store['is_deleted']) && $store['is_deleted'] == 1) {
            throw new CommonException('店铺已删除，无法绑定');
        }
        // 店铺平台类型是否为家政
        if ($store['platform'] != 'house') {
            throw new CommonException('只有家政店铺才能绑定师傅');
        }

        // 若已是当前绑定店铺，无需变更
        if ((int)$technician['store_id'] === (int)$data['store_id']) {
            return true;
        }

        // 有未完成订单就不能换店：按配送/服务状态判断，仅统计 delivery_status 非「已取消」「已完成」的笔数。
        // 以师傅侧交付是否完成为准，一次 count 查询，避免先拉 order_id 再 IN 导致大量数据时性能与内存问题。
        // 说明：师傅交付完成后即可视为该单对师傅侧结束；用户确认收货不影响本校验，结算仍按订单与 order_delivery 记录处理。
        $unfinishedCount = (new TblOrderDeliveryDao())->getOrderDeliveryCount([
            ['technician_id', '=', $id],
            ['delivery_status', 'not in', [
                TblOrderDeliveryEnum::DELIVERY_STATUS_CANCELLED,
                TblOrderDeliveryEnum::DELIVERY_STATUS_COMPLETED,
            ]],
        ]);
        if ($unfinishedCount > 0) {
            throw new CommonException('该师傅名下还有未完成的订单（' . $unfinishedCount . ' 笔），请完成后再更换店铺');
        }

        // 清除师傅与商品的关联关系
        (new TechnicianGoodsRelDao())->deleteTechnicianGoodsRel([['technician_id', '=', $id]]);

        // 更新师傅的店铺绑定（同步 store_id 与 merchant_id）
        $updateData = [
            'store_id' => $data['store_id'],
            'merchant_id' => $store['merchant_id'],
        ];

        (new TechnicianDao())->updateTechnician([['id', '=', $id]], $updateData);

        return true;
    }

    /**
     * 审核师傅申请
     * @param array $data 审核数据
     * @return int
     */
    public function auditTechnician(array $data)
    {
        // 获取师傅信息
        $technicianInfo = (new TechnicianDao())->getTechnicianInfo([['id', '=', $data['id']]]);

        // 判断师傅信息是否存在
        if (empty($technicianInfo)) {
            throw new CommonException('师傅信息不存在');
        }

        // 判断师傅申请状态 (审核通过 不能重复审核)
        if ($technicianInfo['apply_status'] == TechnicianEnum::APPLY_STATUS_APPROVED) {
            throw new CommonException('师傅申请状态不正确');
        }

        // 判断审核状态
        if ($data['apply_status'] == TechnicianEnum::APPLY_STATUS_APPROVED) {
            // 审核通过 修改信息
            $updateData = [
                'apply_status' => TechnicianEnum::APPLY_STATUS_APPROVED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'] ?? '',
            ];
        } else if ($data['apply_status'] == TechnicianEnum::APPLY_STATUS_REJECTED) {
            // 审核拒绝 修改信息
            $updateData = [
                'apply_status' => TechnicianEnum::APPLY_STATUS_REJECTED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'],
            ];
        } else {
            throw new CommonException('师傅申请状态不正确');
        }

        $condition = [['id', '=', $data['id']]];
        return (new TechnicianDao())->updateTechnician($condition, $updateData);
    }

    /**
     * 软删除师傅
     *
     * @param int $id 师傅ID
     * @return int 受影响的行数
     */
    public function softDeleteTechnician(int $id)
    {
        $dao = new TechnicianDao();
        $technician_info = $dao->getTechnicianInfoById($id);
        if (empty($technician_info)) {
            throw new CommonException('师傅不存在');
        }
        if ($technician_info['is_deleted'] == 1) {
            throw new CommonException('师傅已被删除');
        }
        $update_data = [
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $condition = [
            ['id', '=', $id],
            ['is_deleted', '=', 0],
        ];
        return $dao->updateTechnician($condition, $update_data);
    }

    /**
     * 恢复师傅
     *
     * @param int $id 师傅ID
     * @return int 受影响的行数
     */
    public function restoreTechnician(int $id)
    {
        $dao = new TechnicianDao();
        $technician_info = $dao->getTechnicianInfoById($id);
        if (empty($technician_info)) {
            throw new CommonException('师傅不存在');
        }
        if ($technician_info['is_deleted'] != 1) {
            throw new CommonException('师傅未被删除，无需恢复');
        }
        $update_data = [
            'is_deleted' => 0,
            'deleted_at' => null,
        ];
        $condition = [
            ['id', '=', $id],
            ['is_deleted', '=', 1],
        ];
        return $dao->updateTechnician($condition, $update_data);
    }

} 