<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;


use app\common\dao\system\SysErrorLogsDao;


class SysErrorLogsService extends BaseAdminService
{


    public function __construct()
    {
        parent::__construct();
        $this->dao = new SysErrorLogsDao();
    }

    public function getSysErrorLogsPages($data)
    {
        $condition = [];
        
        // 控制器搜索
        if (!empty($data['controller'])) {
            $condition[] = ['controller', 'like', '%' . $data['controller'] . '%'];
        }
        
        // 根目录搜索
        if (!empty($data['root'])) {
            $condition[] = ['root', 'like', '%' . $data['root'] . '%'];
        }
        
        // IP地址搜索
        if (!empty($data['ip'])) {
            $condition[] = ['ip', 'like', '%' . $data['ip'] . '%'];
        }
        
        // 错误代码搜索
        if (!empty($data['code'])) {
            $condition[] = ['code', 'like', '%' . $data['code'] . '%'];
        }
        
        // 包含异常类名搜索（支持多选）
        if (!empty($data['include_exception_class'])) {
            // 如果是数组，使用 IN；如果是字符串，转换为数组
            $exceptionClasses = is_array($data['include_exception_class']) 
                ? $data['include_exception_class'] 
                : [$data['include_exception_class']];
            
            // 过滤空值
            $exceptionClasses = array_filter($exceptionClasses, function($value) {
                return !empty($value);
            });
            
            if (!empty($exceptionClasses)) {
                $condition[] = ['exception_class', 'in', $exceptionClasses];
            }
        }
        
        // 排除异常类名搜索（支持多选）
        if (!empty($data['exclude_exception_class'])) {
            // 如果是数组，使用 NOT IN；如果是字符串，转换为数组
            $excludeClasses = is_array($data['exclude_exception_class']) 
                ? $data['exclude_exception_class'] 
                : [$data['exclude_exception_class']];
            
            // 过滤空值
            $excludeClasses = array_filter($excludeClasses, function($value) {
                return !empty($value);
            });
            
            if (!empty($excludeClasses)) {
                $condition[] = ['exception_class', 'not in', $excludeClasses];
            }
        }
        
        // 请求耗时区间搜索
        if (isset($data['duration_min']) && $data['duration_min'] !== '') {
            $condition[] = ['duration', '>=', $data['duration_min']];
        }
        if (isset($data['duration_max']) && $data['duration_max'] !== '') {
            $condition[] = ['duration', '<=', $data['duration_max']];
        }
        
        $result = $this->dao->getErrorLogPages($condition);
        return $result;
    }

    public function createSysErrorLogs($data)
    {
        $result = $this->dao->createErrorLog($data);
        return $result;
    }



    public function getSysErrorLogsInfo($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = $this->dao->getErrorLogInfo($condition);
        return $result;
    }
}
