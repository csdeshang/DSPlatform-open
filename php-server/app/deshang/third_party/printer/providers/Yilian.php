<?php

namespace app\deshang\third_party\printer\providers;

use app\deshang\core\InternalResultHelper;
use app\deshang\exceptions\CommonException;
use GuzzleHttp\Client;

/**
 * 易联云打印机驱动
 * https://www.kancloud.cn/ly6886/oauth-api/3170322
 */
class Yilian extends BasePrinter
{
    private $client;
    private $apiUrl = 'https://open-api.10ss.net/v2';

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->client = new Client([
            'timeout' => 30,
            'connect_timeout' => 10
        ]);
    }

    /**
     * 打印小票
     * https://www.kancloud.cn/ly6886/oauth-api/3170322
     */
    public function print(array $printerInfo, array $printerData, string $template = 'default'): array
    {
        try {
            $content = $this->formatContent($printerData, $template);
            $timestamp = time();

            // 生成UUIDv4格式的id
            $id = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff) & 0x0fff | 0x4000,
                mt_rand(0, 0x3fff) & 0x3fff | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );

            // 按照client_id、timestamp、client_secret排列方式拼接
            $signStr = $this->config['client_id'] . $timestamp . $this->config['client_secret'];
            $sign = md5($signStr);

            $params = [
                'client_id' => $this->config['client_id'],
                'sign' => $sign,
                'timestamp' => $timestamp,
                'id' => $id,
                'access_token' => $this->config['access_token'],
                'machine_code' => $printerInfo['printer_sn'],
                'content' => $content,
                'origin_id' => uniqid()
            ];

            $response = $this->client->post($this->apiUrl . '/print/index', [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($result['error'] == 0){
                return InternalResultHelper::success('打印成功', $result);
            }else{
                throw new CommonException($result['error_description']);
            }
            
        } catch (\Exception $e) {
            throw new CommonException('打印异常：' . $e->getMessage());
        }
    }

    /**
     * 查询打印机状态
     * https://www.kancloud.cn/ly6886/oauth-api/3170312
     */
    public function getPrinterStatus(array $printerInfo): array
    {
        try {
            $timestamp = time();

            // 生成UUIDv4格式的id
            $id = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff) & 0x0fff | 0x4000,
                mt_rand(0, 0x3fff) & 0x3fff | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );

            // 按照client_id、timestamp、client_secret排列方式拼接
            $signStr = $this->config['client_id'] . $timestamp . $this->config['client_secret'];
            $sign = md5($signStr);

            $params = [
                'client_id' => $this->config['client_id'],
                'sign' => $sign,
                'timestamp' => $timestamp,
                'id' => $id,
                'access_token' => $this->config['access_token'],
                'machine_code' => $printerInfo['printer_sn']
            ];

            $response = $this->client->post($this->apiUrl . '/printer/getprintstatus', [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($result['error'] == 0){
                $message = '未知状态';

                // 统一返回格式 在线 离线   其他服务商固定 返回格式为字符串
                // 0=离线 1=在线 2=缺纸
                switch($result['body']['state']){
                    case 0:
                        $message = '离线';
                        break;
                    case 1:
                        $message = '在线';
                        break;
                    case 2:
                        $message = '缺纸';
                        break;
                    default:
                        $message = '未知状态';
                }

                return InternalResultHelper::success('查询状态成功', array('message' => $message, 'raw_data' => $result['body']));
            }else{
                throw new CommonException($result['error_description']);
            }
            
        } catch (\Exception $e) {
            throw new CommonException('查询状态异常：' . $e->getMessage());
        }
    }

    /**
     * 添加打印机
     * https://www.kancloud.cn/ly6886/oauth-api/3170310
     */
    public function addPrinter(array $printerInfo): array
    {
        try {
            $timestamp = time();

            // 生成UUIDv4格式的id
            $id = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff) & 0x0fff | 0x4000,
                mt_rand(0, 0x3fff) & 0x3fff | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );

            // 按照client_id、timestamp、client_secret排列方式拼接
            $signStr = $this->config['client_id'] . $timestamp . $this->config['client_secret'];
            $sign = md5($signStr);

            $params = [
                'client_id' => $this->config['client_id'],
                'sign' => $sign,
                'timestamp' => $timestamp,
                'id' => $id,
                'access_token' => $this->config['access_token'],
                'machine_code' => $printerInfo['printer_sn'],
                'msign' => $printerInfo['printer_key'],
                'phone' => $printerInfo['phone'] ?? '',
                'print_name' => $printerInfo['printer_name'] ?? '店铺打印机',
            ];

            $response = $this->client->post($this->apiUrl . '/printer/addprinter', [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if($result['error'] == 0){
                return InternalResultHelper::success('添加打印机成功', $result);
            }else{
                throw new CommonException($result['error_description']);
            }
            
        } catch (\Exception $e) {
            throw new CommonException('添加打印机异常：' . $e->getMessage());
        }
    }

    /**
     * 删除打印机
     * https://www.kancloud.cn/ly6886/oauth-api/3170311
     */
    public function deletePrinter(array $printerInfo): array
    {
        try {
            $timestamp = time();

            // 生成UUIDv4格式的id
            $id = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff) & 0x0fff | 0x4000,
                mt_rand(0, 0x3fff) & 0x3fff | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );

            // 按照client_id、timestamp、client_secret排列方式拼接
            $signStr = $this->config['client_id'] . $timestamp . $this->config['client_secret'];
            $sign = md5($signStr);

            $params = [
                'client_id' => $this->config['client_id'],
                'sign' => $sign,
                'timestamp' => $timestamp,
                'id' => $id,
                'access_token' => $this->config['access_token'],
                'machine_code' => $printerInfo['printer_sn']
            ];

            $response = $this->client->post($this->apiUrl . '/printer/deleteprinter', [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);


            if($result['error'] == 0){
                return InternalResultHelper::success('解绑打印机成功', $result);
            }else{
                throw new CommonException($result['error_description']);
            }
            
        } catch (\Exception $e) {
            throw new CommonException('解绑打印机异常：' . $e->getMessage());
        }
    }

    /**
     * 格式化打印内容
     */
    private function formatContent(array $data, string $template): string
    {
        switch ($template) {
            case 'order':
                return $this->formatOrderContent($data);
            case 'receipt':
                return $this->formatReceiptContent($data);
            default:
                return $this->formatDefaultContent($data);
        }
    }

    /**
     * 格式化订单内容
     */
    private function formatOrderContent(array $data): string
    {
        $orderInfo = $data['order_info'];
        $orderGoods = $data['order_goods'];
        $orderAddress = $data['order_address'];
        $storeInfo = $data['store_info'];

        $content = "<FS2>顾客联</FS2>\n";
        $content .= "<FS><center>{$storeInfo['store_name']}</center></FS>\n";
        $content .= "********************************\n";
        $content .= "<FS>预计送达时间: 立即送达</FS>\n";
        $content .= "下单时间: " . date('m-d H:i:s', strtotime($orderInfo['add_time'])) . "\n";
        $content .= "订单号: {$orderInfo['order_sn']}\n";
        $content .= "--------------------------------\n";

        // 备注信息
        if (!empty($orderInfo['user_remark'])) {
            $content .= "<FS2>备注: [{$orderInfo['user_remark']}]</FS2>\n";
        }

        $content .= "**************商品**************\n";
        foreach ($orderGoods as $goods) {
            $content .= "<FS><table><tr><td>{$goods['goods_name']}</td><td>x{$goods['goods_num']}</td><td>￥" . number_format($goods['pay_price'] / 100, 1) . "</td></tr></table></FS>\n";
        }
        $content .= "--------------------------------\n";

        // 优惠信息
        if ($orderInfo['discount_amount'] > 0) {
            $content .= "优惠金额: " . number_format($orderInfo['discount_amount'] / 100, 1) . "\n";
        }

        $content .= "配送费: " . number_format($orderInfo['shipping_amount'] / 100, 1) . "\n";
        $content .= "总原价: " . number_format($orderInfo['original_amount'] / 100, 1) . "\n";
        $content .= "<FS2>实际支付: " . number_format($orderInfo['order_amount'] / 100, 1) . "</FS2>\n";
        $content .= "--------------------------------\n";

        $content .= "<FS>顾客姓名: {$orderAddress['reciver_name']}(**)</FS>\n";
        $content .= "<FS>顾客电话: {$orderAddress['reciver_mobile']}</FS>\n";
        $content .= "<FS>顾客地址: {$orderAddress['reciver_address']}</FS>\n";
        $content .= "********************************\n";
        $content .= "<FS2><center>-- 完 --</center></FS2>\n";

        return $content;
    }

    /**
     * 格式化收据内容
     */
    private function formatReceiptContent(array $data): string
    {
        $content = "<FS2>收据</FS2>\n";
        $content .= "<FS><center>收据凭证</center></FS>\n";
        $content .= "********************************\n";
        $content .= "<FS>时间: {$data['create_time']}</FS>\n";
        $content .= "<FS2>金额: ￥{$data['amount']}</FS2>\n";
        $content .= "********************************\n";
        $content .= "<FS2><center>-- 完 --</center></FS2>";

        return $content;
    }

    /**
     * 格式化默认内容
     */
    private function formatDefaultContent(array $data): string
    {
        $content = "<FS2>打印测试</FS2>\n";
        $content .= "<FS><center>测试凭证</center></FS>\n";
        $content .= "********************************\n";
        $content .= "<FS>时间: " . date('Y-m-d H:i:s') . "</FS>\n";
        $content .= "<FS>内容: {$data['content']}</FS>\n";
        $content .= "********************************\n";
        $content .= "<FS2><center>-- 完 --</center></FS2>";

        return $content;
    }



    /**
     * 获取access_token
     * @return array
     * https://www.kancloud.cn/ly6886/oauth-api/3170302
     *  开放型应用
     * 
     */

    //  {
    //     "error": 0,
    //     "error_description": "success",
    //     "timestamp": 1753702270,
    //     "body": {
    //       "client_id": 1061419147,
    //       "access_token": "98b3e2fe9c0147d2ae3b938fe5c5b2eb61e9c4d4",
    //       "refresh_token": "fc09250d2976410898e76d1eeed85fd5f099ff76",
    //       "machine_code": "",
    //       "expires_in": 2592000,
    //       "refresh_expires_in": 3024000,
    //       "scope": "all"
    //     }
    //   }
    public function getAccessToken(): array
    {
        try {
            $timestamp = time();

            // 生成UUIDv4格式的id
            $id = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff) & 0x0fff | 0x4000,
                mt_rand(0, 0x3fff) & 0x3fff | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );


            // 按照client_id、timestamp、client_secret排列方式拼接
            $signStr = $this->config['client_id'] . $timestamp . $this->config['client_secret'];
            $sign = md5($signStr);

            $params = [
                'client_id' => $this->config['client_id'],
                'grant_type' => 'client_credentials',
                'sign' => $sign,
                'scope' => 'all',
                'timestamp' => $timestamp,
                'id' => $id,
            ];

            $response = $this->client->post($this->apiUrl . '/oauth/oauth', [
                'form_params' => $params,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);
            $result = json_decode($response->getBody()->getContents(), true);


            if ($result['error'] == 0) {
                $data = array(
                    'access_token' => $result['body']['access_token'],
                    'refresh_token' => $result['body']['refresh_token'],
                    // 提前5分钟过期
                    'token_expire_time' => $result['body']['expires_in'] + time() - 300,
                );
                return InternalResultHelper::success('获取token成功', $data);
            }else{
                throw new CommonException($result['error_description']);
            }

        } catch (\Exception $e) {
            throw new CommonException('获取token异常：' . $e->getMessage());
        }
    }
}
