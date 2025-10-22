<?php

namespace app\adminapi\controller\system;

use app\deshang\base\controller\BaseAdminController;
use app\common\enum\system\SysPrinterProviderEnum;
use app\adminapi\service\system\SysConfigService;
use app\deshang\core\ThirdPartyLoader;

/**
 * @OA\Tag(name="admin-api/system/SysPrinterProvider", description="打印机服务商管理接口")
 */
// 打印机服务商 对 sys_config 表 配置修改
class SysPrinterProvider extends BaseAdminController
{
    /**
     * @OA\Get(
     *     path="/adminapi/system/printer-provider/list",
     *     summary="获取打印机服务商列表",
     *     tags={"admin-api/system/SysPrinterProvider"},
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
    // 获取打印机服务商列表
    public function getPrinterProviderList()
    {
        $printer_provider_list = SysPrinterProviderEnum::getPrinterProviderList();
        
        // 获取SysConfig表中设置的默认打印机服务商
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('printer_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        
        // 添加是否默认标识
        foreach ($printer_provider_list as $provider => &$item) {
            $item['is_default'] = ($provider == $default_provider) ? 1 : 0;
            
            // 获取配置状态
            $config_key = 'printer_config_' . $provider;
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
        
        return ds_json_success('操作成功', array_values($printer_provider_list));
    }

    /**
     * @OA\Get(
     *     path="/adminapi/system/printer-provider/info",
     *     summary="获取打印机服务商配置信息",
     *     tags={"admin-api/system/SysPrinterProvider"},
     *     @OA\Parameter(
     *         name="provider",
     *         in="query",
     *         required=true,
     *         description="打印机服务商标识",
     *         @OA\Schema(type="string", example="feie")
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
     *             @OA\Property(property="msg", type="string", example="打印机服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 获取打印机服务商配置信息
    public function getPrinterProviderInfo()
    {
        $printer_provider = input('param.provider');
        if (empty($printer_provider)) {
            return ds_json_error('打印机服务商参数不能为空');
        }
        
        // 获取默认打印机服务商列表及配置
        $printer_provider_list = SysPrinterProviderEnum::getPrinterProviderList();
        
        // 检查服务商是否存在
        if (!isset($printer_provider_list[$printer_provider])) {
            return ds_json_error('不支持的打印机服务商');
        }
        
        // 从数据库获取已保存的服务商配置信息
        $config_key = 'printer_config_' . $printer_provider;
        $sys_config_info = (new SysConfigService())->getSysConfigInfoByKey($config_key);
        
        $provider_info = $printer_provider_list[$printer_provider];
        
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
        $default_config = (new SysConfigService())->getSysConfigInfoByKey('printer_default_provider');
        $default_provider = !empty($default_config['config_value']) ? $default_config['config_value'] : '';
        $provider_info['is_default'] = ($printer_provider == $default_provider) ? 1 : 0;
        
        return ds_json_success('操作成功', $provider_info);
    }

    /**
     * @OA\Post(
     *     path="/adminapi/system/printer-provider/update",
     *     summary="更新打印机服务商配置信息",
     *     tags={"admin-api/system/SysPrinterProvider"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="打印机服务商配置信息",
     *         @OA\JsonContent(
     *             required={"provider"},
     *             @OA\Property(property="provider", type="string", example="feie", description="打印机服务商标识"),
     *             @OA\Property(
     *                 property="config",
     *                 type="object",
     *                 description="配置参数",
     *                 @OA\Property(property="user", type="string", example="YOUR_USERNAME", description="飞鹅云后台注册用户名"),
     *                 @OA\Property(property="ukey", type="string", example="YOUR_UKEY", description="飞鹅云后台登录生成的UKEY"),
     *                 @OA\Property(property="client_id", type="string", example="YOUR_CLIENT_ID", description="易联云应用ID"),
     *                 @OA\Property(property="client_secret", type="string", example="YOUR_CLIENT_SECRET", description="易联云应用密钥"),
     *                 @OA\Property(property="api_key", type="string", example="YOUR_API_KEY", description="API密钥"),
     *                 @OA\Property(property="api_secret", type="string", example="YOUR_API_SECRET", description="API密钥")
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
     *             @OA\Property(property="msg", type="string", example="打印机服务商参数不能为空")
     *         )
     *     )
     * )
     */
    // 更新打印机服务商配置信息
    public function updatePrinterProviderConfig()
    {
        $printer_provider = input('param.provider');
        if (empty($printer_provider)) {
            return ds_json_error('打印机服务商参数不能为空');
        }
        
        // 获取服务商列表
        $printer_provider_list = SysPrinterProviderEnum::getPrinterProviderList();
        
        // 检查服务商是否存在
        if (!isset($printer_provider_list[$printer_provider])) {
            return ds_json_error('不支持的打印机服务商');
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
            $default_config = $printer_provider_list[$printer_provider]['config'];
            
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
        $config_key = 'printer_config_' . $printer_provider;
        $config_data = [
            'config_type' => 'printer',
            'config_key' => $config_key,
            'config_value' => json_encode($valid_config['config'], JSON_UNESCAPED_UNICODE),
        ];
        (new SysConfigService())->updateSysConfigInfo($config_data);
        
        // 设置为默认服务商
        $is_default = isset($data['is_default']) ? (int)$data['is_default'] : 0;
        if ($is_default) {
            $default_data = [
                'config_type' => 'printer',
                'config_key' => 'printer_default_provider',
                'config_value' => $printer_provider,
            ];
            (new SysConfigService())->updateSysConfigInfo($default_data);
        }
        
        return ds_json_success('配置更新成功');
    }
}
