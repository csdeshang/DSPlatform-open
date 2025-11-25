<?php


namespace app\adminapi\service\store;

use app\common\dao\store\TblStoreDao;
use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;

use app\common\enum\store\TblStoreEnum;
use app\common\enum\goods\TblGoodsEnum;
use app\common\dao\goods\TblGoodsDao;
use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;
use app\deshang\utils\SearchHelper;




class TblStoreService extends BaseAdminService
{

    public function getTblStorePage($data)
    {

        $condition = [];
        if (isset($data['store_name']) && $data['store_name'] != '') {
            $condition[] = ['store_name', 'like', '%' . $data['store_name'] . '%'];
        }
        if (isset($data['platform']) && $data['platform'] != '') {
            $condition[] = ['platform', '=', $data['platform']];
        }
        if (isset($data['merchant_id']) && $data['merchant_id'] != '') {
            $condition[] = ['merchant_id', '=', $data['merchant_id']];
        }

        // 商户名搜索
        if (isset($data['merchant_name']) && $data['merchant_name'] != '') {
            $merchantIds = SearchHelper::getMerchantIdsByMerchantName($data['merchant_name']);
            $condition[] = ['merchant_id', 'in', $merchantIds];
        }

        if (isset($data['apply_status']) && in_array($data['apply_status'], array_keys(TblStoreEnum::getApplyStatusDict()))) {
            $condition[] = ['apply_status', '=', $data['apply_status']];
        }


        $result = (new TblStoreDao())->getWithRelStorePages($condition);
        return $result;
    }


    // [此创建店铺方法不能使用]  需要根据平台创建，因为涉及创建不同店铺类型的其他数据
    public function createTblStore($data) {}


    public function getTblStoreList($data)
    {
        $condition = array();
        if (isset($data['merchant_id']) && $data['merchant_id'] > 0) {
            $condition[] = ['merchant_id', '=', $data['merchant_id']];
        }

        // 如果条件为空，则返回空数组
        if (empty($condition)) {
            throw new CommonException('getTblStoreList异常 条件为空');
        }

        $result = (new TblStoreDao())->getStoreList($condition,'*','id desc',100);
        return $result;
    }


    public function getTblStoreInfo($id)
    {

        $result = (new TblStoreDao)->getStoreInfo([['id', '=', $id]]);
        return $result;
    }


    public function updateTblStore(int $id, array $data)
    {
        // 先获取店铺信息，判断店铺是否存在
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $id]]);
        if (empty($store_info)) {
            throw new CommonException('店铺不存在');
        }

        // 如果更新了店铺名称，需要检测唯一性
        if (isset($data['store_name']) && !empty($data['store_name'])) {
            if ((new TblStoreDao())->checkStoreNameExists([
                ['store_name', '=', $data['store_name']],
                ['id', '!=', $id]  // 排除当前店铺
            ])) {
                throw new CommonException('店铺名称已存在');
            }
        }

        $result = (new TblStoreDao())->updateStore([['id', '=', $id]], $data);

        // 判断店铺启用状态是否发生变化
        if ($result && isset($data['is_enabled'])) {
            $old_status = $store_info['is_enabled'];
            $new_status = $data['is_enabled'];

            // 店铺被禁用（从启用变为禁用）
            if ($old_status == 1 && $new_status == 0) {
                $this->handleTblStoreDisabled($id);
            }

            // 店铺被启用（从禁用变为启用）
            if ($old_status == 0 && $new_status == 1) {
                $this->handleTblStoreEnabled($id);
            }
        }

        // 清除缓存
        KvManager::cache()->clear(CacheKeyManager::STORE_TAG);

        return $result;
    }



    // 处理店铺禁用
    private function handleTblStoreDisabled($store_id)
    {
        // 店铺下的商品 设置为系统违规下架状态
        (new TblGoodsDao())->updateGoods([
            ['store_id', '=', $store_id],
            ['sys_status', '=', TblGoodsEnum::SYS_STATUS_NORMAL]
        ], [
            'sys_status' => TblGoodsEnum::SYS_STATUS_VIOLATED,
            'sys_status_reason' => '店铺被禁用，商品自动下架'
        ]);
        // 此处可添加消息通知，目前先添加基本逻辑

        return true;
    }

    // 处理店铺启用
    private function handleTblStoreEnabled($store_id)
    {
        // 将因店铺禁用而被系统下架的商品恢复为正常状态
        // 但保持商家自己的上架状态不变
        (new TblGoodsDao())->updateGoods([
            ['store_id', '=', $store_id],
            ['sys_status', '=', TblGoodsEnum::SYS_STATUS_VIOLATED],
            ['sys_status_reason', 'like', '%店铺被禁用%']
        ], [
            'sys_status' => TblGoodsEnum::SYS_STATUS_NORMAL,
            'sys_status_reason' => null
        ]);
        // 此处可添加消息通知，目前先添加基本逻辑

        return true;
    }




    /**
     * 审核店铺申请
     * @param array $data
     * @return array
     */
    public function auditTblStore($data)
    {
        // 获取店铺信息
        $store_info = (new TblStoreDao())->getStoreInfo([['id', '=', $data['id']]]);
        if (empty($store_info)) {
            throw new CommonException('店铺不存在');
        }

        // 检查当前状态
        if ($store_info['apply_status'] != TblStoreEnum::APPLY_STATUS_WAIT) {
            throw new CommonException('该店铺已审核，不能重复审核');
        }

        // 审核通过
        if ($data['apply_status'] == TblStoreEnum::APPLY_STATUS_APPROVED) {
            $update_data = [
                'apply_status' => TblStoreEnum::APPLY_STATUS_APPROVED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'] ?? '审核通过',
            ];
        } else if ($data['apply_status'] == TblStoreEnum::APPLY_STATUS_REJECTED) {
            // 审核拒绝
            if (empty($data['audit_remark'])) {
                throw new CommonException('拒绝时必须填写审核备注');
            }
            $update_data = [
                'apply_status' => TblStoreEnum::APPLY_STATUS_REJECTED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'],
            ];
        } else {
            throw new CommonException('审核状态值无效');
        }

        // 更新店铺信息
        $result = (new TblStoreDao())->updateStore([['id', '=', $data['id']]], $update_data);
        return $result;
    }
}
