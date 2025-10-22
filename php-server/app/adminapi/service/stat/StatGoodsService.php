<?php

namespace app\adminapi\service\stat;
use app\deshang\base\service\BaseAdminService;
use app\common\dao\goods\TblGoodsDao;

use app\common\enum\goods\TblGoodsEnum;



class StatGoodsService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TblGoodsDao();
    }

    public function getStatGoodsOverview(array $data):array
    {
        $result = [];

        $platform = $data['platform'] ?? '';

        // 总新增商品数
        $result['new_goods']['total'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform]
        ]);
        // 今日新增
        $result['new_goods']['today'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_goods']['yesterday'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_goods']['week'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_goods']['month'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);        



        // 待审核
        $result['sys_status']['wait'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['sys_status', '=', TblGoodsEnum::SYS_STATUS_PENDING_REVIEW]
        ]);

        // 审核失败
        $result['sys_status']['failed'] = $this->dao->getGoodsCount([
            ['platform', '=', $platform],
            ['sys_status', '=', TblGoodsEnum::SYS_STATUS_FAILED]
        ]);
        


        return $result;
    }



}