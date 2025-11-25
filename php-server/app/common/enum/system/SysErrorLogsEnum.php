<?php

namespace app\common\enum\system;

/**
 * 系统错误日志枚举定义  对应表：sys_error_logs
 *
 * 参考字段（deshang.sql 905-933）：
 * - exception_class: varchar(32) 异常类名（短类名，不含命名空间）
 */
class SysErrorLogsEnum
{
    // 异常类（exception_class 字段）
    // 自定义业务异常类
    const EXCEPTION_CLASS_COMMON = 'CommonException';           // 通用业务异常
    const EXCEPTION_CLASS_AUTH = 'AuthException';               // 认证异常
    const EXCEPTION_CLASS_PERMISSION = 'PermissionException';   // 权限异常
    const EXCEPTION_CLASS_PAY = 'PayException';                 // 支付异常
    const EXCEPTION_CLASS_NOT_FOUND = 'NotFoundException';     // 资源未找到异常
    const EXCEPTION_CLASS_SYSTEM = 'SystemException';           // 系统异常
    const EXCEPTION_CLASS_STATE = 'StateException';             // 状态异常

    // ThinkPHP 框架异常类
    const EXCEPTION_CLASS_ERROR = 'ErrorException';             // 错误异常
    const EXCEPTION_CLASS_HTTP = 'HttpException';               // HTTP异常
    const EXCEPTION_CLASS_VALIDATE = 'ValidateException';       // 验证异常
    const EXCEPTION_CLASS_DATA_NOT_FOUND = 'DataNotFoundException';  // 数据未找到异常
    const EXCEPTION_CLASS_MODEL_NOT_FOUND = 'ModelNotFoundException'; // 模型未找到异常
    const EXCEPTION_CLASS_HTTP_RESPONSE = 'HttpResponseException';   // HTTP响应异常

    // 获取异常类字典
    public static function getExceptionClassDict(): array
    {
        return [
            // 自定义业务异常
            self::EXCEPTION_CLASS_COMMON => 'CommonException',
            self::EXCEPTION_CLASS_AUTH => 'AuthException',
            self::EXCEPTION_CLASS_PERMISSION => 'PermissionException',
            self::EXCEPTION_CLASS_PAY => 'PayException',
            self::EXCEPTION_CLASS_NOT_FOUND => 'NotFoundException',
            self::EXCEPTION_CLASS_SYSTEM => 'SystemException',
            self::EXCEPTION_CLASS_STATE => 'StateException',
            
            // ThinkPHP 框架异常
            self::EXCEPTION_CLASS_ERROR => 'ErrorException',
            self::EXCEPTION_CLASS_HTTP => 'HttpException',
            self::EXCEPTION_CLASS_VALIDATE => 'ValidateException',
            self::EXCEPTION_CLASS_DATA_NOT_FOUND => 'DataNotFoundException',
            self::EXCEPTION_CLASS_MODEL_NOT_FOUND => 'ModelNotFoundException',
            self::EXCEPTION_CLASS_HTTP_RESPONSE => 'HttpResponseException',
        ];
    }

    // 获取异常类描述
    public static function getExceptionClassDesc($value): string
    {
        $data = self::getExceptionClassDict();
        return $data[$value] ?? '未知异常类型';
    }


}

