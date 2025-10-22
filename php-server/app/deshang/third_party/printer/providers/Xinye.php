<?php

namespace app\deshang\third_party\printer\providers;
use app\deshang\core\InternalResultHelper;
use app\deshang\exceptions\CommonException;


use GuzzleHttp\Client;

/**
 * 新业打印机驱动
 */
class Xinye extends BasePrinter
{
    private $client;
    private $apiUrl = 'http://www.xpyun.net/api/openapi';

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
     */
    public function print(array $printerInfo, array $printerData, string $template = 'default'): array
    {
        throw new CommonException('打印异常：打印机不支持打印');
    }

    /**
     * 查询打印机状态
     */
    public function getPrinterStatus(array $printerInfo): array
    {
        throw new CommonException('打印异常：打印机不支持打印');
    }

    /**
     * 添加打印机
     */
    public function addPrinter(array $printerInfo): array
    {
        throw new CommonException('打印异常：打印机不支持打印');
    }

    /**
     * 删除打印机
     */
    public function deletePrinter(array $printerInfo): array
    {
        throw new CommonException('打印异常：打印机不支持打印');
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
        $content = "<CB>{$data['store_name']}</CB><BR>";
        $content .= "<B>订单号：{$data['order_sn']}</B><BR>";
        $content .= "下单时间：{$data['create_time']}<BR>";
        $content .= "--------------------------------<BR>";
        
        foreach ($data['goods_list'] as $goods) {
            $content .= "{$goods['goods_name']} x{$goods['goods_num']}<BR>";
            $content .= "  ￥{$goods['goods_price']}<BR>";
        }
        
        $content .= "--------------------------------<BR>";
        $content .= "商品总额：￥{$data['goods_amount']}<BR>";
        $content .= "配送费：￥{$data['shipping_amount']}<BR>";
        $content .= "<B>订单总额：￥{$data['order_amount']}</B><BR>";
        $content .= "--------------------------------<BR>";
        $content .= "收货地址：{$data['address']}<BR>";
        $content .= "联系电话：{$data['phone']}<BR>";
        $content .= "--------------------------------<BR>";
        $content .= "店铺ID：{$data['store_id']}<BR>";
        $content .= "<QR>https://example.com/order/{$data['order_sn']}</QR><BR>";
        
        return $content;
    }

    /**
     * 格式化收据内容
     */
    private function formatReceiptContent(array $data): string
    {
        $content = "<CB>收据</CB><BR>";
        $content .= "时间：{$data['create_time']}<BR>";
        $content .= "金额：￥{$data['amount']}<BR>";
        $content .= "--------------------------------<BR>";
        
        return $content;
    }

    /**
     * 格式化默认内容
     */
    private function formatDefaultContent(array $data): string
    {
        $content = "<CB>打印测试</CB><BR>";
        $content .= "时间：" . date('Y-m-d H:i:s') . "<BR>";
        $content .= "内容：{$data['content']}<BR>";
        $content .= "--------------------------------<BR>";
        
        return $content;
    }
} 