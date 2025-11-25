<?php

namespace app\adminapi\service\technician;

use think\facade\Db;
use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\technician\DeshangTechnicianBalanceService;
use app\common\enum\technician\TechnicianBalanceEnum;
use app\common\dao\technician\TechnicianBalanceLogDao;

/**
 * 管理端师傅余额服务类
 */
class TechnicianBalanceService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取师傅余额记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getTechnicianBalanceLogPages(array $data): array
    {
        $condition = [];

        if (isset($data['technician_id']) && $data['technician_id'] != '') {
            $condition[] = ['technician_id', '=', $data['technician_id']];
        }
        if (isset($data['change_type']) && $data['change_type'] != '') {
            $condition[] = ['change_type', '=', $data['change_type']];
        }
        if (isset($data['change_mode']) && $data['change_mode'] != '') {
            $condition[] = ['change_mode', '=', $data['change_mode']];
        }

        $result = (new TechnicianBalanceLogDao())->getTechnicianBalanceLogPages($condition);

        return $result;
    }

    /**
     * 修改师傅余额
     * @param array $data 修改数据
     */
    function modifyTechnicianBalance(array $data)
    {
        $add_data = array(
            'technician_id' => $data['technician_id'],
            'related_id' => 0,
            'change_type' => TechnicianBalanceEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_amount' => $data['change_amount'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangTechnicianBalanceService();
            $deshangService->modifyTechnicianBalance($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
} 