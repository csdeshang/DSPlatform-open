<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;

use app\common\enum\system\SysSmsProviderEnum;
use app\adminapi\service\system\SysConfigService;
use app\deshang\core\ThirdPartyLoader;

/**
 * @OA\Tag(name="admin-api/system/SysSmsProvider", description="短信服务商管理接口")
 */
// 短信服务商  对 sys_config 表  配置修改
class SysSmsProvider extends BaseAdminController
{


    /**
     * @OA\Get(
     *     path="/adminapi/system/sms-provider/list",
     *     summary="获取短信服务商列表",
     *     tags={"admin-api/system/SysSmsProvider"},
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
    // 获取短信服务商列表
    public function getSmsProviderList()
    {
        
        $sms_provider_list = SysSmsProviderEnum::getSmsProviderList();
        
        // 获取SysConfig表中设置的默认短信服务商
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('sms_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        
        // 添加是否默认标识
        foreach ($sms_provider_list as $provider => &$item) {
            $item['is_default'] = ($provider == $default_provider) ? 1 : 0;
            
            // 获取配置状态
            $config_key = 'sms_config_' . $provider;
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
        
        return ds_json_success('操作成功', array_values($sms_provider_list));
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/sms-provider/info",
     *     summary="获取短信服务商配置信息",
     *     tags={"admin-api/system/SysSmsProvider"},
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         required=true,
     *         description="短信服务商标识",
     *         @OA\Schema(type="string", example="aliyun_sms")
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
     *             @OA\Property(property="msg", type="string", example="短信服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 获取短信服务商配置信息
    public function getSmsProviderInfo()
    {
        $sms_provider = input('param.provider');
        if (empty($sms_provider)) {
            return ds_json_error('短信服务商参数不能为空');
        }
        
        // 获取默认短信服务商列表及配置
        $sms_provider_list = SysSmsProviderEnum::getSmsProviderList();
        
        // 检查服务商是否存在
        if (!isset($sms_provider_list[$sms_provider])) {
            return ds_json_error('不支持的短信服务商');
        }
        
        // 从数据库获取已保存的服务商配置信息
        $config_key = 'sms_config_' . $sms_provider;
        $sys_config_info = (new SysConfigService())->getSysConfigInfoByKey($config_key);
        
        $provider_info = $sms_provider_list[$sms_provider];
        
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
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('sms_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        $provider_info['is_default'] = ($sms_provider == $default_provider) ? 1 : 0;
        
        return ds_json_success('操作成功', $provider_info);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/sms-provider/update",
     *     summary="更新短信服务商配置信息",
     *     tags={"admin-api/system/SysSmsProvider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="短信服务商配置信息",
     *         @OA\JsonContent(
     *             required={"provider"},
     *             @OA\Property(property="provider", type="string", example="aliyun_sms", description="短信服务商标识"),
     *             @OA\Property(
     *                 property="config",
     *                 type="object",
     *                 description="配置参数",
     *                 @OA\Property(property="access_key_id", type="string", example="YOUR_ACCESS_KEY"),
     *                 @OA\Property(property="access_key_secret", type="string", example="YOUR_SECRET_KEY"),
     *                 @OA\Property(property="sign_name", type="string", example="您的签名"),
     *                 @OA\Property(property="template_code", type="string", example="SMS_123456789")
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
     *             @OA\Property(property="msg", type="string", example="短信服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 更新短信服务商配置信息
    public function updateSmsProviderConfig()
    {
        $sms_provider = input('param.provider');
        if (empty($sms_provider)) {
            return ds_json_error('短信服务商参数不能为空');
        }
        
        // 获取服务商列表
        $sms_provider_list = SysSmsProviderEnum::getSmsProviderList();
        
        // 检查服务商是否存在
        if (!isset($sms_provider_list[$sms_provider])) {
            return ds_json_error('不支持的短信服务商');
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
            $default_config = $sms_provider_list[$sms_provider]['config'];
            
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
        $config_key = 'sms_config_' . $sms_provider;
        $config_data = [
            'config_type' => 'sms',
            'config_key' => $config_key,
            'config_value' => json_encode($valid_config['config'], JSON_UNESCAPED_UNICODE),
        ];
        (new SysConfigService())->updateSysConfigInfo($config_data);
        
        // 设置为默认服务商
        $is_default = isset($data['is_default']) ? (int)$data['is_default'] : 0;
        if ($is_default) {
            $default_data = [
                'config_type' => 'sms',
                'config_key' => 'sms_default_provider',
                'config_value' => $sms_provider,
            ];
            (new SysConfigService())->updateSysConfigInfo($default_data);
        }
        
        return ds_json_success('配置更新成功');
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/sms-provider/test",
     *     summary="测试短信配置",
     *     tags={"admin-api/system/SysSmsProvider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="测试短信发送信息",
     *         @OA\JsonContent(
     *             required={"provider", "mobile", "sms_template_id"},
     *             @OA\Property(property="provider", type="string", example="aliyun_sms", description="短信服务商标识"),
     *             @OA\Property(property="mobile", type="string", example="13800138000", description="测试手机号"),
     *             @OA\Property(property="sms_template_id", type="string", example="SMS_123456789", description="短信模板ID"),
     *             @OA\Property(property="sms_template_params", type="object", description="短信模板参数", example={"code": "123456"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="测试发送成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="测试发送成功")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="参数错误",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="参数不完整，缺少服务商、手机号或模板ID")
     *         )
     *     )
     * )
     */
    // 测试短信配置
    public function testSmsProvider()
    {
        $sms_provider = input('param.provider');
        $mobile = input('param.mobile');
        $sms_template_id = input('param.sms_template_id');
        $sms_template_params = input('param.sms_template_params');

        if (empty($sms_provider) || empty($mobile) || empty($sms_template_id)) {
            return ds_json_error('参数不完整，缺少服务商、手机号或模板ID');
        }

        $sms = ThirdPartyLoader::sms($sms_provider);

        $result = $sms->send($mobile, $sms_template_id, $sms_template_params);

        halt($result);

        exit;


    }




}