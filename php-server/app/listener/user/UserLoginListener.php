<?php


namespace app\listener\user;

use think\facade\Db;
use app\deshang\exceptions\CommonException;


use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\common\enum\user\UserGrowthEnum;
use app\deshang\service\user\DeshangUserGrowthService;

use app\common\dao\user\UserPointsLogDao;
use app\common\dao\user\UserGrowthLogDao;

class UserLoginListener
{
    public function handle(array $params)
    {
        // 登录获取积分
        $this->loginGetPoints($params);

        // 登录获取成长值
        $this->loginGetGrowth($params);
    }


    // 登录获取积分
    public function loginGetPoints($params)
    {
        // 登录获取积分
        $points_login_enabled = sysConfig('points:points_login_enabled');
        if ($points_login_enabled == 1) {
            // 积分
            $points_login_amount = sysConfig('points:points_login_amount');

            if ($points_login_amount > 0) {

                // 判断今天是否有领取记录
                $condition = [
                    ['user_id', '=', $params['user_id']],
                    ['change_type', '=', UserPointsEnum::TYPE_LOGIN],
                    ['change_mode', '=', UserPointsEnum::MODE_INCREASE],
                    ['create_at', '>=', strtotime(date('Y-m-d'))],
                ];
                $today_points_log = (new UserPointsLogDao())->getPointsLogInfo($condition);

                // 没有领取记录，则添加记录
                if (empty($today_points_log)) {
                    $add_data = array(
                        'user_id' => $params['user_id'],
                        'related_id' => 0,
                        'change_type' => UserPointsEnum::TYPE_LOGIN,
                        'change_mode' => UserPointsEnum::MODE_INCREASE,
                        'change_num' => $points_login_amount,
                        'change_desc' => '登录获取积分',
                    );
                    Db::startTrans();
                    try {
                        (new DeshangUserPointsService())->modifyUserPoints($add_data);
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        throw new CommonException('获取到的异常' . $e->getMessage());
                    }
                }
            }
        }
    }


    // 登录获取成长值
    public function loginGetGrowth($params)
    {
        // 登录获取成长值
        $growth_login_enabled = sysConfig('growth:growth_login_enabled');
        if ($growth_login_enabled == 1) {
            // 成长值
            $growth_login_amount = sysConfig('growth:growth_login_amount');

            if ($growth_login_amount > 0) {

                // 判断今天是否有领取记录
                $condition = [
                    ['user_id', '=', $params['user_id']],
                    ['change_type', '=', UserGrowthEnum::TYPE_LOGIN],
                    ['change_mode', '=', UserGrowthEnum::MODE_INCREASE],
                    ['create_at', '>=', strtotime(date('Y-m-d'))],
                ];
                $today_growth_log = (new UserGrowthLogDao())->getGrowthLogInfo($condition);

                // 没有领取记录，则添加记录
                if (empty($today_growth_log)) {
                    $add_data = array(
                        'user_id' => $params['user_id'],
                        'related_id' => 0,
                        'change_type' => UserGrowthEnum::TYPE_LOGIN,
                        'change_mode' => UserGrowthEnum::MODE_INCREASE,
                        'change_num' => $growth_login_amount,
                        'change_desc' => '登录获取成长值',
                    );
                    Db::startTrans();
                    try {
                        (new DeshangUserGrowthService())->modifyUserGrowth($add_data);
                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        throw new CommonException('获取到的异常' . $e->getMessage());
                    }
                }
            }
        }
    }
}
