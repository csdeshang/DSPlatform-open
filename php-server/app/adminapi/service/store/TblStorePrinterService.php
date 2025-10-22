<?php

namespace app\adminapi\service\store;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\store\TblStorePrinterDao;
use app\common\dao\store\TblStorePrinterLogDao;

use app\deshang\exceptions\CommonException;
use app\deshang\utils\SearchHelper;

/**
 * 店铺打印机服务（管理员查看）
 */
class TblStorePrinterService extends BaseAdminService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取店铺打印机列表
     */
    public function getTblStorePrinterPages($data)
    {
        $condition = [];

        if (isset($data['store_name']) && $data['store_name'] != '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
        }

        if (isset($data['printer_name']) && $data['printer_name'] != '') {
            $condition[] = ['printer_name', 'like', '%' . $data['printer_name'] . '%'];
        }

        if (isset($data['printer_provider']) && $data['printer_provider'] != '') {
            $condition[] = ['printer_provider', '=', $data['printer_provider']];
        }

        $result = (new TblStorePrinterDao())->getWithRelStorePrinterPages($condition);

        return $result;
    }

    /**
     * 获取店铺打印机详情
     */
    public function getTblStorePrinterInfo($id)
    {
        $condition = [['id', '=', $id]];
        $printerInfo = (new TblStorePrinterDao())->getStorePrinterInfo($condition);


        return $printerInfo;
    }

    /**
     * 获取店铺打印机日志列表
     */
    public function getTblStorePrinterLogPages($data)
    {
        $condition = [];

        if (isset($data['store_name']) && $data['store_name'] != '') {
            $storeIds = SearchHelper::getStoreIdsByStoreName($data['store_name']);
            $condition[] = ['store_id', 'in', $storeIds];
        }

        if (isset($data['order_id']) && $data['order_id'] != '') {
            $condition[] = ['order_id', '=', $data['order_id']];
        }

        if (isset($data['print_status']) && $data['print_status'] !== '') {
            $condition[] = ['print_status', '=', $data['print_status']];
        }

        $result = (new TblStorePrinterLogDao())->getWithRelStorePrinterLogPages($condition);

        return $result;
    }
}
