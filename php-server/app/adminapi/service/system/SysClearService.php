<?php

namespace app\adminapi\service\system;


use app\deshang\base\service\BaseAdminService;

use app\deshang\exceptions\CommonException;

use think\facade\Db;


// 系统清理
class SysClearService extends BaseAdminService
{

    public function __construct()
    {
        parent::__construct();
    }



    /**
     * 清除数据库表数据
     * @param string $table_name 表名
     * @param bool $use_truncate true使用TRUNCATE(快速清空), false使用DELETE(保留自增ID)
     * @return void
     */
    public function clearTableData(string $table_name, bool $use_truncate = true)
    {
        // 验证表名（只允许字母、数字、下划线）
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table_name)) {
            throw new CommonException('无效的表名');
        }

        if (!in_array($table_name, ['sys_access_logs', 'sys_error_logs', 'admin_logs'])) {
            throw new CommonException('表名错误');
        }

        // 获取数据库表前缀
        $prefix = config('database.connections.' . config('database.default'))['prefix'] ?? '';
        $fullTableName = $prefix . $table_name;

        // 检查表是否存在
        $tableExists = !empty(Db::query("SHOW TABLES LIKE '{$fullTableName}'"));
        if (!$tableExists) {
            throw new CommonException('表不存在');
        }

        if ($use_truncate) {
            Db::execute("TRUNCATE TABLE `{$fullTableName}`");
        } else {
            Db::execute("DELETE FROM `{$fullTableName}`");
        }
    }
}
