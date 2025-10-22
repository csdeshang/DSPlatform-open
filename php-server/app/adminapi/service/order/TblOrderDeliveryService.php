<?php

namespace app\adminapi\service\order;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\order\TblOrderDeliveryDao;

use app\common\enum\order\TblOrderEnum;

use app\deshang\exceptions\CommonException;

use think\facade\Db;


class TblOrderDeliveryService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getTblOrderDeliveryPages(array $data): array
    {

        $condition = [];
        if (isset($data['rider_id']) && $data['rider_id'] != '') {
            $condition['rider_id'] = $data['rider_id'];
        }

        // 交付方式
        if (array_key_exists($data['delivery_method'], TblOrderEnum::getAllOrderDeliveryDict())) {
            $condition['delivery_method'] = $data['delivery_method'];
        }

        $result = (new TblOrderDeliveryDao())->getWithRelOrderDeliveryPages($condition);
        return $result;


    }
}