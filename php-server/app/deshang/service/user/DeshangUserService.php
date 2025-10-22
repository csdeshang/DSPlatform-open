<?php

namespace app\deshang\service\user;

use app\deshang\exceptions\CommonException;
use app\deshang\utils\TokenCache;

use app\deshang\service\BaseDeshangService;

use app\common\dao\user\UserDao;
use app\common\dao\trade\TradePayLogDao;
use app\common\dao\merchant\MerchantDao;
use app\common\dao\rider\RiderDao;
use app\common\dao\store\TblStoreDao;
use app\common\dao\store\TblStoreAuthUserDao;
use app\common\dao\technician\TechnicianDao;
use app\common\dao\blogger\BloggerDao;

use app\common\enum\blogger\BloggerEnum;


class DeshangUserService extends BaseDeshangService
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * 会员注册
     */
    public function createUser($data)
    {
        $this->validate($data, 'app\deshang\service\user\validate\User.add');

        $user_info = array();
        $user_info['username'] = $data['username'];
        $user_info['password'] = create_password(trim($data['password']));
        if (isset($data['email'])) {
            $user_info['email'] = $data['email'];
        }

        $user_info['create_at'] = TIMESTAMP;
        $user_info['login_time'] = TIMESTAMP;
        $user_info['old_login_time'] = TIMESTAMP;
        $user_info['login_ip'] = request()->ip();
        $user_info['old_login_ip'] = $user_info['login_ip'];
        $user_info['pay_password'] = create_password('123456'); //注册会员默认支付密码为123456

        if (isset($data['idcard_name'])) {
            $user_info['idcard_name'] = $data['idcard_name'];
        }
        if (isset($data['nickname'])) {
            $user_info['nickname'] = $data['nickname'];
        } else {
            $user_info['nickname'] = '用户' . $user_info['username'];
        }
        if (isset($data['qq'])) {
            $user_info['qq'] = $data['qq'];
        }
        if (isset($data['sex'])) {
            $user_info['sex'] = $data['sex'];
        }
        if (isset($data['avatar'])) {
            $user_info['avatar'] = $data['avatar'];
        }

        //添加邀请人(推荐人)会员积分
        if (isset($data['inviter_id']) && intval($data['inviter_id']) > 0) {
            // 邀请人是否存在
            $inviter_info = (new UserDao())->getUserInfoById($data['inviter_id']);
            if(!empty($inviter_info)){
                $user_info['inviter_id'] = intval($data['inviter_id']);
            }
        }

        //  手机注册登录绑定
        if (isset($data['mobile_bind'])) {
            $user_info['mobile'] = $data['mobile'];
            $user_info['mobile_bind'] = $data['mobile_bind'];
        }



        $user_id = (new UserDao())->createUser($user_info);


        if ($user_id > 0) {            
            // 用户注册事件
            event('UserRegisterListener',['user_id'=>$user_id]);
            // 邀请获取积分
            if(isset($data['inviter_id']) && intval($data['inviter_id']) > 0){
                event('UserInviteListener',['inviter_id'=>$data['inviter_id']]);
            }
        }

        return $user_id;
    }


    // 更新用户信息(主要用于基本信息的修改，积分、余额、成长值、余额等通过Dao层修改)
    public function updateUser(int $id,array $data)
    {

        // 过滤不能修改的字段 
        $unmodifiableFields = ['username', 'growth', 'points', 'points_in', 'points_out', 'balance', 'balance_in', 'balance_out', 'distributor_balance', 'distributor_balance_in', 'distributor_balance_out'];
        foreach ($unmodifiableFields as $field) {
            if (array_key_exists($field,$data)) {
                throw new CommonException($field . '不能直接进行修改');
            }
        }
        // 验证器
        $this->validate($data, 'app\deshang\service\user\validate\User.edit');

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = create_password($data['password']);
            
            // 修改密码后，使该用户的所有Token失效 (用户禁用 刷新Token去验证)
            (new TokenCache())->invalidateToken('user', $id);
        }else{
            unset($data['password']);
        }

        if (isset($data['pay_password']) && !empty($data['pay_password'])) {
            $data['pay_password'] = create_password($data['pay_password']);
        }else{
            unset($data['pay_password']);
        }


        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = (new UserDao())->updateUser($condition, $data);

        return $result;
    }


    // 获取用户信息和其他角色信息
    public function getUserInfoWithRoles($id){
        $user_info = (new UserDao())->getUserInfoById($id,'*');
        unset($user_info['password']);
        unset($user_info['pay_password']);



        // 获取商户信息
        $user_info['merchant'] = (new MerchantDao)->getMerchantInfo([['user_id','=',$user_info['id']]],'*');

        // 获取骑手信息
        $user_info['rider'] = (new RiderDao)->getRiderInfo([['user_id','=',$user_info['id']]],'*');

        // 获取师傅信息
        $user_info['technician'] = (new TechnicianDao)->getTechnicianInfo([['user_id','=',$user_info['id']]],'*');

        // 获取博主信息
        $user_info['blogger'] = $this->getBloggerInfo($user_info);


        // 获取可管理的店铺列表
        $manage_store_list = array();
        // 获取商户下的店铺
        if(isset($user_info['merchant']['id'])){
            $manage_store_list = (new TblStoreDao)->getStoreList([['merchant_id','=',$user_info['merchant']['id']]],'id,platform,merchant_id,store_name,apply_status');
        }
        // 获取用户授权的店铺
        $store_ids = (new TblStoreAuthUserDao)->getStoreAuthUserColumn([['user_id','=',$user_info['id']]],'store_id');
        if(!empty($store_ids)){
            $manage_store_list = array_merge($manage_store_list, (new TblStoreDao)->getStoreList([['id','in',$store_ids]],'id,platform,merchant_id,store_name,apply_status'));
        }
        $user_info['manage_store_list'] = $manage_store_list;



        return $user_info;
    }



    // 根据用户id 获取用户信息
    public function getUserInfoById($id){
        $result = (new UserDao())->getUserInfoById($id);
        return $result;
    }



    // 获取用户交易记录分页
    public function getUserTradePayLogPages($data){
        $condition = [];
        $condition[] = ['user_id', '=', $data['user_id']];
        $result = (new TradePayLogDao())->getTradePayLogPages($condition);
        return $result;
    }


    // 获取博主信息(如果不是博主 自动注册成博主，如需博主审核功能，单独开发)
    public function getBloggerInfo($user_info){
        $blogger_info = (new BloggerDao())->getBloggerInfo([['user_id','=',$user_info['id']]],'*');



        // 自动创建博主
        if(empty($blogger_info)){
            $blogger_id = (new BloggerDao())->createBlogger([
                'user_id' => $user_info['id'],
                'blogger_name' => $user_info['nickname'],
                'verification_status' => BloggerEnum::VERIFICATION_STATUS_WAIT,
            ]);

            $blogger_info = (new BloggerDao())->getBloggerInfo([['id','=',$blogger_id]],'*');
        }


        return $blogger_info;
    }



}
