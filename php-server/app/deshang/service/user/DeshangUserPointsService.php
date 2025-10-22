<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserPointsEnum;


use app\common\dao\user\UserPointsLogDao;
use app\common\dao\user\UserDao;


class DeshangUserPointsService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改用户积分 调用建议通过事务处理
    public function modifyUserPoints($data)
    {

        $change_mode = $data['change_mode'];
        $change_num = $data['change_num'];
        $user_id = $data['user_id'];
        $change_type = $data['change_type'];



        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, UserPointsEnum::getChangeModeDict())) {
            throw new CommonException('UserPointsEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, UserPointsEnum::getChangeTypeDict())) {
            throw new CommonException('UserPointsEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_num)) {
            throw new CommonException('积分格式错误，必须为数字');
        }
        if ($change_num <= 0) {
            throw new CommonException('积分必须为正数');
        }

        //获取用户信息

        $user_info = (new UserDao())->getUserInfoById($user_id, 'id,points,points_in,points_out');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够积分进行扣除
        if ($change_mode == UserPointsEnum::MODE_DECREASE) {
            if ($user_info['points'] < $change_num) {
                throw new CommonException('用户积分不足');
            }
        }

        $after_points = $change_mode == 1 ? $user_info['points'] + $change_num : $user_info['points'] - $change_num;



        // print_r($user_info);exit;



        $points_data = array(
            'user_id' => $user_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 充值 提现 退款 系统
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_num' => $change_num, // 变动金额
            'before_num' => $user_info['points'], // 变动前金额
            'after_num' => $after_points, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        $points_log_id = (new UserPointsLogDao())->createPointsLog($points_data);

        //修改用户余额
        $user_updata = array(
            'points' => $after_points
        );
        switch ($change_mode) {
            case UserPointsEnum::MODE_INCREASE:
                //收入总额
                $user_updata['points_in'] = $user_info['points_in'] + $change_num;
                break;
            case UserPointsEnum::MODE_DECREASE:
                //支出总额
                $user_updata['points_out'] = $user_info['points_out'] + $change_num;
                break;
        }

        $result = (new UserDao())->updateUser(['id' => $user_id], $user_updata);




        return $result;
    }
}
