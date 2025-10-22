<?php

namespace app\deshang\third_party\express\providers;

// 快递查询驱动接口
abstract class BaseExpress
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * 查询快递信息
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码（部分快递公司需要）
     * @return array 查询结果
     */
    abstract public function query(string $express_no, string $express_code, string $phone = ''): array;
} 