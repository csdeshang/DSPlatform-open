<?php


namespace app\adminapi\service\user;

use think\facade\Db;

use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\user\DeshangUserGrowthService;

use app\common\enum\user\UserGrowthEnum;
use app\deshang\utils\SearchHelper;


use app\common\dao\user\UserGrowthLogDao;

class UserGrowthService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取会员成长值记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getUserGrowthLogPages(array $data): array
    {
        $condition = [];
        if(isset($data['user_id']) && $data['user_id'] != ''){
            $condition[] = ['user_id', '=', $data['user_id']];
        }

        if (isset($data['username']) && $data['username'] != '') {
            $userIds = SearchHelper::getUserIdsByUsername($data['username']);
            $condition[] = ['user_id', 'in', $userIds];
        }

        if(isset($data['change_type']) && $data['change_type'] !== ''){
            $condition[] = ['change_type', '=', $data['change_type']];
        }
        if(isset($data['change_mode']) && $data['change_mode'] !== ''){
            $condition[] = ['change_mode', '=', $data['change_mode']];
        }

        $result = (new UserGrowthLogDao())->getWithRelGrowthLogPages($condition);
        return $result;
    }

    /**
     * 修改会员预存款
     * @param array $data 修改数据
     */
    function modifyUserGrowth(array $data)
    {
        $add_data = array(
            'user_id' => $data['user_id'],
            'related_id' => 0,
            'change_type' => UserGrowthEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_num' => $data['change_num'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangUserGrowthService();
            $deshangService->modifyUserGrowth($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new CommonException('获取到的异常'.$e->getMessage());
        }
    }
}
