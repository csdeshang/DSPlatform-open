<?php

namespace app\listener\user;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\common\enum\user\UserGrowthEnum;
use app\deshang\service\user\DeshangUserGrowthService;



class UserInviteListener
{
    public function handle(array $params)
    {
        // 邀请获取积分
        $this->inviteGetPoints($params);

        // 邀请获取成长值
        $this->inviteGetGrowth($params);
    }


    // 注册获取积分
    public function inviteGetPoints($params)
    {
        // 邀请获取积分
        $points_invite_enabled = sysConfig('points:points_invite_enabled');
        if ($points_invite_enabled == 1) {
            // 积分
            $points_invite_amount = sysConfig('points:points_invite_amount');

            if ($points_invite_amount > 0) {

                $add_data = array(
                    'user_id' => $params['inviter_id'],
                    'related_id' => 0,
                    'change_type' => UserPointsEnum::TYPE_INVITE,
                    'change_mode' => UserPointsEnum::MODE_INCREASE,
                    'change_num' => $points_invite_amount,
                    'change_desc' => '邀请获取积分',
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


    // 邀请获取成长值
    public function inviteGetGrowth($params)
    {
        // 邀请获取成长值
        $growth_invite_enabled = sysConfig('growth:growth_invite_enabled');
        if ($growth_invite_enabled == 1) {
            // 成长值
            $growth_invite_amount = sysConfig('growth:growth_invite_amount');

            if ($growth_invite_amount > 0) {


                $add_data = array(
                    'user_id' => $params['inviter_id'],
                    'related_id' => 0,
                    'change_type' => UserGrowthEnum::TYPE_INVITE,
                    'change_mode' => UserGrowthEnum::MODE_INCREASE,
                    'change_num' => $growth_invite_amount,
                    'change_desc' => '邀请获取成长值',
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
