<?php


namespace app\adminapi\service\stat;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\user\UserDao;





class StatUserService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new UserDao();
    }

    public function getStatUserOverview()
    {
        $result = [];

        // 总新增用户数
        $result['new_user']['total'] = $this->dao->getUserCount([]);

        // 今日新增
        $result['new_user']['today'] = $this->dao->getUserCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日新增
        $result['new_user']['yesterday'] = $this->dao->getUserCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周新增
        $result['new_user']['week'] = $this->dao->getUserCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月新增
        $result['new_user']['month'] = $this->dao->getUserCount([
            ['create_at', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['create_at', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);


        //活跃用户数
        $result['active_user']['today'] = $this->dao->getUserCount([
            ['login_time', '>=', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 昨日活跃
        $result['active_user']['yesterday'] = $this->dao->getUserCount([
            ['login_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 day')))],
            ['login_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本周活跃
        $result['active_user']['week'] = $this->dao->getUserCount([
            ['login_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 week')))],
            ['login_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);
        // 本月活跃
        $result['active_user']['month'] = $this->dao->getUserCount([
            ['login_time', '>=', strtotime(date('Y-m-d 00:00:00', strtotime('-1 month')))],
            ['login_time', '<', strtotime(date('Y-m-d 00:00:00'))]
        ]);


        // 用户资产(总资产,总收入,总支出)
        $user_asset = $this->dao->getUserInfo([['id', '>', 0]],'sum(balance) as total_balance, sum(balance_in) as total_balance_in, sum(balance_out) as total_balance_out, sum(points) as total_points, sum(points_in) as total_points_in, sum(points_out) as total_points_out');
  
        $result['user_asset']['total_balance'] = ds_commerce_money($user_asset['total_balance'], 2);
        $result['user_asset']['total_balance_in'] = ds_commerce_money($user_asset['total_balance_in'], 2);
        $result['user_asset']['total_balance_out'] = ds_commerce_money($user_asset['total_balance_out'], 2);

        // 用户资产(总积分,总收入,总支出)
        $result['user_asset']['total_points'] = $user_asset['total_points'];
        $result['user_asset']['total_points_in'] = $user_asset['total_points_in'];
        $result['user_asset']['total_points_out'] = $user_asset['total_points_out'];



        return $result;
    }
}
