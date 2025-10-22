<?php

namespace app\adminapi\service\wechat;

use app\deshang\base\service\BaseAdminService;
use app\common\dao\system\SysNoticeTplDao;
use app\deshang\service\wechat\DeshangOfficialEasyService;
use app\deshang\exceptions\CommonException;
use app\common\enum\system\SysNoticeEnum;

class WechatOfficialTemplateService extends BaseAdminService
{
    protected $sysNoticeTplDao;

    public function __construct()
    {
        parent::__construct();
        $this->sysNoticeTplDao = new SysNoticeTplDao();
    }

    /**
     * 获取微信公众号模板列表
     * @return array
     */
    public function getWechatOfficialTemplateList()
    {
        // 获取公众号预设模板
        $presets = config('wechat_official_template_presets');

        // 根据公众号预设模板的key
        $condition = [];
        $condition[] = ['key', 'in', array_keys($presets)];
        $list = $this->sysNoticeTplDao->getSysNoticeTplList($condition);

        foreach ($list as $key => $value) {
            $list[$key]['preset'] = $presets[$value['key']];
        }

        return $list;
    }

    /**
     * 同步微信公众号模板
     * @param array $keys
     * @return array
     * @throws CommonException
     */
    public function syncWechatOfficialTemplate(array $keys)
    {
        if (empty($keys)) {
            throw new CommonException('请选择要同步的模板');
        }

        // 微信公众号模板列表
        $condition = [];
        $condition[] = ['key', 'in', $keys];
        $sysNoticeTplList = $this->sysNoticeTplDao->getSysNoticeTplList($condition);

        // 公众号预设模板
        $presets = config('wechat_official_template_presets');

        // 管理端使用系统配置，merchant_id = 0
        $deshangOfficialEasyService = (new DeshangOfficialEasyService())->init(0);

        foreach ($sysNoticeTplList as $tpl) {
            $key = $tpl['key'];
            $preset = $presets[$key] ?? [];

            if (empty($preset)) {
                throw new CommonException('预设配置不存在: ' . $key);
            }

            // 从预设配置中提取关键词名称列表
            $keywordNameList = [];
            if (isset($preset['template_fields']) && is_array($preset['template_fields'])) {
                foreach ($preset['template_fields'] as $field) {
                    if (isset($field['field_name'])) {
                        $keywordNameList[] = $field['field_name'];
                    }
                }
            }

            if (empty($keywordNameList)) {
                throw new CommonException('模板 ' . $key . ' 的关键词配置不能为空');
            }

            // 调用微信公众号添加模板接口，传递template_id_short和keyword_name_list
            $res = $deshangOfficialEasyService->addMessageTemplate($preset['template_id_short'], $keywordNameList);

            if (isset($res['errcode']) && $res['errcode'] == 0) {
                $updateData = [
                    'wechat_official_template_id' => $res['template_id'],
                    // 同步后，默认开启
                    'wechat_official_switch' => SysNoticeEnum::SWITCH_ON,
                ];

                $updated = $this->sysNoticeTplDao->updateSysNoticeTpl(['key' => $key], $updateData);
            } else {
                throw new CommonException($tpl['key'] . '添加微信公众号模板失败: ' . $res['errcode'] . ' ' . $res['errmsg']);
            }
        }

        return true;
    }

    /**
     * 删除微信公众号模板配置
     * @param string $key
     * @return array
     * @throws CommonException
     */
    public function deleteWechatOfficialTemplate(string $key)
    {
        $sysNoticeTplInfo = $this->sysNoticeTplDao->getSysNoticeTplInfo(['key' => $key]);

        if (empty($sysNoticeTplInfo['wechat_official_template_id'])) {
            throw new CommonException('微信公众号模板不存在');
        }

        // 删除模板
        $res = (new DeshangOfficialEasyService())
            ->init(0)
            ->delMessageTemplate($sysNoticeTplInfo['wechat_official_template_id']);

        if (isset($res['errcode']) && $res['errcode'] == 0) {
            $updateData = [
                'wechat_official_template_id' => '',
                // 删除后，默认关闭
                'wechat_official_switch' => SysNoticeEnum::SWITCH_OFF,
            ];

            $updated = $this->sysNoticeTplDao->updateSysNoticeTpl(['key' => $key], $updateData);
            return $updated;
        } else {
            throw new CommonException('删除微信公众号模板失败: ' . ($res['errmsg'] ?? '未知错误'));
        }
    }
} 