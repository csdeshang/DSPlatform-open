<?php

namespace app\deshang\base;

use think\Validate;



use app\common\dao\merchant\MerchantDao;
use app\common\dao\system\SysPlatformDao;



class BaseValidate extends Validate
{
    // 检测平台是否存在
    protected function checkPlatform($value, $rule, $data = []): bool
    {

        $condition = [
            ['platform', '=', $value],
        ];
        $result = (new SysPlatformDao())->getSysPlatformInfo($condition);
        return $result ? true : false;
    }


    // 检测商户是否存在
    protected function checkMerchant($value, $rule, $data = []): bool
    {
        $merchant = (new MerchantDao())->getMerchantInfoById($value);
        return $merchant ? true : false; // 用户存在返回true，不存在返回false
    }


    // 检测数组是否所有元素都是数字
    protected function isAllNumbers($value, $rule, $data = []): bool
    {
        foreach ($value as $item) {
            if (!is_numeric($item)) {
                return false; // 如果有一个元素不是数字，直接返回 false
            }
        }
        return true; // 如果所有元素都是数字，返回 true
    }

    /**
     * 检测纬度值是否有效
     * 纬度取值范围：-90度 ~ 90度
     */
    protected function checkLatitude($value, $rule, $data = []): bool
    {
        // 检查是否为数值
        if (!is_numeric($value)) {
            return false;
        }
        // 检查范围：纬度范围是 -90 到 90 度
        if ($value < -90 || $value > 90) {
            return false;
        }
        return true;
    }

    /**
     * 检测经度值是否有效
     * 经度取值范围：-180度 ~ 180度
     */
    protected function checkLongitude($value, $rule, $data = []): bool
    {
        // 检查是否为数值
        if (!is_numeric($value)) {
            return false;
        }
        // 检查范围：经度范围是 -180 到 180 度
        if ($value < -180 || $value > 180) {
            return false;
        }
        return true;
    }

    // 检测手机验证码
    protected function checkMobileCode($value, $rule, $data = []): bool
    {
        // 6位数字
        if (!preg_match('/^\d{6}$/', $value)) {
            return false;
        }
        return true;
    }


}
