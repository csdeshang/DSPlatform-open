<?php

namespace app\common\enum\video;

class VideoShortEnum
{
    // 审核状态状态
    const TYPE_VIDEO = 1; // 视频
    const TYPE_IMAGE = 2; // 图片


    /**
     * 获取视频类型列表
     * @return array
     */
    public static function getTypeDict(): array
    {
        return [
            self::TYPE_VIDEO => '视频',
            self::TYPE_IMAGE => '图片',

        ];
    }

    /**
     * 获取视频类型描述
     * @param string $value
     * @return string
     */
    public static function getTypeDesc($value): string
    {
        $data = self::getTypeDict();
        return $data[$value] ?? '未知状态';
    }


    // 审核状态状态
    const AUDIT_STATUS_WAIT = 0; // 待审核
    const AUDIT_STATUS_PASS = 1; // 审核通过
    const AUDIT_STATUS_REFUSE = 2; // 审核拒绝


    /**
     * 获取视频审核状态列表
     * @return array
     */
    public static function getAuditStatusDict(): array
    {
        return [
            self::AUDIT_STATUS_WAIT => '待审核',
            self::AUDIT_STATUS_PASS => '审核通过',
            self::AUDIT_STATUS_REFUSE => '审核拒绝',

        ];
    }

    /**
     * 获取视频审核状态描述
     * @param string $value
     * @return string
     */
    public static function getAuditStatusDesc($value): string
    {
        $data = self::getAuditStatusDict();
        return $data[$value] ?? '未知状态';
    }

    



}
