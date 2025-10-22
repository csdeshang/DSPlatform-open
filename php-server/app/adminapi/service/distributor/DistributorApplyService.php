<?php

namespace app\adminapi\service\distributor;

use app\deshang\base\service\BaseAdminService;
use app\deshang\exceptions\CommonException;
use app\common\dao\distributor\DistributorApplyDao;


use app\common\enum\distributor\DistributorEnum;
use app\common\enum\distributor\DistributorApplyEnum;

use app\common\dao\user\UserDao;
use app\deshang\utils\SearchHelper;


class DistributorApplyService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDistributorApplyPages(array $data): array
    {
        $condition = [];

        // 判断分销商申请状态
        if(array_key_exists($data['apply_status'], DistributorApplyEnum::getDistributorApplyStatusDict())){
            $condition[] = ['apply_status', '=', $data['apply_status']];
        }

        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }

        return (new DistributorApplyDao())->getDistributorApplyPages($condition);
    }

    // 分销商审核
    public function auditDistributorApply(array $data)
    {
        // 获取分销商申请信息
        $applyInfo = (new DistributorApplyDao())->getDistributorApplyInfo(['id' => $data['id']]);



        // 判断分销商申请信息是否存在
        if (empty($applyInfo)) {
            throw new CommonException('分销商申请信息不存在');
        }

        // 判断分销商申请状态  (审核通过 不能重复审核)
        if ($applyInfo['apply_status'] == DistributorApplyEnum::APPLY_STATUS_APPROVED) {
            throw new CommonException('分销商申请状态不正确');
        }

        // 判断分销商申请状态
        if ($data['apply_status'] == DistributorApplyEnum::APPLY_STATUS_APPROVED) {
            // 审核通过 修改信息
            $update_apply = [
                'apply_status' => DistributorApplyEnum::APPLY_STATUS_APPROVED,
            ];

            // 修改分销商申请信息
           (new DistributorApplyDao())->updateDistributorApply(['id' => $data['id']], $update_apply);

            // 修改会员信息
            $update_user = [
                'is_distributor' => 1,
                'distributor_status' => DistributorEnum::STATUS_NORMAL,
                'distributor_level_id' => 1,
                'distributor_addtime' => time(),
            ];
            (new UserDao())->updateUser(['id' => $applyInfo['user_id']], $update_user);
    
        }else if ($data['apply_status'] == DistributorApplyEnum::APPLY_STATUS_REJECTED) {
            // 审核拒绝 修改信息
            $update_apply = [
                'apply_status' => DistributorApplyEnum::APPLY_STATUS_REJECTED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'],
            ];
            // 修改分销商申请信息
            (new DistributorApplyDao())->updateDistributorApply(['id' => $data['id']], $update_apply);


        }else{
            throw new CommonException('分销商申请状态不正确');
        }

        return true;
    }
}