<?php

namespace app\deshang\third_party\printer\providers;
use app\deshang\core\InternalResultHelper;
use app\deshang\exceptions\CommonException;

use GuzzleHttp\Client;

/**
 * 飞鹅打印机驱动
 * https://help.feieyun.com/home/doc/zh;nav=1-0
 */
class Feie extends BasePrinter
{
    private $client;
    private $apiUrl = 'http://api.feieyun.cn/Api/Open/';

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5
        ]);
    }

    /**
     * 打印小票
     * https://help.feieyun.com/home/doc/zh;nav=1-1
     * 
     * @param array $printerInfo 打印机信息
     * @param array $printerData 打印数据
     * @param string $template 模板类型
     * @return array
     * @throws CommonException
     */
    public function print(array $printerInfo, array $printerData, string $template = 'default'): array
    {
        try {
            // 获取配置信息
            $user = $this->config['user'] ?? '';
            $ukey = $this->config['ukey'] ?? '';
            $printerSn = $printerInfo['printer_sn'] ?? '';

            // 验证必要参数
            if (empty($user) || empty($ukey)) {
                throw new CommonException('飞鹅打印机配置信息不完整');
            }
            if (empty($printerSn)) {
                throw new CommonException('打印机序列号不能为空');
            }

            // 格式化打印内容
            $content = $this->formatContent($printerData, $template);

            // 构建请求参数
            $params = [
                'user' => $user,
                'stime' => time(),
                'sig' => $this->generateSignature($user, $ukey),
                'apiname' => 'Open_printMsg',
                'sn' => $printerSn,
                'content' => $content,
                'times' => 1
            ];

            // 发送请求
            $response = $this->client->post($this->apiUrl, [
                'form_params' => $params
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // 检查响应
            if (isset($result['ret']) && $result['ret'] == 0) {
                return InternalResultHelper::success('打印成功', $result['data'] ?? []);
            } else {
                $errorMsg = $result['msg'] ?? '打印失败';
                throw new CommonException('飞鹅打印机打印失败：' . $errorMsg);
            }

        } catch (\Exception $e) {
            if ($e instanceof CommonException) {
                throw $e;
            }
            throw new CommonException('飞鹅打印机打印异常：' . $e->getMessage());
        }
    }

    /**
     * 查询打印机状态
     * https://help.feieyun.com/home/doc/zh;nav=1-10
     * 
     * @param array $printerInfo 打印机信息
     * @return array
     * @throws CommonException
     */
    public function getPrinterStatus(array $printerInfo): array
    {
        try {
            // 获取配置信息
            $user = $this->config['user'] ?? '';
            $ukey = $this->config['ukey'] ?? '';
            $printerSn = $printerInfo['printer_sn'] ?? '';

            // 验证必要参数
            if (empty($user) || empty($ukey)) {
                throw new CommonException('飞鹅打印机配置信息不完整');
            }
            if (empty($printerSn)) {
                throw new CommonException('打印机序列号不能为空');
            }

            // 构建请求参数
            $params = [
                'user' => $user,
                'stime' => time(),
                'sig' => $this->generateSignature($user, $ukey),
                'apiname' => 'Open_queryPrinterStatus',
                'sn' => $printerSn
            ];

            // 发送请求
            $response = $this->client->post($this->apiUrl, [
                'form_params' => $params
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // 检查响应
            if (isset($result['ret']) && $result['ret'] == 0) {
                $status = $result['data'] ?? '';
                // 统一返回格式 - 只返回状态文字
                switch($status) {
                    case '离线':
                        return InternalResultHelper::success('查询状态成功', '离线');
                    case '在线':
                        return InternalResultHelper::success('查询状态成功', '在线');
                    case '缺纸':
                        return InternalResultHelper::success('查询状态成功', '缺纸');
                    default:
                        return InternalResultHelper::success('查询状态成功', '离线');
                }
            } else {
                $errorMsg = $result['msg'] ?? '查询状态失败';
                throw new CommonException('飞鹅打印机查询状态失败：' . $errorMsg);
            }

        } catch (\Exception $e) {
            if ($e instanceof CommonException) {
                throw $e;
            }
            throw new CommonException('飞鹅打印机查询状态异常：' . $e->getMessage());
        }
    }

    /**
     * 添加打印机
     * https://help.feieyun.com/home/doc/zh;nav=1-0
     * 
     * @param array $printerInfo 打印机信息
     * @return array
     * @throws CommonException
     */
    public function addPrinter(array $printerInfo): array
    {
        try {
            // 获取配置信息
            $user = $this->config['user'] ?? '';
            $ukey = $this->config['ukey'] ?? '';
            $printerSn = $printerInfo['printer_sn'] ?? '';
            $printerKey = $printerInfo['printer_key'] ?? '';
            $printerName = $printerInfo['printer_name'] ?? '';

            // 验证必要参数
            if (empty($user) || empty($ukey)) {
                throw new CommonException('飞鹅打印机配置信息不完整');
            }
            if (empty($printerSn) || empty($printerKey)) {
                throw new CommonException('打印机序列号和密钥不能为空');
            }

            // 构建请求参数
            $params = [
                'user' => $user,
                'stime' => time(),
                'sig' => $this->generateSignature($user, $ukey),
                'apiname' => 'Open_printerAddlist',
                'printerContent' => $printerSn . '#' . $printerKey . '#' . $printerName
            ];

            // 发送请求
            $response = $this->client->post($this->apiUrl, [
                'form_params' => $params
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // 检查响应
            if (isset($result['ret']) && $result['ret'] == 0) {
                return InternalResultHelper::success('添加打印机成功', $result['data'] ?? []);
            } else {
                $errorMsg = $result['msg'] ?? '添加打印机失败';
                throw new CommonException('飞鹅打印机添加失败：' . $errorMsg);
            }

        } catch (\Exception $e) {
            if ($e instanceof CommonException) {
                throw $e;
            }
            throw new CommonException('飞鹅打印机添加异常：' . $e->getMessage());
        }
    }

    /**
     * 生成签名
     * 
     * @param string $user 用户名
     * @param string $ukey 用户密钥
     * @return string
     */
    private function generateSignature(string $user, string $ukey): string
    {
        $stime = time();
        return sha1($user . $ukey . $stime);
    }

    /**
     * 删除打印机
     * https://help.feieyun.com/home/doc/zh;nav=1-3
     * 
     * @param array $printerInfo 打印机信息
     * @return array
     * @throws CommonException
     */
    public function deletePrinter(array $printerInfo): array
    {
        try {
            // 获取配置信息
            $user = $this->config['user'] ?? '';
            $ukey = $this->config['ukey'] ?? '';
            $printerSn = $printerInfo['printer_sn'] ?? '';

            // 验证必要参数
            if (empty($user) || empty($ukey)) {
                throw new CommonException('飞鹅打印机配置信息不完整');
            }
            if (empty($printerSn)) {
                throw new CommonException('打印机序列号不能为空');
            }

            // 构建请求参数
            $params = [
                'user' => $user,
                'stime' => time(),
                'sig' => $this->generateSignature($user, $ukey),
                'apiname' => 'Open_printerDelList',
                'snlist' => $printerSn
            ];

            // 发送请求
            $response = $this->client->post($this->apiUrl, [
                'form_params' => $params
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            // 检查响应
            if (isset($result['ret']) && $result['ret'] == 0) {
                return InternalResultHelper::success('删除打印机成功', $result['data'] ?? []);
            } else {
                $errorMsg = $result['msg'] ?? '删除打印机失败';
                throw new CommonException('飞鹅打印机删除失败：' . $errorMsg);
            }

        } catch (\Exception $e) {
            if ($e instanceof CommonException) {
                throw $e;
            }
            throw new CommonException('飞鹅打印机删除异常：' . $e->getMessage());
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
     * 格式化订单小票内容
     */
    private function formatOrderContent(array $data): string
    {
        $content = "<CB>{$data['store_name']}</CB><BR>";
        $content .= "订单号：{$data['order_sn']}<BR>";
        $content .= "时间：{$data['create_time']}<BR>";
        $content .= "--------------------------------<BR>";
        
        foreach ($data['goods_list'] as $goods) {
            $content .= "{$goods['goods_name']}<BR>";
            $content .= "  {$goods['goods_num']} x ￥{$goods['goods_price']}<BR>";
        }
        
        $content .= "--------------------------------<BR>";
        $content .= "商品总额：￥{$data['goods_amount']}<BR>";
        $content .= "运费：￥{$data['shipping_amount']}<BR>";
        $content .= "订单总额：￥{$data['order_amount']}<BR>";
        $content .= "--------------------------------<BR>";
        $content .= "收货地址：{$data['address']}<BR>";
        $content .= "联系电话：{$data['phone']}<BR>";
        $content .= "--------------------------------<BR>";
        $content .= "感谢您的惠顾！<BR>";
        
        return $content;
    }

    /**
     * 格式化收据内容
     */
    private function formatReceiptContent(array $data): string
    {
        $content = "<CB>收据</CB><BR>";
        $content .= "--------------------------------<BR>";
        $content .= "金额：￥{$data['amount']}<BR>";
        $content .= "时间：{$data['create_time']}<BR>";
        $content .= "--------------------------------<BR>";
        
        return $content;
    }

    /**
     * 格式化默认内容
     */
    private function formatDefaultContent(array $data): string
    {
        return $data['content'] ?? '';
    }
}