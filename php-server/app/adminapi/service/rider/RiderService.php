<?php

namespace app\adminapi\service\rider;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\rider\RiderDao;
use app\common\dao\user\UserDao;
use app\common\enum\rider\RiderEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;



class RiderService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();

    }

    public function getRiderPages($data){

        $condition = [];

        // 根据参数控制是否显示已删除数据，空或不传时不筛选（显示全部）
        if (isset($data['is_deleted']) && $data['is_deleted'] !== '' && $data['is_deleted'] !== null && in_array((int)$data['is_deleted'], [0, 1], true)) {
            $condition[] = ['is_deleted', '=', (int)$data['is_deleted']];
        }

        if (isset($data['user_id']) && $data['user_id'] != '') {
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        if (isset($data['name']) && $data['name'] != '') {
            $condition[] = ['name', 'like', '%' . $data['name'] . '%'];
        }
        if (isset($data['mobile']) && $data['mobile'] != '') {
            $condition[] = ['mobile', 'like', '%' . $data['mobile'] . '%'];
        }
        if (isset($data['apply_status']) && in_array($data['apply_status'], array_keys(RiderEnum::getApplyStatusDict()))) {
            $condition[] = ['apply_status', '=', $data['apply_status']];
        }

        $result = (new RiderDao())->getWithRelRiderPages($condition);

        return $result;


        
    }


    public function createRider($data){

        // 判断用户是否存在
        $user = (new UserDao())->getUserInfoById($data['user_id']);
        if (empty($user)) {
            throw new CommonException('用户不存在');
        }

        // 当前用户是否已有骑手（含已删除）
        $rider = (new RiderDao())->getRiderInfo([['user_id', '=', $data['user_id']]]);
        if (!empty($rider)) {
            if ($rider['is_deleted'] === 1) {
                throw new CommonException('该用户的骑手已被删除，无法再次创建');
            }
            throw new CommonException('用户所关联的骑手已经存在,请勿重复添加');
        }

        $result = (new RiderDao())->createRider($data);
        return $result;


    }

    public function updateRider(int $id, array $data): int
    {

        $condition = [];
        $condition[] = ['id', '=', $id];

        $result = (new RiderDao())->updateRider($condition, $data);
        return $result;
    }


    public function getRiderInfo($id){
        $condition = [['id', '=', $id]];
        $result = (new RiderDao())->getRiderInfo($condition);
        return $result;
    }

    public function auditRider(array $data)
    {
        // 获取骑手信息
        $riderInfo = (new RiderDao())->getRiderInfo([['id', '=', $data['id']]]);

        // 判断骑手信息是否存在
        if (empty($riderInfo)) {
            throw new CommonException('骑手信息不存在');
        }

        // 判断骑手申请状态 (审核通过 不能重复审核)
        if ($riderInfo['apply_status'] == RiderEnum::APPLY_STATUS_APPROVED) {
            throw new CommonException('骑手申请状态不正确');
        }

        // 判断审核状态
        if ($data['apply_status'] == RiderEnum::APPLY_STATUS_APPROVED) {
            // 审核通过 修改信息
            $updateData = [
                'apply_status' => RiderEnum::APPLY_STATUS_APPROVED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'] ?? '',
            ];
        } else if ($data['apply_status'] == RiderEnum::APPLY_STATUS_REJECTED) {
            // 审核拒绝 修改信息
            $updateData = [
                'apply_status' => RiderEnum::APPLY_STATUS_REJECTED,
                'audit_time' => time(),
                'audit_remark' => $data['audit_remark'],
            ];
        } else {
            throw new CommonException('骑手申请状态不正确');
        }

        $condition = [['id', '=', $data['id']]];
        return (new RiderDao())->updateRider($condition, $updateData);
    }

    /**
     * 软删除骑手
     *
     * @param int $id 骑手ID
     * @return int 受影响的行数
     */
    public function softDeleteRider(int $id)
    {
        $dao = new RiderDao();
        $rider_info = $dao->getRiderInfoById($id);
        if (empty($rider_info)) {
            throw new CommonException('骑手不存在');
        }
        if ($rider_info['is_deleted'] == 1) {
            throw new CommonException('骑手已被删除');
        }
        $update_data = [
            'is_deleted' => 1,
            'deleted_at' => time(),
        ];
        $condition = [
            ['id', '=', $id],
            ['is_deleted', '=', 0],
        ];
        return $dao->updateRider($condition, $update_data);
    }

    /**
     * 恢复骑手
     *
     * @param int $id 骑手ID
     * @return int 受影响的行数
     */
    public function restoreRider(int $id)
    {
        $dao = new RiderDao();
        $rider_info = $dao->getRiderInfoById($id);
        if (empty($rider_info)) {
            throw new CommonException('骑手不存在');
        }
        if ($rider_info['is_deleted'] != 1) {
            throw new CommonException('骑手未被删除，无需恢复');
        }
        $update_data = [
            'is_deleted' => 0,
            'deleted_at' => null,
        ];
        $condition = [
            ['id', '=', $id],
            ['is_deleted', '=', 1],
        ];
        return $dao->updateRider($condition, $update_data);
    }

}