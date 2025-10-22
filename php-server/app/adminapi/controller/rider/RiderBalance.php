<?php

namespace app\adminapi\controller\rider;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\rider\RiderBalanceService;

class RiderBalance extends BaseAdminController
{

    public function getRiderBalanceLogPages(){

        $data = array(
            'rider_id' => input('param.rider_id'),
            'change_type' => input('param.change_type'),
            'change_mode' => input('param.change_mode'),
        );
        $list = (new RiderBalanceService())->getRiderBalanceLogPages($data);
        return ds_json_success('操作成功',$list);

    }


    //修改骑手余额
    public function modifyRiderBalance(){
        $data = array(
            'rider_id' => input('param.rider_id'),
            'change_mode' => input('param.change_mode'),
            'change_amount' => number_format(input('param.change_amount'), 2, '.', ''),
        );

        $this->validate($data, 'app\adminapi\controller\rider\validate\RiderBalance.modify');

        (new RiderBalanceService())->modifyRiderBalance($data);

        return ds_json_success('操作成功');
    }

}