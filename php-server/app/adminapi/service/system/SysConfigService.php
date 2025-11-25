<?php

namespace app\adminapi\service\system;

use app\deshang\base\service\BaseAdminService;

use app\common\dao\system\SysConfigDao;

use app\deshang\exceptions\CommonException;

use app\deshang\kv\KvManager;
use app\deshang\kv\keys\CacheKeyManager;


// 系统配置
class SysConfigService extends BaseAdminService
{




    public function getSysConfigList($data){
        $condition = [];

        if(isset($data['config_type']) && $data['config_type'] != ''){
            $condition[] = ['config_type', '=', $data['config_type']];
        }else{
            throw new CommonException('配置类型不能为空');
        }

        $result = (new SysConfigDao())->getSysConfigList($condition);

        if (is_array($result)) {
            $list_config = array();
            foreach ($result as $k => $v) {
                $list_config[$v['config_key']] = $v['config_value'];
            }
        }
        return $list_config;
    }

    // 获取单个配置 通过config_key获取
    public function getSysConfigInfoByKey($config_key){
        $condition = [];
        $condition[] = ['config_key', '=', $config_key];
        $result = (new SysConfigDao())->getSysConfigInfo($condition);
        return $result;
    }

    // 单个配置修改
    public function updateSysConfigInfo($data){
        $condition = [];
        $condition[] = ['config_key', '=', $data['config_key']];
        $condition[] = ['config_type', '=', $data['config_type']];

        // 判断是否存在
        $config_count = (new SysConfigDao())->getSysConfigCount($condition);
        if($config_count == 0){
            throw new CommonException($data['config_key'].'配置不存在');
        }
        if($config_count > 1){
            throw new CommonException($data['config_key'].'配置重复');
        }


        $result = (new SysConfigDao())->updateSysConfig($condition, ['config_value' => $data['config_value']]);

        // 清除缓存
        $cacheKey = sprintf(CacheKeyManager::SYS_CONFIG_KEY, $data['config_type']);
        KvManager::cache()->delete($cacheKey);

        return $result;
    }



    // 批量修改配置
    public function batchUpdateSysConfig($config_type,$data){
        foreach ($data as $k => $v) {
            $tmp = array();
            $tmp['config_value'] = $v;

            (new SysConfigDao())->updateSysConfig([['config_key', '=', $k],['config_type', '=', $config_type]], $tmp);
        }


        // 清除缓存
        $cacheKey = sprintf(CacheKeyManager::SYS_CONFIG_KEY, $config_type);
        KvManager::cache()->delete($cacheKey);


        return true;
    }




}