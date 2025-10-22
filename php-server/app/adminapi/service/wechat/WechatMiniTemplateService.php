<?php


namespace app\adminapi\service\wechat;


use app\deshang\base\service\BaseAdminService;
use app\common\dao\system\SysNoticeTplDao;
use app\deshang\service\wechat\DeshangMiniEasyService;
use app\deshang\exceptions\CommonException;
use app\common\enum\system\SysNoticeEnum;



class WechatMiniTemplateService extends BaseAdminService
{

    protected $sysNoticeTplDao;
    public function __construct()
    {
        parent::__construct();
        $this->sysNoticeTplDao = new SysNoticeTplDao();
    }




    public function getWechatMiniTemplateList()
    {

        // 获取小程序预设模板
        $presets = config('wechat_mini_template_presets');


        // 根据小程序 预设模板的key 
        $condition = [];
        $condition[] = ['key', 'in', array_keys($presets)];
        $list = $this->sysNoticeTplDao->getSysNoticeTplList($condition);

        foreach ($list as $key => $value) {
            $list[$key]['preset'] = $presets[$value['key']];
        }

        return $list;
    }

    /**
     * 同步微信小程序模板
     * @param array $data
     * @return array
     * @throws CommonException
     */
    public function syncWechatMiniTemplate(array $keys)
    {
        if (empty($keys)) {
            throw new CommonException('请选择要同步的模板');
        }

        // 微信小程序模板列表
        $condition = [];
        $condition[] = ['key', 'in', $keys];
        $sysNoticeTplList = $this->sysNoticeTplDao->getSysNoticeTplList($condition);

        // 小程序预设模板
        $presets = config('wechat_mini_template_presets');


        // 管理端使用系统配置，merchant_id = 0
        $deshangMiniEasyService = (new DeshangMiniEasyService())->init(0);

        foreach ($sysNoticeTplList as $tpl) {
            $key = $tpl['key'];
            $preset = $presets[$key] ?? [];

            if (empty($preset)) {
                throw new CommonException('预设配置不存在: ' . $key);
            }

            $res = $deshangMiniEasyService->addMessageTemplate($preset['tid'], $preset['kidList'], $preset['sceneDesc']);

            if (isset($res['errcode']) && $res['errcode'] == 0) {
                $updateData = [
                    'wechat_mini_template_id' => $res['priTmplId'],
                    // 同步后，默认开启
                    'wechat_mini_switch' => SysNoticeEnum::SWITCH_ON,
                ];

                $updated = $this->sysNoticeTplDao->updateSysNoticeTpl(['key' => $key], $updateData);
            } else {
                throw new CommonException($tpl['key'] . '添加微信小程序模板失败: '  . $res['errcode'] . ' ' . $res['errmsg']);
            }
        }

        return true;
    }



    /**
     * 删除微信小程序模板配置
     * @param string $key
     * @return array
     * @throws CommonException
     */
    public function deleteWechatMiniTemplate(string $key)
    {

        $sysNoticeTplInfo = $this->sysNoticeTplDao->getSysNoticeTplInfo(['key' => $key]);

        if (empty($sysNoticeTplInfo['wechat_mini_template_id'])) {
            throw new CommonException('微信小程序模板不存在');
        }

        // 删除模板
        $res = (new DeshangMiniEasyService())
            ->init(0)
            ->delMessageTemplate($sysNoticeTplInfo['wechat_mini_template_id']);

        if (isset($res['errcode']) && $res['errcode'] == 0) {
            $updateData = [
                'wechat_mini_template_id' => '',
                // 删除后，默认关闭
                'wechat_mini_switch' => SysNoticeEnum::SWITCH_OFF,
            ];

            $updated = $this->sysNoticeTplDao->updateSysNoticeTpl(['key' => $key], $updateData);
            return $updated;
        } else {
            throw new CommonException('删除微信小程序模板失败: ' . ($res['errmsg'] ?? '未知错误'));
        }
    }
}
