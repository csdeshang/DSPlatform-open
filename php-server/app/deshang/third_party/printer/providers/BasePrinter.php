<?php

namespace app\deshang\third_party\printer\providers;

/**
 * 小票打印机驱动接口
 */
abstract class BasePrinter
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 打印小票
     * @param array $printerInfo 打印机信息
     * @param array $printerData 打印数据
     * @param string $template 模板名称
     * @return array 打印结果
     */
    abstract public function print(array $printerInfo, array $printerData, string $template = 'default'): array;

    /**
     * 查询打印机状态
     * @param array $printerInfo 打印机信息
     * @return array 状态信息
     */
    abstract public function getPrinterStatus(array $printerInfo): array;

    /**
     * 添加打印机
     * @param array $printerInfo 打印机信息
     * @return array 添加结果
     */
    abstract public function addPrinter(array $printerInfo): array;

    /**
     * 删除打印机
     * @param array $printerInfo 打印机信息
     * @return array 删除结果
     */
    abstract public function deletePrinter(array $printerInfo): array;
}