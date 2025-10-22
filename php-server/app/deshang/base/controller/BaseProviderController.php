<?php

namespace app\deshang\base\controller;


/**
 * 服务商配置基础控制器
 * 提供处理各种服务商配置的通用方法
 */
abstract class BaseProviderController extends BaseAdminController
{
    /**
     * 获取配置前缀，如 'sms_config_' 或 'storage_config_'
     * @return string
     */
    abstract protected function getConfigPrefix(): string;

    /**
     * 获取默认配置键名，如 'sms_default_provider' 或 'storage_default_provider'
     * @return string
     */
    abstract protected function getDefaultConfigKey(): string;

    /**
     * 获取服务商枚举列表
     * @return array
     */
    abstract protected function getProviderList(): array;

}