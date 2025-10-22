<?php


namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;

use app\deshang\exceptions\CommonException;

use app\common\dao\wechat\WechatSettingDao;

class DeshangOfficialMenuService extends BaseDeshangService
{

    public function __construct()
    {
        parent::__construct();
    }


    // 获取微信公众号菜单
    public function getWechatOfficialMenu(int $merchant_id)
    {
        // 获取微信设置  merchant_id = 0 为系统设置   wechat_official_menu字段 为json字符串 公众号菜单信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);
        $wechat_official_menu = isset($wechat_setting['wechat_official_menu']) ? json_decode($wechat_setting['wechat_official_menu'], true) : [];



        return $wechat_official_menu;
    }


    // 更新微信公众号菜单
    public function updateWechatOfficialMenu(int $merchant_id, array $data)
    {
        // 获取微信设置  merchant_id = 0 为系统设置   wechat_official_menu字段 为json字符串 公众号菜单信息
        $wechat_setting = (new WechatSettingDao())->getWechatSettingInfo([['merchant_id', '=', $merchant_id]]);

        // 检测微信公众号菜单格式
        $this->validateWechatOfficialMenu($data);

        // 如果微信设置不存在，则创建一个新的设置
        if (empty($wechat_setting)) {
            $setting_data = [
                'merchant_id' => $merchant_id,
                'wechat_official_menu' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ];
            $result = (new WechatSettingDao())->createWechatSetting($setting_data);
            return $result;
        } else {
            // 菜单格式检测通过，保存菜单
            $update_data = [
                'wechat_official_menu' => json_encode($data, JSON_UNESCAPED_UNICODE)
            ];

            // 更新现有设置
            $result = (new WechatSettingDao())->updateWechatSetting([['merchant_id', '=', $merchant_id]], $update_data);
            return $result;
        }
    }


    // 发布微信公众号菜单
    public function publishWechatOfficialMenu(int $merchant_id)
    {
        // 获取微信公众号菜单
        $wechat_official_menu = $this->getWechatOfficialMenu($merchant_id);

        if (empty($wechat_official_menu)) {
            throw new CommonException('微信公众号菜单配置为空，请先配置菜单');
        }


        // 发布微信公众号菜单
        $result = (new DeshangOfficialEasyService())->init($merchant_id)->createWechatMenu($wechat_official_menu);

        // 微信API成功返回格式：{"errcode":0,"errmsg":"ok"}
        // 失败返回格式：{"errcode":65303,"errmsg":"there is no selfmenu, please create selfmenu first rid: xxx"}
        if (isset($result['errcode'])) {
            if ($result['errcode'] == 0) {
                // 创建成功
                return $result;
            } else {
                // 创建失败
                $errorMsg = sprintf(
                    '发布微信公众号菜单失败，错误码：%s，错误信息：%s',
                    $result['errcode'],
                    $result['errmsg'] ?? '未知错误'
                );
                throw new CommonException($errorMsg);
            }
        } else {
            // 返回格式异常
            throw new CommonException('微信API返回格式异常：' . json_encode($result));
        }
    }





    /**
     * 验证微信公众号菜单格式
     * 
     * @param array $data 菜单数据
     * @throws CommonException 如果菜单格式不正确
     * @return void
     */
    private function validateWechatOfficialMenu(array $data): void
    {
        // 检查button数组
        if (!isset($data['button']) || !is_array($data['button'])) {
            throw new CommonException('菜单格式错误，必须包含button数组');
        }

        // 检查一级菜单数量
        if (count($data['button']) > 3) {
            throw new CommonException('一级菜单最多3个');
        }

        // 遍历检查一级菜单
        foreach ($data['button'] as $button) {
            // 检查必须字段
            if (!isset($button['name']) || empty($button['name'])) {
                throw new CommonException('菜单名称不能为空');
            }

            // 检查菜单名称长度
            if (mb_strlen($button['name'], 'UTF-8') > 16) {
                throw new CommonException('一级菜单名称不能超过16个字符');
            }

            // 检查子菜单
            if (isset($button['sub_button']) && is_array($button['sub_button'])) {
                // 检查子菜单数量
                if (count($button['sub_button']) > 5) {
                    throw new CommonException('子菜单最多5个');
                }

                // 遍历检查子菜单
                foreach ($button['sub_button'] as $sub_button) {
                    // 检查必须字段
                    if (!isset($sub_button['name']) || empty($sub_button['name'])) {
                        throw new CommonException('子菜单名称不能为空');
                    }

                    // 检查菜单名称长度
                    if (mb_strlen($sub_button['name'], 'UTF-8') > 60) {
                        throw new CommonException('子菜单名称不能超过60个字符');
                    }

                    // 检查子菜单类型
                    if (!isset($sub_button['type']) || empty($sub_button['type'])) {
                        throw new CommonException('子菜单类型不能为空');
                    }

                    // 根据不同类型检查必要参数
                    switch ($sub_button['type']) {
                        case 'view':
                            if (!isset($sub_button['url']) || empty($sub_button['url'])) {
                                throw new CommonException('view类型菜单必须包含url参数');
                            }
                            break;
                        case 'click':
                            if (!isset($sub_button['key']) || empty($sub_button['key'])) {
                                throw new CommonException('click类型菜单必须包含key参数');
                            }
                            break;
                        case 'miniprogram':
                            if (
                                !isset($sub_button['url']) || empty($sub_button['url']) ||
                                !isset($sub_button['appid']) || empty($sub_button['appid']) ||
                                !isset($sub_button['pagepath']) || empty($sub_button['pagepath'])
                            ) {
                                throw new CommonException('miniprogram类型菜单必须包含url、appid和pagepath参数');
                            }
                            break;
                        default:
                            throw new CommonException('不支持的菜单类型：' . $sub_button['type']);
                    }
                }
            } else {
                // 没有子菜单的情况，一级菜单必须有类型和对应参数
                if (!isset($button['type']) || empty($button['type'])) {
                    throw new CommonException('菜单类型不能为空');
                }

                // 根据不同类型检查必要参数
                switch ($button['type']) {
                    case 'view':
                        if (!isset($button['url']) || empty($button['url'])) {
                            throw new CommonException('view类型菜单必须包含url参数');
                        }
                        break;
                    case 'click':
                        if (!isset($button['key']) || empty($button['key'])) {
                            throw new CommonException('click类型菜单必须包含key参数');
                        }
                        break;
                    case 'miniprogram':
                        if (
                            !isset($button['url']) || empty($button['url']) ||
                            !isset($button['appid']) || empty($button['appid']) ||
                            !isset($button['pagepath']) || empty($button['pagepath'])
                        ) {
                            throw new CommonException('miniprogram类型菜单必须包含url、appid和pagepath参数');
                        }
                        break;
                    default:
                        throw new CommonException('不支持的菜单类型：' . $button['type']);
                }
            }
        }
    }
}
