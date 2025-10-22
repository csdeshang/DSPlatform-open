<?php

namespace app\adminapi\service\user;

use app\deshang\base\service\BaseAdminService;

use app\deshang\service\user\DeshangUserService;

use app\common\dao\user\UserDao;

class UserService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
        $this->dao = new UserDao();
    }

    public function getUserPages($data)
    {
        $condition = [];

        if (isset($data['username']) && $data['username'] != '') {
            $condition[] = ['username', 'like', '%' . $data['username'] . '%'];
        }
        if (isset($data['nickname']) && $data['nickname'] != '') {
            $condition[] = ['nickname', 'like', '%' . $data['nickname'] . '%'];
        }
        if (isset($data['email']) && $data['email'] != '') {
            $condition[] = ['email', 'like', '%' . $data['email'] . '%'];
        }
        if (isset($data['mobile']) && $data['mobile'] != '') {
            $condition[] = ['mobile', 'like', '%' . $data['mobile'] . '%'];
        }
        
        // 邀请人ID搜索
        if (isset($data['inviter_id']) && $data['inviter_id'] !== '') {
            $condition[] = ['inviter_id', '=', $data['inviter_id']];
        }
        
        // 是否启用搜索
        if (isset($data['is_enabled']) && $data['is_enabled'] !== '') {
            $condition[] = ['is_enabled', '=', $data['is_enabled']];
        }
        
        // 可用金额区间搜索
        if (isset($data['balance_min']) && $data['balance_min'] !== '') {
            $condition[] = ['balance', '>=', $data['balance_min']];
        }
        if (isset($data['balance_max']) && $data['balance_max'] !== '') {
            $condition[] = ['balance', '<=', $data['balance_max']];
        }
        
        // 总收入区间搜索
        if (isset($data['balance_in_min']) && $data['balance_in_min'] !== '') {
            $condition[] = ['balance_in', '>=', $data['balance_in_min']];
        }
        if (isset($data['balance_in_max']) && $data['balance_in_max'] !== '') {
            $condition[] = ['balance_in', '<=', $data['balance_in_max']];
        }
        
        // 总支出区间搜索
        if (isset($data['balance_out_min']) && $data['balance_out_min'] !== '') {
            $condition[] = ['balance_out', '>=', $data['balance_out_min']];
        }
        if (isset($data['balance_out_max']) && $data['balance_out_max'] !== '') {
            $condition[] = ['balance_out', '<=', $data['balance_out_max']];
        }

        if (isset($data['is_distributor']) && in_array($data['is_distributor'], [0,1])) {
            $condition[] = ['is_distributor', '=', $data['is_distributor']];
        }
        
        // 分销商余额区间搜索
        if (isset($data['distributor_balance_min']) && $data['distributor_balance_min'] !== '') {
            $condition[] = ['distributor_balance', '>=', $data['distributor_balance_min']];
        }
        if (isset($data['distributor_balance_max']) && $data['distributor_balance_max'] !== '') {
            $condition[] = ['distributor_balance', '<=', $data['distributor_balance_max']];
        }
        
        // 分销商收入区间搜索
        if (isset($data['distributor_balance_in_min']) && $data['distributor_balance_in_min'] !== '') {
            $condition[] = ['distributor_balance_in', '>=', $data['distributor_balance_in_min']];
        }
        if (isset($data['distributor_balance_in_max']) && $data['distributor_balance_in_max'] !== '') {
            $condition[] = ['distributor_balance_in', '<=', $data['distributor_balance_in_max']];
        }
        
        // 分销商支出区间搜索
        if (isset($data['distributor_balance_out_min']) && $data['distributor_balance_out_min'] !== '') {
            $condition[] = ['distributor_balance_out', '>=', $data['distributor_balance_out_min']];
        }
        if (isset($data['distributor_balance_out_max']) && $data['distributor_balance_out_max'] !== '') {
            $condition[] = ['distributor_balance_out', '<=', $data['distributor_balance_out_max']];
        }

        $result = $this->dao->getUserPages($condition);
        return $result;
    }


    public function createUser($data)
    {
        $result = (new DeshangUserService())->createUser($data);
        return $result;
    }

    public function updateUser(int $id,array $data)
    {
        $result = (new DeshangUserService())->updateUser($id,$data);
        return $result;
    }


    public function getUserInfo($id)
    {
        $result = $this->dao->getUserInfoById($id);
        // 密码不返回
        $result['password'] = '';
        $result['pay_password'] = '';
        return $result;
    }

    /**
     * 获取用户推广关系列表
     * @param array $data
     * @return array
     */
    public function getUserRelationList($data)
    {
        $inviter_id = (int)$data['inviter_id'];
        
        // 构建查询条件：查询被邀请的用户列表
        $condition = [
            ['inviter_id', '=', $inviter_id]
        ];
        
        // 查询用户列表
        $result = $this->dao->getUserList($condition);
        
        // 为每个用户添加是否有子节点的标识和基础统计
        foreach ($result as &$user) {
            // 统计该用户的直推人数（该用户作为邀请人邀请了多少人）
            $user['direct_count'] = $this->dao->getUserCount([['inviter_id', '=', $user['id']]]);
            
            // 基于直推人数判断是否有子节点
            $user['hasChildren'] = $user['direct_count'] > 0;
        }
        
        return $result;
    }

}
