<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\common\enum\system\SysExpressProviderEnum;
use app\adminapi\service\system\SysConfigService;
use app\deshang\core\ThirdPartyLoader;
use app\common\dao\system\SysExpressDao;

/**
 * @OA\Tag(name="admin-api/system/SysExpressProvider", description="快递查询服务商管理接口")
 */
// 快递查询服务商 对 sys_config 表 配置修改
class SysExpressProvider extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/express-provider/list",
     *     summary="获取快递查询服务商列表",
     *     tags={"admin-api/system/SysExpressProvider"},
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    // 获取快递查询服务商列表
    public function getExpressProviderList()
    {
        $express_provider_list = SysExpressProviderEnum::getExpressProviderList();
        
        // 获取SysConfig表中设置的默认快递查询服务商
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('express_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        
        // 添加是否默认标识
        foreach ($express_provider_list as $provider => &$item) {
            $item['is_default'] = ($provider == $default_provider) ? 1 : 0;
            
            // 获取配置状态
            $config_key = 'express_config_' . $provider;
            $provider_config = (new SysConfigService())->getSysConfigInfoByKey($config_key);
            if (!empty($provider_config['config_value'])) {
                $config_value = is_array($provider_config['config_value']) 
                    ? $provider_config['config_value'] 
                    : json_decode($provider_config['config_value'], true);
                
                // 合并配置参数到config中
                if (isset($config_value) && is_array($config_value)) {
                    $item['config'] = array_merge($item['config'], $config_value);
                }
            }
        }
        
        return ds_json_success('操作成功', array_values($express_provider_list));
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/express-provider/info",
     *     summary="获取快递查询服务商配置信息",
     *     tags={"admin-api/system/SysExpressProvider"},
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         required=true,
     *         description="快递查询服务商标识",
     *         @OA\Schema(type="string", example="kuaidiniao")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="操作成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="参数错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="快递查询服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 获取快递查询服务商配置信息
    public function getExpressProviderInfo()
    {
        $express_provider = input('param.provider');
        if (empty($express_provider)) {
            return ds_json_error('快递查询服务商参数不能为空');
        }
        
        // 获取默认快递查询服务商列表及配置
        $express_provider_list = SysExpressProviderEnum::getExpressProviderList();
        
        // 检查服务商是否存在
        if (!isset($express_provider_list[$express_provider])) {
            return ds_json_error('不支持的快递查询服务商');
        }
        
        // 从数据库获取已保存的服务商配置信息
        $config_key = 'express_config_' . $express_provider;
        $sys_config_info = (new SysConfigService())->getSysConfigInfoByKey($config_key);
        
        $provider_info = $express_provider_list[$express_provider];
        
        // 合并数据库配置
        if (!empty($sys_config_info) && !empty($sys_config_info['config_value'])) {
            // 如果数据库中有保存的配置，则使用数据库中的配置
            $saved_config = is_array($sys_config_info['config_value']) 
                ? $sys_config_info['config_value'] 
                : json_decode($sys_config_info['config_value'], true);
            
            // 合并配置参数到config中
            if (isset($saved_config) && is_array($saved_config)) {
                $provider_info['config'] = array_merge($provider_info['config'], $saved_config);
            }
        }
        
        // 检查是否为默认服务商
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('express_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        $provider_info['is_default'] = ($express_provider == $default_provider) ? 1 : 0;
        
        return ds_json_success('操作成功', $provider_info);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/express-provider/update",
     *     summary="更新快递查询服务商配置信息",
     *     tags={"admin-api/system/SysExpressProvider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="快递查询服务商配置信息",
     *         @OA\JsonContent(
     *             required={"provider"},
     *             @OA\Property(property="provider", type="string", example="kuaidiniao", description="快递查询服务商标识"),
     *             @OA\Property(
     *                 property="config",
     *                 type="object",
     *                 description="配置参数",
     *                 @OA\Property(property="ebusiness_id", type="string", example="YOUR_EBUSINESS_ID", description="快递鸟商户ID"),
     *                 @OA\Property(property="app_key", type="string", example="YOUR_APP_KEY", description="快递鸟APP密钥"),
     *                 @OA\Property(property="customer", type="string", example="YOUR_CUSTOMER", description="快递100客户编号"),
     *                 @OA\Property(property="key", type="string", example="YOUR_KEY", description="快递100授权码")
     *             ),
     *             @OA\Property(property="is_default", type="integer", example=1, description="是否设为默认服务商")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="配置更新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="配置更新成功")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="参数错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="快递查询服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 更新快递查询服务商配置信息
    public function updateExpressProviderConfig()
    {
        $express_provider = input('param.provider');
        if (empty($express_provider)) {
            return ds_json_error('快递查询服务商参数不能为空');
        }
        
        // 获取服务商列表
        $express_provider_list = SysExpressProviderEnum::getExpressProviderList();
        
        // 检查服务商是否存在
        if (!isset($express_provider_list[$express_provider])) {
            return ds_json_error('不支持的快递查询服务商');
        }
        
        // 获取提交的配置参数
        $data = input('param.');
        if (empty($data)) {
            return ds_json_error('配置参数不能为空');
        }
        
        // 提取需要保存的参数
        $valid_config = [
            'config' => []
        ];
        
        // 处理config字段
        if (isset($data['config']) && is_array($data['config'])) {
            // 获取原始配置结构作为参考
            $default_config = $express_provider_list[$express_provider]['config'];
            
            // 只保留与默认配置中存在的键
            foreach ($default_config as $key => $value) {
                if (isset($data['config'][$key])) {
                    $valid_config['config'][$key] = $data['config'][$key];
                } else {
                    $valid_config['config'][$key] = $value;
                }
            }
        }
        
        // 保存配置
        $config_key = 'express_config_' . $express_provider;
        $config_data = [
            'config_type' => 'express',
            'config_key' => $config_key,
            'config_value' => json_encode($valid_config['config'], JSON_UNESCAPED_UNICODE),
        ];
        (new SysConfigService())->updateSysConfigInfo($config_data);
        
        // 设置为默认服务商
        $is_default = isset($data['is_default']) ? (int)$data['is_default'] : 0;
        if ($is_default) {
            $default_data = [
                'config_type' => 'express',
                'config_key' => 'express_default_provider',
                'config_value' => $express_provider,
            ];
            (new SysConfigService())->updateSysConfigInfo($default_data);
        }
        
        return ds_json_success('配置更新成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/express-provider/query",
     *     summary="测试快递查询配置",
     *     tags={"admin-api/system/SysExpressProvider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="测试快递查询信息",
     *         @OA\JsonContent(
     *             required={"provider", "express_no", "express_code"},
     *             @OA\Property(property="provider", type="string", example="kuaidiniao", description="快递查询服务商标识"),
     *             @OA\Property(property="express_no", type="string", example="YT1234567890", description="快递单号"),
     *             @OA\Property(property="express_code", type="string", example="YTO", description="快递公司编码"),
     *             @OA\Property(property="phone", type="string", example="13800138000", description="手机号码后四位（部分快递公司需要）")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="查询成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="查询成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="参数错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="参数不完整，缺少服务商、快递单号或快递公司编码")
     *         )
     *     )
     * )
     */
    // 测试快递查询配置
    public function testExpressProvider()
    {
        $express_provider = input('param.provider');
        $express_no = input('param.express_no');
        $express_code = input('param.express_code');
        $phone = input('param.phone', '');

        if (empty($express_provider) || empty($express_no) || empty($express_code)) {
            return ds_json_error('参数不完整，缺少服务商、快递单号或快递公司编码');
        }

        try {
            $express = ThirdPartyLoader::express($express_provider);
            
            $result = $express->query($express_no, $express_code, $phone);
            
            return ds_json_success('查询成功', $result);
        } catch (\Exception $e) {
            return ds_json_error('查询失败：' . $e->getMessage());
        }
    }

}
