<?php


namespace app\adminapi\service\distributor;



use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\distributor\DeshangDistributorBalanceService;

use app\common\enum\distributor\DistributorBalanceEnum;

use app\common\dao\distributor\DistributorBalanceLogDao;

use think\facade\Db;
use app\deshang\utils\SearchHelper;


class DistributorBalanceService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取分销商余额记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getDistributorBalanceLogPages(array $data): array
    {

        $condition = [];

        if(isset($data['distributor_user_id']) && $data['distributor_user_id'] != ''){
            $condition[] = ['distributor_user_id', '=', $data['distributor_user_id']];
        }
        if(isset($data['change_type']) && $data['change_type'] !== ''){
            $condition[] = ['change_type', '=', $data['change_type']];
        }
        if(isset($data['change_mode']) && $data['change_mode'] !== ''){
            $condition[] = ['change_mode', '=', $data['change_mode']];
        }

        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['distributor_user_id', 'in', $userIds];
        }
        
        // 变动金额区间搜索
        if (isset($data['change_amount_min']) && $data['change_amount_min'] !== '') {
            $condition[] = ['change_amount', '>=', $data['change_amount_min']];
        }
        if (isset($data['change_amount_max']) && $data['change_amount_max'] !== '') {
            $condition[] = ['change_amount', '<=', $data['change_amount_max']];
        }

        $result = (new DistributorBalanceLogDao())->getWithRelDistributorBalanceLogPages($condition);
        return $result;
    }

    /**
     * 修改分销商预存款
     * @param array $data 修改数据
     */
    function modifyDistributorBalance(array $data)
    {
        $add_data = array(
            'distributor_user_id' => $data['distributor_user_id'],
            'related_id' => 0,
            'change_type' => DistributorBalanceEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_amount' => $data['change_amount'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangDistributorBalanceService();
            $deshangService->modifyDistributorBalance($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
}
