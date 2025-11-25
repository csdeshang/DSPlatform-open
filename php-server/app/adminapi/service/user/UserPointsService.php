<?php


namespace app\adminapi\service\user;

use think\facade\Db;

use app\deshang\exceptions\CommonException;
use app\deshang\base\service\BaseAdminService;
use app\deshang\service\user\DeshangUserPointsService;

use app\common\enum\user\UserPointsEnum;

use app\common\dao\user\UserPointsLogDao;
use app\deshang\utils\SearchHelper;

class UserPointsService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取会员积分记录列表
     * @param array $data 查询条件
     * @return array
     */
    public function getUserPointsLogPages(array $data): array
    {
        $condition = [];
        if(isset($data['user_id']) && $data['user_id'] != ''){
            $condition[] = ['user_id', '=', $data['user_id']];
        }
        
        // 用户名搜索
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
        
        // 关联ID搜索
        if(isset($data['related_id']) && $data['related_id'] !== ''){
            $condition[] = ['related_id', '=', (int)$data['related_id']];
        }

        $result = (new UserPointsLogDao())->getWithRelPointsLogPages($condition);
        return $result;
    }

    /**
     * 获取会员积分日志详情
     * @param int $id 日志ID
     * @return array
     */
    public function getUserPointsLogInfo($id)
    {
        $result = (new UserPointsLogDao())->getPointsLogInfoById($id);
        return $result;
    }

    /**
     * 修改会员预存款
     * @param array $data 修改数据
     */
    function modifyUserPoints(array $data)
    {
        $add_data = array(
            'user_id' => $data['user_id'],
            'related_id' => 0,
            'change_type' => UserPointsEnum::TYPE_SYSTEM,
            'change_mode' => $data['change_mode'],
            'change_num' => $data['change_num'],
            'change_desc' => isset($data['change_desc']) ? $data['change_desc'] : '管理员操作',
        );

        Db::startTrans();
        try {
            $deshangService = new DeshangUserPointsService();
            $deshangService->modifyUserPoints($add_data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            // 直接抛出原异常，保持异常类型（SystemException、PermissionException等）
            throw $e;
        }
    }
}
