<?php


namespace app\deshang\service\trade;

use app\deshang\service\BaseDeshangService;

use app\common\enum\trade\TradePaymentConfigEnum;
use app\deshang\exceptions\CommonException;
use app\deshang\exceptions\PayException;


use app\common\dao\trade\TradePaymentConfigDao;
use app\common\enum\trade\TradePayEnum;

class DeshangTradePaymentConfigService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
        $this->dao = new TradePaymentConfigDao();
    }

    // 获取商户支付配置列表(用于支付配置列表) merchant_id 为 0 时 获取系统支付配置列表
    public function getPaymentConfigByMerchant($merchant_id)
    {


        $config_list = $this->dao->getPaymentConfigList([['merchant_id', '=', $merchant_id]]);



        // 按场景归类配置
        $grouped_config = [];
        foreach ($config_list as $config) {
            $grouped_config[$config['payment_scene']][] = $config;
        }


        $result = [];
        // 获取系统支持支付列表
        $system_support_payment_list = TradePaymentConfigEnum::getSystemSupportPaymentList();

        // 合并系统支持支付列表和数据库支付配置
        foreach ($system_support_payment_list as $scene => $channels) {
            $result[$scene] = [
                'name' => TradePaymentConfigEnum::getPaymentSceneDesc($scene),
            ];
            // 初始化系统支持支付列表  用于前端展示所有支付渠道
            foreach ($channels as $channel) {
                $result[$scene]['payment_config_list'][$channel] = [
                    'payment_name' => TradePaymentConfigEnum::getPaymentChannelDesc($channel),
                    'icon' => TradePaymentConfigEnum::getPaymentChannelIcon($channel),
                    'id' => 0,
                    'merchant_id' => $merchant_id,
                    'payment_channel' => $channel,
                    'payment_scene' => $scene,
                    'config_data' => [],
                    'is_enabled' => 0,
                    'sort' => 0,
                ];
            }

            // 如果数据库有配置，则覆盖默认配置
            if (isset($grouped_config[$scene])) {
                foreach ($grouped_config[$scene] as $config) {
                    $result[$scene]['payment_config_list'][$config['payment_channel']]['id'] = $config['id'];
                    $result[$scene]['payment_config_list'][$config['payment_channel']]['merchant_id'] = $config['merchant_id'];
                    $result[$scene]['payment_config_list'][$config['payment_channel']]['payment_scene'] = $config['payment_scene'];
                    $result[$scene]['payment_config_list'][$config['payment_channel']]['is_enabled'] = $config['is_enabled'];
                    $result[$scene]['payment_config_list'][$config['payment_channel']]['sort'] = $config['sort'];
                }
            }
        }

        // 处理返回数据
        return $result;
    }



    // 获取可用支付列表(用于支付选择)
    public function getAvailablePaymentList($data)
    {

        $condition = [];
        $condition[] = ['merchant_id', '=', $data['merchant_id']];
        $condition[] = ['payment_scene', '=', $data['payment_scene']];
        $condition[] = ['is_enabled', '=', 1];

        // 如果是充值，则支付类型不显示余额支付类型
        if($data['pay_type'] == TradePayEnum::SOURCE_TYPE_RECHARGE){
            $condition[] = ['payment_channel', '!=', TradePaymentConfigEnum::CHANNEL_BALANCE];
        }


        $field = 'id,merchant_id,payment_scene,payment_channel,is_enabled,sort';
        $config_list = $this->dao->getPaymentConfigList($condition, $field);



        foreach ($config_list as $key => $config) {
            $config_list[$key]['payment_channel_icon'] = TradePaymentConfigEnum::getPaymentChannelIcon($config['payment_channel']);
            $config_list[$key]['payment_name'] = TradePaymentConfigEnum::getPaymentChannelDesc($config['payment_channel']);
        }
        return $config_list;
    }


    // 获取支付配置信息
    public function getPaymentConfigInfoById($id)
    {

        $info = $this->dao->getPaymentConfigInfoById($id);

        // 将 config_data 转换为数组
        $info['config_data'] = $this->getConfigData($info);
        return $info;
    }


    // 获取支付配置信息
    public function getPaymentConfigInfo($data)
    {

        $condition = [];
        $condition[] = ['merchant_id', '=', $data['merchant_id']];
        $condition[] = ['payment_channel', '=', $data['payment_channel']];
        $condition[] = ['payment_scene', '=', $data['payment_scene']];
        $condition[] = ['is_enabled', '=', 1];
        $info = $this->dao->getPaymentConfigInfo($condition);

        // 将 config_data 转换为数组
        $info['config_data'] = $this->getConfigData($info);
        return $info;
    }

    // 创建支付配置
    public function createPaymentConfig($data)
    {
        $this->validate($data, 'app\deshang\service\trade\validate\TradePaymentConfigValidator.create');


        // 检查是否存在相同配置
        $condition = [];
        $condition[] = ['merchant_id', '=', $data['merchant_id']];
        $condition[] = ['payment_channel', '=', $data['payment_channel']];
        $condition[] = ['payment_scene', '=', $data['payment_scene']];

        $config = $this->dao->getPaymentConfigInfo($condition);


        if ($config) {
            throw new CommonException('配置已存在');
        }

        // 将 config_data 转换为数组
        if($data['payment_channel'] != TradePaymentConfigEnum::CHANNEL_BALANCE){
            $data['config_data'] = $this->setConfigData($data);
        }

        $result = $this->dao->createPaymentConfig($data);
        return $result;
    }

    // 更新支付配置
    public function updatePaymentConfig($data)
    {
        $this->validate($data, 'app\deshang\service\trade\validate\TradePaymentConfigValidator.update');


        $condition = [];
        $condition[] = ['id', '=', $data['id']];
        $condition[] = ['merchant_id', '=', $data['merchant_id']];
        $condition[] = ['payment_channel', '=', $data['payment_channel']];
        $condition[] = ['payment_scene', '=', $data['payment_scene']];


        // 将 config_data 转换为数组
        if($data['payment_channel'] != TradePaymentConfigEnum::CHANNEL_BALANCE){
            $data['config_data'] = $this->setConfigData($data);
        }


        $result = $this->dao->updatePaymentConfig($condition, $data);
        return $result;



    }

    // 获取支付配置数据
    private function setConfigData($data)
    {
        $config_data = [];

        if ($data['payment_channel'] == TradePaymentConfigEnum::CHANNEL_ALIPAY) {
            // 支付宝 app 公钥证书路径  (内容转成路径)
            $app_public_cert_path = $this->setCertContent($data, 'app_public_cert' ,'crt');
            // 支付宝 公钥证书路径
            $alipay_public_cert_path = $this->setCertContent($data, 'alipay_public_cert' ,'crt');
            // 支付宝根证书 路径
            $alipay_root_cert_path = $this->setCertContent($data, 'alipay_root_cert' ,'crt');

            $config_data = [
                // 支付宝分配的 app_id
                'app_id' => isset($data['config_data']['app_id']) ? $data['config_data']['app_id'] : '',
                // 应用私钥
                'app_secret_cert' => isset($data['config_data']['app_secret_cert']) ? $data['config_data']['app_secret_cert'] : '',
                // 应用公钥证书 路径
                'app_public_cert_path' => $app_public_cert_path,
                // 支付宝公钥证书 路径
                'alipay_public_cert_path' => $alipay_public_cert_path,
                // 支付宝根证书 路径
                'alipay_root_cert_path' => $alipay_root_cert_path,
            ];
        } elseif ($data['payment_channel'] == TradePaymentConfigEnum::CHANNEL_WECHAT) {

            // 微信支付公钥证书路径
            $wechat_public_cert_path = $this->setCertContent($data, 'wechat_public_cert' ,'pem');
            $mch_public_cert_path = $this->setCertContent($data, 'mch_public_cert' ,'pem');
            $mch_secret_cert_path = $this->setCertContent($data, 'mch_secret_cert' ,'pem');


            $config_data = [
                // app_id [选填]
                'app_id' => isset($data['config_data']['app_id']) ? $data['config_data']['app_id'] : '',
                // 商户号
                'mch_id' => isset($data['config_data']['mch_id']) ? $data['config_data']['mch_id'] : '',
                // 商户秘钥(V3)
                'mch_secret_key' => isset($data['config_data']['mch_secret_key']) ? $data['config_data']['mch_secret_key'] : '',
                // 商户私钥
                'mch_secret_cert' => isset($data['config_data']['mch_secret_cert']) ? $data['config_data']['mch_secret_cert'] : '',
                // 商户私钥路径
                'mch_secret_cert_path' => $mch_secret_cert_path,
                // 商户公钥证书路径
                'mch_public_cert_path' => $mch_public_cert_path,
                // 微信支付公钥ID及证书路径
                'wechat_public_cert_id' => isset($data['config_data']['wechat_public_cert_id']) ? $data['config_data']['wechat_public_cert_id'] : '',
                'wechat_public_cert_path' => $wechat_public_cert_path,
            ];
        }else{
            throw new PayException('支付方式不支持');
        }
        return json_encode($config_data);
    }

    // 设置支付配置数据
    private function getConfigData($data)
    {
        // 检查 config_data 是否为 null 或空字符串
        if (empty($data['config_data'])) {
            return [];
        }

        $config_data = json_decode($data['config_data'], true);

        // 检查 json_decode 是否成功
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        if ($data['payment_channel'] == TradePaymentConfigEnum::CHANNEL_ALIPAY) {
            // 获取本地文件内的证书内容
            $config_data['app_public_cert'] = $this->getCertContent($config_data, 'app_public_cert_path');
            $config_data['alipay_public_cert'] = $this->getCertContent($config_data, 'alipay_public_cert_path');
            $config_data['alipay_root_cert'] = $this->getCertContent($config_data, 'alipay_root_cert_path');
        } elseif ($data['payment_channel'] == TradePaymentConfigEnum::CHANNEL_WECHAT) {

            // 读取本地文件内的证书内容
            $config_data['wechat_public_cert'] = $this->getCertContent($config_data, 'wechat_public_cert_path');
            $config_data['mch_public_cert'] = $this->getCertContent($config_data, 'mch_public_cert_path');
            $config_data['mch_secret_cert'] = $this->getCertContent($config_data, 'mch_secret_cert_path');
        }



        return $config_data;
    }


    // 设置证书内容 存储到本地
    /**
     * 设置证书内容 存储到本地
     * @param array $data 配置数据
     * @param string $key 证书内容键名
     * @param string $suffix 文件保存后缀
     * @return string 证书内容路径
     */
    private function setCertContent(array $data, string $key ,string $suffix = 'crt')
    {
        if (!isset($data['config_data'][$key])) {
            return '';
        }

        // 证书内容 存储到本地
        $path = root_path() . '/cert/' . $data['merchant_id'] . '_' . $data['payment_channel'] . '_' . $data['payment_scene'] . '_' . $key . '.' . $suffix;
        // 不同场景 相同支付方式， 证书内容相同， 所以 证书内容 只存储一份
        // $path = root_path() . '/cert/' . $data['merchant_id'] . '_' . $data['payment_channel'] . '_' . $key . '.crt';
        file_put_contents($path, $data['config_data'][$key]);
        return $path;
    }

    // 获取证书内容
    private function getCertContent($config_data, $key)
    {

        // 验证配置数组是否存在
        if (!isset($config_data[$key])) {
            return '';
        }

        $path = $config_data[$key];

        // 检查文件路径是否存在
        if (!file_exists($path)) {
            return '';
        }
        $content = file_get_contents($path);
        return $content;
    }





    // 删除支付配置
    public function deletePaymentConfig($id)
    {
        $condition = [];
        $condition[] = ['id', '=', $id];
        $result = $this->dao->deletePaymentConfig($condition);
        return $result;
    }
}
