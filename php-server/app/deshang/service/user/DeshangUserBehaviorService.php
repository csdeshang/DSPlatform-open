<?php

namespace app\deshang\service\user;

use app\deshang\service\BaseDeshangService;
use app\common\dao\user\UserBehaviorLogDao;
use app\common\enum\user\UserBehaviorEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\utils\UserAgentUtil;

class DeshangUserBehaviorService extends BaseDeshangService
{
    private $userBehaviorLogDao;

    public function __construct()
    {
        parent::__construct();
        $this->userBehaviorLogDao = new UserBehaviorLogDao();
    }

    /**
     * 记录用户行为
     * 
     * @param array $data 行为数据
     * @param array $extraData 额外数据
     * @return int 记录ID
     */
    public function logUserBehavior(array $data, array $extraData = []): int
    {
        $ip = request()->ip();
        $userAgent = request()->header('User-Agent');

        // 验证参数
        if (empty($data['username'])) {
            throw new CommonException('用户名不能为空');
        }

        // 验证行为类型
        if (!array_key_exists($data['behavior_type'], UserBehaviorEnum::getBehaviorTypeDict())) {
            throw new CommonException('logUserBehavior的行为类型错误');
        }
        // 验证行为状态
        if (!array_key_exists($data['behavior_status'], UserBehaviorEnum::getBehaviorStatusDict())) {
            throw new CommonException('logUserBehavior的行为状态错误');
        }
        // 验证风险等级
        if (!array_key_exists($data['risk_level'], UserBehaviorEnum::getRiskLevelDict())) {
            throw new CommonException('logUserBehavior的风险等级错误');
        }
        // 验证异常状态
        if (!array_key_exists($data['is_abnormal'], UserBehaviorEnum::getAbnormalDict())) {
            throw new CommonException('logUserBehavior的异常状态错误');
        }


        // 使用 UserAgentUtil 解析设备信息
        $deviceInfo = UserAgentUtil::parse($userAgent);

        $logData = [
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'behavior_type' => $data['behavior_type'],
            'behavior_scene' => UserAgentUtil::getPlatformType($userAgent),
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'device_type' => $deviceInfo['device_type'],
            'browser' => $deviceInfo['browser'],
            'os' => $deviceInfo['os'],
            'behavior_status' => $data['behavior_status'],
            'failure_reason' => $data['failure_reason'],
            'is_abnormal' => $data['is_abnormal'],
            'abnormal_reason' => $data['abnormal_reason'],
            'risk_level' => $data['risk_level'],
            'extra_data' => json_encode($extraData),
        ];

        return $this->userBehaviorLogDao->createUserBehaviorLog($logData);
    }


    /**
     * 检测IP频率限制
     * 
     * @throws CommonException
     */
    public function checkIpFrequency(): void
    {
        $ip = request()->ip();
        $currentTime = time();

        // 1. 检测5分钟内失败次数
        $fiveMinFailed = $this->userBehaviorLogDao->getUserBehaviorLogCount([
            ['create_at', '>=', $currentTime - 300], // 5分钟
            ['behavior_type', 'in', [UserBehaviorEnum::TYPE_LOGIN_NORMAL, UserBehaviorEnum::TYPE_LOGIN_MOBILE]],
            ['ip_address', '=', $ip],
            ['behavior_status', '=', UserBehaviorEnum::STATUS_FAILED]
        ]);

        if ($fiveMinFailed >= 10) {
            throw new CommonException('IP访问过于频繁，请5分钟后再试');
        }

        // 2. 检测1小时内总尝试次数（包括成功和失败）
        // $oneHourTotal = $this->userBehaviorLogDao->getUserBehaviorLogCount([
        //     ['create_at', '>=', $currentTime - 3600], // 1小时
        //     ['behavior_type', 'in', [UserBehaviorEnum::TYPE_LOGIN_NORMAL, UserBehaviorEnum::TYPE_LOGIN_MOBILE]],
        //     ['ip_address', '=', $ip]
        // ]);

        // if ($oneHourTotal >= 20) {
        //     throw new CommonException('IP访问过于频繁，请1小时后再试');
        // }


    }

    /**
     * 检测用户登录频率限制
     * 
     * @param array $user 用户信息
     * @param array $extraData 额外数据
     * @throws CommonException
     */
    public function checkUserLoginFrequency(array $user, array $extraData = []): void
    {
        $startTime = time() - 300; // 5分钟
        $userAttempts = $this->userBehaviorLogDao->getUserBehaviorLogCount([
            ['create_at', '>=', $startTime],
            ['behavior_type', 'in', [UserBehaviorEnum::TYPE_LOGIN_NORMAL, UserBehaviorEnum::TYPE_LOGIN_MOBILE]],
            ['username', '=', $user['username']]
        ]);

        if ($userAttempts >= 5) {
            // 记录被阻止的登录尝试
            $this->logUserBehavior([
                'username' => $user['username'],
                'user_id' => $user['id'],
                'behavior_type' => UserBehaviorEnum::TYPE_LOGIN_NORMAL,
                'behavior_status' => UserBehaviorEnum::STATUS_FAILED,
                'failure_reason' => '登录尝试次数过多',
                'is_abnormal' => UserBehaviorEnum::ABNORMAL_YES,
                'abnormal_reason' => '登录尝试次数过多',
                // 中风险
                'risk_level' => UserBehaviorEnum::RISK_MEDIUM,
                'extra_data' => json_encode($extraData)
            ]);

            throw new CommonException('登录尝试次数过多，请5分钟后再试');
        }
    }
}
