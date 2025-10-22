<?php

namespace app\listener\user;

use think\facade\Db;
use app\deshang\exceptions\CommonException;

use app\common\enum\user\UserPointsEnum;
use app\deshang\service\user\DeshangUserPointsService;
use app\common\enum\user\UserGrowthEnum;
use app\deshang\service\user\DeshangUserGrowthService;



class UserRegisterListener
{
    public function handle(array $params)
    {
        // 注册获取积分
        $this->registerGetPoints($params);

        // 注册获取成长值
        $this->registerGetGrowth($params);
    }


    // 注册获取积分
    public function registerGetPoints($params)
    {
        // 注册获取积分
        $points_register_enabled = sysConfig('points:points_register_enabled');
        if ($points_register_enabled == 1) {
            // 积分
            $points_register_amount = sysConfig('points:points_register_amount');

            if ($points_register_amount > 0) {

                $add_data = array(
                    'user_id' => $params['user_id'],
                    'related_id' => 0,
                    'change_type' => UserPointsEnum::TYPE_REGISTER,
                    'change_mode' => UserPointsEnum::MODE_INCREASE,
                    'change_num' => $points_register_amount,
                    'change_desc' => '注册获取积分',
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


    // 注册获取成长值
    public function registerGetGrowth($params)
    {
        // 注册获取成长值
        $growth_register_enabled = sysConfig('growth:growth_register_enabled');
        if ($growth_register_enabled == 1) {
            // 成长值
            $growth_register_amount = sysConfig('growth:growth_register_amount');

            if ($growth_register_amount > 0) {


                $add_data = array(
                    'user_id' => $params['user_id'],
                    'related_id' => 0,
                    'change_type' => UserGrowthEnum::TYPE_REGISTER,
                    'change_mode' => UserGrowthEnum::MODE_INCREASE,
                    'change_num' => $growth_register_amount,
                    'change_desc' => '注册获取成长值',
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
