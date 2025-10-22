<?php

namespace app\common\enum\user;

/**
 * 用户操作行为相关枚举
 */
class UserBehaviorEnum
{
    
    // 行为类型
    const TYPE_LOGIN_NORMAL = 'login_normal'; // 普通登录
    const TYPE_LOGIN_MOBILE = 'login_mobile'; // 手机登录
    const TYPE_LOGIN_WECHAT = 'login_wechat'; // 微信登录
    const TYPE_REGISTER_NORMAL = 'register_normal'; // 普通注册
    const TYPE_REGISTER_MOBILE = 'register_mobile'; // 手机注册
    const TYPE_PASSWORD_RESET = 'password_reset'; // 密码重置

    /**
     * 获取行为类型列表
     */
    public static function getBehaviorTypeDict(): array
    {
        return [
            self::TYPE_LOGIN_NORMAL => '普通登录',
            self::TYPE_LOGIN_MOBILE => '手机登录',
            self::TYPE_LOGIN_WECHAT => '微信登录',
            // self::TYPE_REGISTER_NORMAL => '普通注册',
            // self::TYPE_REGISTER_MOBILE => '手机注册',
            // self::TYPE_PASSWORD_RESET => '密码重置',
        ];
    }

    /**
     * 获取行为类型描述
     */
    public static function getBehaviorTypeDesc($value): string
    {
        $data = self::getBehaviorTypeDict();
        return $data[$value] ?? '未知类型';
    }



    // 行为状态
    const STATUS_SUCCESS = 1; // 成功
    const STATUS_FAILED = 0; // 失败

    /**
     * 获取行为状态列表
     */
    public static function getBehaviorStatusDict(): array
    {
        return [
            self::STATUS_SUCCESS => '成功',
            self::STATUS_FAILED => '失败',
        ];
    }

    /**
     * 获取行为状态描述
     */
    public static function getBehaviorStatusDesc($value): string
    {
        $data = self::getBehaviorStatusDict();
        return $data[$value] ?? '未知状态';
    }



    // 风险等级
    const RISK_NONE = 0; // 无风险
    const RISK_LOW = 1; // 低风险
    const RISK_MEDIUM = 2; // 中风险
    const RISK_HIGH = 3; // 高风险

    /**
     * 获取风险等级列表
     */
    public static function getRiskLevelDict(): array
    {
        return [
            self::RISK_NONE => '无风险',
            self::RISK_LOW => '低风险',
            self::RISK_MEDIUM => '中风险',
            self::RISK_HIGH => '高风险',
        ];
    }

    /**
     * 获取风险等级描述
     */
    public static function getRiskLevelDesc($value): string
    {
        $data = self::getRiskLevelDict();
        return $data[$value] ?? '未知风险';
    }



    // 异常状态
    const ABNORMAL_NO = 0; // 正常
    const ABNORMAL_YES = 1; // 异常

    /**
     * 获取异常状态列表
     */
    public static function getAbnormalDict(): array
    {
        return [
            self::ABNORMAL_NO => '正常',
            self::ABNORMAL_YES => '异常',
        ];
    }

    /**
     * 获取异常状态描述
     */
    public static function getAbnormalDesc($value): string
    {
        $data = self::getAbnormalDict();
        return $data[$value] ?? '未知状态';
    }
}
