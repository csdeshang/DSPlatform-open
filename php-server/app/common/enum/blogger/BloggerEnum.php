<?php

namespace app\common\enum\blogger;

class BloggerEnum
{


    // 认证状态
    const VERIFICATION_STATUS_WAIT = 0; // 待认证
    const VERIFICATION_STATUS_PASS = 1; // 认证通过
    const VERIFICATION_STATUS_REFUSE = 2; // 认证拒绝

    /**
     * 获取博主认证状态列表
     * @return array
     */
    public static function getVerificationStatusDict(): array
    {
        return [
            self::VERIFICATION_STATUS_WAIT => '待认证',
            self::VERIFICATION_STATUS_PASS => '认证通过',
            self::VERIFICATION_STATUS_REFUSE => '认证拒绝',
        ];
    }

    /**
     * 获取博主认证状态描述
     * @param string $value
     * @return string
     */
    public static function getVerificationStatusDesc($value): string
    {
        $data = self::getVerificationStatusDict();
        return $data[$value] ?? '未知状态';
    }
    


    // 认证类型
    const VERIFICATION_TYPE_PERSONAL = 1; // 个人认证
    const VERIFICATION_TYPE_ENTERPRISE = 2; // 企业认证

    /**
     * 获取博主认证类型列表
     * @return array
     */
    public static function getVerificationTypeDict(): array
    {
        return [
            self::VERIFICATION_TYPE_PERSONAL => '个人认证',
            self::VERIFICATION_TYPE_ENTERPRISE => '企业认证',
        ];
    }

    /**
     * 获取博主认证类型描述
     * @param string $value
     * @return string
     */
    public static function getVerificationTypeDesc($value): string
    {
        $data = self::getVerificationTypeDict();
        return $data[$value] ?? '未知类型';
    }



}
