<?php

namespace app\adminapi\controller\tblStore;

use app\deshang\base\controller\BaseAdminController;
use app\adminapi\service\store\TblStorePrinterService;

/**
 * @OA\Tag(name="admin-api/tblStore/TblStorePrinter", description="店铺打印机管理接口")
 */
class TblStorePrinter extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/printer/pages",
     *     summary="获取店铺打印机分页列表",
     *     description="管理员查看所有店铺的打印机分页列表",
     *     tags={"admin-api/tblStore/TblStorePrinter"},
     *     @OA\Parameter(
     *         name="store_name",
     *         in="query",
     *         required=false,
     *         description="店铺名称搜索",
     *         @OA\Schema(type="string", example="官方自营手机店铺")
     *     ),
     *     @OA\Parameter(
     *         name="printer_name",
     *         in="query",
     *         required=false,
     *         description="打印机名称搜索",
     *         @OA\Schema(type="string", example="收银台打印机")
     *     ),
     *     @OA\Parameter(
     *         name="printer_provider",
     *         in="query",
     *         required=false,
     *         description="打印机服务商",
     *         @OA\Schema(type="string", example="yilian")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="页码",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         required=false,
     *         description="每页数量",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTblStorePrinterPages()
    {
        $data = array(
            'store_name' => input('param.store_name', ''),
            'printer_name' => input('param.printer_name', ''),
            'printer_provider' => input('param.printer_provider', ''),
        );
        
        // 验证器
        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStorePrinterValidate.pages');
        
        $result = (new TblStorePrinterService())->getTblStorePrinterPages($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/printer/info/{id}",
     *     summary="获取店铺打印机详情",
     *     description="管理员查看指定打印机的详细信息",
     *     tags={"admin-api/tblStore/TblStorePrinter"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="打印机ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTblStorePrinterInfo($id)
    {
        // 验证器
        $this->validate(['id' => $id], 'app\adminapi\controller\tblStore\validate\TblStorePrinterValidate.info');
        
        $result = (new TblStorePrinterService())->getTblStorePrinterInfo((int)$id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/tbl-store/printer/log/pages",
     *     summary="获取店铺打印机日志分页列表",
     *     description="管理员查看所有店铺的打印机日志分页列表",
     *     tags={"admin-api/tblStore/TblStorePrinter"},
     *     @OA\Parameter(
     *         name="store_name",
     *         in="query",
     *         required=false,
     *         description="店铺名称搜索",
     *         @OA\Schema(type="string", example="官方自营手机店铺")
     *     ),
     *     @OA\Parameter(
     *         name="printer_name",
     *         in="query",
     *         required=false,
     *         description="打印机名称搜索",
     *         @OA\Schema(type="string", example="收银台打印机")
     *     ),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         required=false,
     *         description="订单ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="print_status",
     *         in="query",
     *         required=false,
     *         description="打印状态：0失败 1成功",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="页码",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page_size",
     *         in="query",
     *         required=false,
     *         description="每页数量",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function getTblStorePrinterLogPages()
    {
        $data = array(
            'store_name' => input('param.store_name', ''),
            'order_id' => input('param.order_id', ''),
            'print_status' => input('param.print_status', ''),
        );
        
        // 验证器
        $this->validate($data, 'app\adminapi\controller\tblStore\validate\TblStorePrinterValidate.logs');
        
        $result = (new TblStorePrinterService())->getTblStorePrinterLogPages($data);
        return ds_json_success('操作成功', $result);
    }
} 