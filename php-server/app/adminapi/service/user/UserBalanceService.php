<?php


namespace app\adminapi\service\user;

use think\facade\Db;

use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\user\DeshangUserBalanceService;

use app\common\enum\user\UserBalanceEnum;

use app\common\dao\user\UserBalanceLogDao;
use app\deshang\utils\SearchHelper;


class UserBalanceService extends BaseAdminService
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
    public function getUserBalanceLogPages(array $data): array
    {
        $condition = [];
        if(isset($data['user_id']) && $data['user_id'] != ''){
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }
        
        if(isset($data['change_type']) && $data['change_type'] !== ''){
            $condition[] = ['change_type', '=', $data['change_type']];
        }
        if(isset($data['change_mode']) && $data['change_mode'] !== ''){
            $condition[] = ['change_mode', '=', $data['change_mode']];
        }
        
        // 变动金额区间搜索
        if (isset($data['change_amount_min']) && $data['change_amount_min'] !== '') {
            $condition[] = ['change_amount', '>=', $data['change_amount_min']];
        }
        if (isset($data['change_amount_max']) && $data['change_amount_max'] !== '') {
            $condition[] = ['change_amount', '<=', $data['change_amount_max']];
        }

        $result = (new UserBalanceLogDao())->getWithRelBalanceLogPages($condition);
        return $result;
    }

    /**
     * 修改会员预存款
     * @param array $data 修改数据
     */
    function modifyUserBalance(array $data)
    {
        $add_data = array(
            'user_id' => $data['user_id'],
            'related_id' => 0,
            'change_type' => UserBalanceEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_amount' => $data['change_amount'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangUserBalanceService();
            $deshangService->modifyUserBalance($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
}
