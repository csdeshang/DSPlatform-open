<?php


namespace app\adminapi\service\merchant;



use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\merchant\DeshangMerchantBalanceService;

use app\common\enum\merchant\MerchantBalanceEnum;

use app\common\dao\merchant\MerchantBalanceLogDao;

use think\facade\Db;


class MerchantBalanceService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取商户余额记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getMerchantBalanceLogPages(array $data): array
    {

        $condition = [];

        if(isset($data['merchant_id']) && $data['merchant_id'] != ''){
            $condition[] = ['merchant_id', '=', $data['merchant_id']];
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


        $result = (new MerchantBalanceLogDao())->getWithRelBalanceLogPages($condition);
        return $result;
    }

    /**
     * 修改商户预存款
     * @param array $data 修改数据
     */
    function modifyMerchantBalance(array $data)
    {
        $add_data = array(
            'merchant_id' => $data['merchant_id'],
            'related_id' => 0,
            'change_type' => MerchantBalanceEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_amount' => $data['change_amount'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangMerchantBalanceService();
            $deshangService->modifyMerchantBalance($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
}
