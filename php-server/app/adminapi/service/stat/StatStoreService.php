<?php

namespace app\adminapi\service\stat;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\store\TblStoreDao;

use app\common\enum\store\TblStoreEnum;



class StatStoreService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblStoreDao();
    }

    public function getStatStoreOverview(array $data)
    {

        $platform = $data['platform'] ?? '';


        $result = [];

        // 总新增订单数
        $result['new_store']['total'] = $this->dao->getStoreCount([['platform', '=', $platform]]);
        // 今日新增
        $result['new_store']['today'] = $this->dao->getStoreCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_store']['yesterday'] = $this->dao->getStoreCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_store']['week'] = $this->dao->getStoreCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_store']['month'] = $this->dao->getStoreCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);



        // 店铺状态
        // 待审核
        $result['apply_status']['wait'] = $this->dao->getStoreCount([
            ['platform', '=', $platform],
            ['apply_status', '=', TblStoreEnum::APPLY_STATUS_WAIT]
        ]);








        return $result;
    }
}
