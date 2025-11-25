<?php


namespace app\adminapi\service\rider;

use think\facade\Db;

use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\rider\DeshangRiderBalanceService;

use app\common\enum\rider\RiderBalanceEnum;

use app\common\dao\rider\RiderBalanceLogDao;


class RiderBalanceService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 获取会员余额记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getRiderBalanceLogPages(array $data): array
    {
        $condition = [];

        if (isset($data['rider_id']) && $data['rider_id'] != '') {
            $condition[] = ['rider_id', '=', $data['rider_id']];
        }
        if (isset($data['change_type']) && $data['change_type'] != '') {
            $condition[] = ['change_type', '=', $data['change_type']];
        }
        if (isset($data['change_mode']) && $data['change_mode'] != '') {
            $condition[] = ['change_mode', '=', $data['change_mode']];
        }

        $result = (new RiderBalanceLogDao())->getRiderBalanceLogPages($condition);

        return $result;
    }

    /**
     * 修改会员预存款
     * @param array $data 修改数据
     */
    function modifyRiderBalance(array $data)
    {
        $add_data = array(
            'rider_id' => $data['rider_id'],
            'related_id' => 0,
            'change_type' => RiderBalanceEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_amount' => $data['change_amount'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangRiderBalanceService();
            $deshangService->modifyRiderBalance($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
}
