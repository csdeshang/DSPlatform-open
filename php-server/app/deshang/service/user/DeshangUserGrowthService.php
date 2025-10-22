<?php


namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\service\BaseDeshangService;

use app\common\enum\user\UserGrowthEnum;

use app\common\dao\user\UserDao;
use app\common\dao\user\UserGrowthLogDao;
use app\common\dao\user\UserGrowthLevelDao;

class DeshangUserGrowthService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }




    // 修改用户成长值 调用建议通过事务处理
    public function modifyUserGrowth($data)
    {

        $change_mode = $data['change_mode'];
        $change_num = $data['change_num'];
        $user_id = $data['user_id'];
        $change_type = $data['change_type'];



        // 使用枚举类验证变动方式
        if (!array_key_exists($change_mode, UserGrowthEnum::getChangeModeDict())) {
            throw new CommonException('UserGrowthEnum 变动方式错误');
        }

        // 验证变动类型
        if (!array_key_exists($change_type, UserGrowthEnum::getChangeTypeDict())) {
            throw new CommonException('UserGrowthEnum 变动类型错误');
        }



        // 验证金额是否合法（必须为正数且为数字）
        if (!is_numeric($change_num)) {
            throw new CommonException('成长值格式错误，必须为数字');
        }
        if ($change_num <= 0) {
            throw new CommonException('成长值必须为正数');
        }

        //获取用户信息

        $user_info = (new UserDao())->getUserInfoById($user_id, 'id,growth,growth_level_id');
        if (empty($user_info)) {
            throw new CommonException('用户不存在');
        }

        // 判断是否有足够成长值进行扣除
        if ($change_mode == UserGrowthEnum::MODE_DECREASE) {
            if ($user_info['growth'] < $change_num) {
                throw new CommonException('用户成长值不足');
            }
        }

        $after_growth = $change_mode == 1 ? $user_info['growth'] + $change_num : $user_info['growth'] - $change_num;



        // print_r($user_info);exit;



        $growth_data = array(
            'user_id' => $user_id,
            // 关联ID 订单ID 退款ID
            'related_id' => $data['related_id'],
            'change_type' => $change_type, // 变动类型 充值 提现 退款 系统
            'change_mode' => $change_mode, // 变动方式 1 增加 2 减少
            'change_num' => $change_num, // 变动金额
            'before_num' => $user_info['growth'], // 变动前金额
            'after_num' => $after_growth, // 变动后金额
            'change_desc' => $data['change_desc'], // 变动描述
        );

        $growth_log_id = (new UserGrowthLogDao())->createGrowthLog($growth_data);

        //修改用户余额
        $user_updata = array(
            'growth' => $after_growth
        );



        // 获取等级列表
        $growth_level_list = (new UserGrowthLevelDao())->getUserGrowthLevelList([],'*','min_growth desc');
        
        // 计算变动后成长值对应的等级ID
        $new_growth_level_id = $user_info['growth_level_id']; // 默认不变
        foreach ($growth_level_list as $level) {
            if ($after_growth >= $level['min_growth']) {
                $new_growth_level_id = $level['id'];
                break;
            }
        }

        // 如果等级有变化，更新等级ID
        if ($new_growth_level_id != $user_info['growth_level_id']) {
            $user_updata['growth_level_id'] = $new_growth_level_id;
        }


        // 成长值一般情况下只有增加，除了系统减少，所以没有总收入和总支出
        // switch ($change_mode) {
        //     case UserGrowthEnum::MODE_INCREASE:
        //         //收入总额
        //         $user_updata['growth_in'] = $user_info['growth_in'] + $change_num;
        //         break;
        //     case UserGrowthEnum::MODE_DECREASE:
        //         //支出总额
        //         $user_updata['growth_out'] = $user_info['growth_out'] + $change_num;
        //         break;
        // }

        $result = (new UserDao())->updateUser(['id' => $user_id], $user_updata);

        return $result;
    }



}
