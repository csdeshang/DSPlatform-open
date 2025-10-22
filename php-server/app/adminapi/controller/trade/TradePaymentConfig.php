<?php

namespace app\adminapi\controller\trade;

use app\deshang\base\controller\BaseAdminController;

use app\adminapi\service\trade\TradePaymentConfigService;

/**
 * @OA\Tag(name="admin-api/trade/TradePaymentConfig", description="支付配置管理接口")
 */
class TradePaymentConfig extends BaseAdminController
{

    /**
     * @OA\Get(
     *     path="/adminapi/trade/payment-config/merchant",
     *     summary="获取商户支付配置列表",
     *     tags={"admin-api/trade/TradePaymentConfig"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function getPaymentConfigByMerchant()
    {

        $result = (new TradePaymentConfigService())->getPaymentConfigByMerchant();
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Get(
     *     path="/adminapi/trade/payment-config/info/{id}",
     *     summary="获取单条支付配置",
     *     tags={"admin-api/trade/TradePaymentConfig"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="配置ID",
     *         @OA\Schema(type="integer")
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
    public function getPaymentConfigInfoById($id)
    {
        $result = (new TradePaymentConfigService())->getPaymentConfigInfoById($id);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/trade/payment-config/create",
     *     summary="创建支付配置",
     *     tags={"admin-api/trade/TradePaymentConfig"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="支付配置信息",
     *         @OA\JsonContent(
     *             required={"payment_channel", "payment_scene", "config_data"},
     *             @OA\Property(property="payment_channel", type="string", example="wechat"),
     *             @OA\Property(property="payment_scene", type="string", example="h5"),
     *             @OA\Property(property="config_data", type="object", example={}),
     *             @OA\Property(property="is_enabled", type="integer", example=1),
     *             @OA\Property(property="sort", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function createPaymentConfig()
    {
        $data = array(
            'payment_channel' => input('param.payment_channel'),
            'payment_scene' => input('param.payment_scene'),
            'config_data' => input('param.config_data/a'),
            'is_enabled' => input('param.is_enabled'),
            'sort' => input('param.sort'),
        );
        $result = (new TradePaymentConfigService())->createPaymentConfig($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Put(
     *     path="/adminapi/trade/payment-config/update/{id}",
     *     summary="更新支付配置",
     *     tags={"admin-api/trade/TradePaymentConfig"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="配置ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="支付配置信息",
     *         @OA\JsonContent(
     *             @OA\Property(property="payment_channel", type="string", example="wechat"),
     *             @OA\Property(property="payment_scene", type="string", example="h5"),
     *             @OA\Property(property="config_data", type="object", example={}),
     *             @OA\Property(property="is_enabled", type="integer", example=1),
     *             @OA\Property(property="sort", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function updatePaymentConfig($id)
    {
        $data = array(
            'id' => $id,
            'payment_channel' => input('param.payment_channel'),
            'payment_scene' => input('param.payment_scene'),
            'config_data' => input('param.config_data/a'),
            'is_enabled' => input('param.is_enabled'),
            'sort' => input('param.sort'),
        );
        $result = (new TradePaymentConfigService())->updatePaymentConfig($data);
        return ds_json_success('操作成功', $result);
    }

    /**
     * @OA\Delete(
     *     path="/adminapi/trade/payment-config/delete/{id}",
     *     summary="删除支付配置",
     *     tags={"admin-api/trade/TradePaymentConfig"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="配置ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功")
     *         )
     *     )
     * )
     */
    public function deletePaymentConfig($id)
    {
        $result = (new TradePaymentConfigService())->deletePaymentConfig($id);
        return ds_json_success('操作成功', $result);
    }
}
