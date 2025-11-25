<?php

namespace app\deshang\base\service;

use app\deshang\base\service\BaseApiService;
use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\AuthException;
use app\deshang\kv\KvManager;

// 用户基础服务层  用户必须登录
class BaseUserService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();

        //验证是否获取到user_id
        if (!$this->user_id) {
            throw new AuthException('请先登录', 403);
        }

        if (!$this->user_is_enabled) {
            throw new AuthException('您账户已被禁用');
        }

        // 访问频率限流, 可在子类按需调用（每用户30次/60秒）（建议启用Redis）
        // $this->applyRateLimit(30, 60);

    }


    // 限流方法（子类按需调用）
    protected function applyRateLimit(int $max = 30, int $window = 60): void
    {
        // - 限流默认30次/60秒，适合中低风险接口（平衡安全与体验）。
        // - 建议启用Redis：提供原子操作，性能更好（本地 file 有I/O开销）。
        // - 替代：删除此限流，用Nginx服务器级限流（配置 limit_req），避免PHP压力（尤其高并发下）。
        // - 影响：太严可能误限正常用户，监控日志调优；高风险如支付建议 max=1/window=30。
        $rateKey = 'user_rate_' . $this->user_id . '_' . md5(request()->layer(). request()->controller(). request()->action());
        if (!KvManager::rateLimit()->check($rateKey, $max, $window)) {
            throw new CommonException('操作频繁，请' . ceil($window / 60) . '分钟后重试');
        }
    }







}
