<?php

namespace app\adminapi\service\merchant;

use app\deshang\base\service\BaseAdminService;
use app\deshang\service\merchant\DeshangMerchantService;

use app\common\dao\merchant\MerchantDao;
use app\common\dao\user\UserDao;

use app\common\enum\merchant\MerchantEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

class MerchantService extends BaseAdminService
{
    public function getMerchantPages($data)
    {

        $condition = [];
        if (isset($data['name']) && !empty($data['name'])) {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }
        if (isset($data['contact_name']) && !empty($data['contact_name'])) {
            $condition[] = ['contact_name', 'like', '%' . $data['contact_name'] . '%'];
        }
        if (isset($data['apply_status']) && in_array($data['apply_status'], array_keys(MerchantEnum::getApplyStatusDict()))) {
            $condition[] = ['apply_status', '=', $data['apply_status']];
        }

        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }

        // 联系电话搜索
        if (isset($data['contact_phone']) && !empty($data['contact_phone'])) {
            $condition[] = ['contact_phone', 'like', '%' . $data['contact_phone'] . '%'];
        }

        // 可用金额区间搜索
        if (isset($data['balance_min']) && $data['balance_min'] !== '') {
            $condition[] = ['balance', '>=', $data['balance_min']];
        }
        if (isset($data['balance_max']) && $data['balance_max'] !== '') {
            $condition[] = ['balance', '<=', $data['balance_max']];
        }

        // 总收入区间搜索
        if (isset($data['balance_in_min']) && $data['balance_in_min'] !== '') {
            $condition[] = ['balance_in', '>=', $data['balance_in_min']];
        }
        if (isset($data['balance_in_max']) && $data['balance_in_max'] !== '') {
            $condition[] = ['balance_in', '<=', $data['balance_in_max']];
        }

        // 总支出区间搜索
        if (isset($data['balance_out_min']) && $data['balance_out_min'] !== '') {
            $condition[] = ['balance_out', '>=', $data['balance_out_min']];
        }
        if (isset($data['balance_out_max']) && $data['balance_out_max'] !== '') {
            $condition[] = ['balance_out', '<=', $data['balance_out_max']];
        }

        $result = (new MerchantDao())->getWithRelMerchantPages($condition);

        return $result;
    }

    // 创建商户
    public function createMerchant(array $data)
    {
        // 审核通过
        $data['apply_status'] = 1;
        // 状态 0 关闭 1 开启
        $data['is_enabled'] = 1;

        // 当前用户是否存在
        $userInfo = (new UserDao())->getUserInfo([['id', '=', $data['user_id']]]);
        if (empty($userInfo)) {
            throw new CommonException('用户不存在');
        }

        // 当前用户是否是商户
        $merchantInfo = (new MerchantDao())->getMerchantInfo([['user_id', '=', $data['user_id']]]);
        if (!empty($merchantInfo)) {
            throw new CommonException('用户已经是商户');
        }

        // 检测商户名称是否存在
        if ((new MerchantDao())->checkMerchantNameExists([['name', '=', $data['name']]])) {
            throw new CommonException('商户名称已存在');
        }

        return (new MerchantDao())->createMerchant($data);
    }

    public function updateMerchant(array $data)
    {
        // 需要检测商户名称唯一性
        if (isset($data['name']) && !empty($data['name'])) {
            if ((new MerchantDao())->checkMerchantNameExists([
                ['name', '=', $data['name']],
                ['id', '!=', $data['id']]  // 排除当前商户
            ])) {
                throw new CommonException('商户名称已存在');
            }
        }

        $condition = [['id', '=', $data['id']]];
        return (new MerchantDao())->updateMerchant($condition, $data);
    }

    public function getMerchantInfo(int $id)
    {
        $result = (new MerchantDao())->getWithRelMerchantInfo([['id', '=', $id]]);
        return $result;
    }

    /**
     * 审核商户申请
     * 
     * @param array $data 审核数据
     * @return bool
     */
    public function auditMerchant(array $data)
    {
        // 获取商户信息
        $merchantInfo = (new MerchantDao())->getMerchantInfo([['id', '=', $data['id']]]);

        // 判断商户信息是否存在
        if (empty($merchantInfo)) {
            throw new CommonException('商户信息不存在');
        }

        // 判断商户申请状态 (审核通过 不能重复审核)
        if ($merchantInfo['apply_status'] == MerchantEnum::APPLY_STATUS_PASS) {
            throw new CommonException('商户申请状态不正确');
        }

        // 判断审核状态
        if ($data['apply_status'] == MerchantEnum::APPLY_STATUS_PASS) {
            // 审核通过 修改信息
            $updateData = [
                'apply_status' => MerchantEnum::APPLY_STATUS_PASS,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'] ?? '',
            ];
        } else if ($data['apply_status'] == MerchantEnum::APPLY_STATUS_REJECT) {
            // 审核拒绝 修改信息
            $updateData = [
                'apply_status' => MerchantEnum::APPLY_STATUS_REJECT,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'],
            ];
        } else {
            throw new CommonException('商户申请状态不正确');
        }

        $condition = [['id', '=', $data['id']]];
        return (new MerchantDao())->updateMerchant($condition, $updateData);
    }
}
