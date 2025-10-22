<?php

namespace app\deshang\service\wechat;

use app\deshang\service\BaseDeshangService;
use app\common\dao\wechat\WechatPushLogDao;
use app\common\enum\wechat\WechatPushEnum;
use app\common\dao\user\UserIdentityDao;

/**
 * 微信公众号消息处理服务类
 */
class DeshangOfficialMessageService extends BaseDeshangService
{
    /**
     * @var DeshangOfficialEasyService 微信公众号服务类
     */
    protected $easyService;

    /**
     * @var int 商户ID
     */
    protected $merchant_id;


    public function __construct(DeshangOfficialEasyService $easyService, int $merchant_id)
    {
        parent::__construct();
        $this->easyService = $easyService;
        $this->merchant_id = $merchant_id;
    }

    /**
     * 处理具体的消息内容
     * 
     * @param array $message 消息数组
     * @return mixed
     */
    public function processMessage(array $message)
    {
        // 记录消息日志
        (new WechatPushLogDao())->createWechatPushLog([
            'merchant_id' => $this->merchant_id,
            'type' => WechatPushEnum::TYPE_OFFICIAL,
            'message' => json_encode($message),
        ]);

        // 根据消息类型处理
        // https://developers.weixin.qq.com/doc/service/guide/product/message/Receiving_standard_messages.html
        switch ($message['MsgType']) {
            case 'text':
                // 文本消息
                return $this->handleTextMessage($message);
            case 'image':
                // 图片消息
                return '图片消息功能开发中';
                // return $this->handleImageMessage($message);
            case 'voice':
                // 语音消息
                return '语音消息功能开发中';
                // return $this->handleVoiceMessage($message);
            case 'video':
                // 视频消息
                return '视频消息功能开发中';
                // return $this->handleVideoMessage($message);
            case 'shortvideo':
                // 小视频消息
                return '小视频消息功能开发中';
                // return $this->handleShortVideoMessage($message);
            case 'location':
                // 地理位置消息
                return '地理位置消息功能开发中';
                // return $this->handleLocationMessage($message);
            case 'link':
                // 链接消息
                return '链接消息功能开发中';
                // return $this->handleLinkMessage($message);
            case 'file':
                // 文件消息
                return '文件消息功能开发中';
                // return $this->handleFileMessage($message);
            case 'event':
                // 事件消息
                return $this->handleEventMessage($message);
            default:
                // 默认消息
                return '收到未知消息';
                // return $this->handleDefaultMessage($message);
        }
    }

    /**
     * 处理文本消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleTextMessage(array $message)
    {
        $content = $message['Content'] ?? '';
        $fromUser = $message['FromUserName'] ?? '';
        
        // 这里可以根据业务需求处理文本消息
        // 例如：关键词回复、智能客服等
        
        // 简单的关键词回复示例
        if (strpos($content, '帮助') !== false) {
            return '您好！欢迎使用我们的服务，如需帮助请联系客服。';
        }
        
        if (strpos($content, '菜单') !== false) {
            return '请点击下方菜单进行操作。';
        }
        
        // 默认回复
        return '感谢您的消息，我们已收到！';
    }

    /**
     * 处理图片消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleImageMessage(array $message)
    {
        $mediaId = $message['MediaId'] ?? '';
        $picUrl = $message['PicUrl'] ?? '';
        
        // 处理图片消息逻辑
        // 可以保存图片、进行图片识别等
        
        return '已收到您的图片！';
    }

    /**
     * 处理语音消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleVoiceMessage(array $message)
    {
        $mediaId = $message['MediaId'] ?? '';
        $format = $message['Format'] ?? '';
        $recognition = $message['Recognition'] ?? ''; // 语音识别结果
        
        // 处理语音消息逻辑
        if (!empty($recognition)) {
            return "您说的是：{$recognition}";
        }
        
        return '已收到您的语音消息！';
    }

    /**
     * 处理视频消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleVideoMessage(array $message)
    {
        $mediaId = $message['MediaId'] ?? '';
        $thumbMediaId = $message['ThumbMediaId'] ?? '';
        
        // 处理视频消息逻辑
        
        return '已收到您的视频！';
    }

    /**
     * 处理小视频消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleShortVideoMessage(array $message)
    {
        $mediaId = $message['MediaId'] ?? '';
        $thumbMediaId = $message['ThumbMediaId'] ?? '';
        
        // 处理小视频消息逻辑
        
        return '已收到您的小视频！';
    }

    /**
     * 处理地理位置消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleLocationMessage(array $message)
    {
        $locationX = $message['Location_X'] ?? '';
        $locationY = $message['Location_Y'] ?? '';
        $scale = $message['Scale'] ?? '';
        $label = $message['Label'] ?? '';
        
        // 处理地理位置消息逻辑
        
        return "已收到您的位置信息：{$label}";
    }

    /**
     * 处理链接消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleLinkMessage(array $message)
    {
        $title = $message['Title'] ?? '';
        $description = $message['Description'] ?? '';
        $url = $message['Url'] ?? '';
        
        // 处理链接消息逻辑
        
        return "已收到您分享的链接：{$title}";
    }

    /**
     * 处理文件消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleFileMessage(array $message)
    {
        $title = $message['Title'] ?? '';
        $description = $message['Description'] ?? '';
        $fileKey = $message['FileKey'] ?? '';
        $fileMd5 = $message['FileMd5'] ?? '';
        $fileTotalLen = $message['FileTotalLen'] ?? '';
        
        // 处理文件消息逻辑
        
        return "已收到您的文件：{$title}";
    }

    /**
     * 处理事件消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleEventMessage(array $message)
    {
        $event = $message['Event'] ?? '';
        
        switch ($event) {
            case 'subscribe':
                return $this->handleSubscribeEvent($message);
            case 'unsubscribe':
                return $this->handleUnsubscribeEvent($message);
            case 'CLICK':
                // 菜单点击事件
                return '菜单点击事件功能开发中';
                // return $this->handleClickEvent($message);
            case 'VIEW':
                // 菜单跳转事件
                return '菜单跳转事件功能开发中';
                // return $this->handleViewEvent($message);
            case 'SCAN':
                // 扫码事件
                return '扫码事件功能开发中';
                // return $this->handleScanEvent($message);
            case 'LOCATION':
                // 地理位置事件
                return '地理位置事件功能开发中';
                // return $this->handleLocationEvent($message);
            default:
                // 默认事件
                return '收到未知事件';
                // return $this->handleDefaultEvent($message);
        }
    }

    /**
     * 处理关注事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleSubscribeEvent(array $message)
    {
        $fromUser = $message['FromUserName'] ?? '';
        // 事件KEY值，qrscene_为前缀，后面为二维码的场景值ID
        $eventKey = $message['EventKey'] ?? '';
        
        // 获取对应用户信息 
        $userInfo = $this->easyService->getWechatUserInfo($fromUser);

        

        // 如果unionid 存在 则通过unionid 判断用户是否存在 进行关系绑定 用户模板通知等
        if(isset($userInfo['unionid']) && !empty($userInfo['unionid'])){

            // 通过unionid 判断用户是否存在
            $user_identity = (new UserIdentityDao())->getUserIdentityInfo([['wx_unionid', '=', $userInfo['unionid']], ['merchant_id', '=', $this->merchant_id]]);

            // unionid 已存在  用户通过其他方式登录
            if(!empty($user_identity)){
                // user_identity 
                if(empty($user_identity['wx_event_openid'])){
                    // 用户已存在 且 没有 wx_event_openid 则更新 wx_event_openid 
                    (new UserIdentityDao())->updateUserIdentity([['wx_unionid', '=', $userInfo['unionid']], ['merchant_id', '=', $this->merchant_id]], ['wx_event_openid' => $fromUser]);
                    return '感谢您的关注！绑定成功';
                }else{
                    // 用户已存在 且 有 wx_event_openid  验证 是否与 fromUser 一致
                    if($user_identity['wx_event_openid'] == $fromUser){
                        // 一致 则不处理  正常返回
                        return '感谢您的关注！欢迎使用我们的服务。';
                    }else{
                        // 不一致 则更新 wx_event_openid
                        // (new UserIdentityDao())->updateUserIdentity([['wx_unionid', '=', $userInfo['unionid']], ['merchant_id', '=', $this->merchant_id]], ['wx_event_openid' => $fromUser]);
                        return '系统错误 wx_event_openid 与 fromUser 不一致';
                    }
                }
                
            }else{
                // 用户不存在 
                return '感谢您的关注！欢迎使用我们的服务。';
            }

        }else{
            // 感谢您的关注！欢迎使用我们的服务。
            return '系统获取用户信息失败';
        }
    }

    /**
     * 处理取消关注事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleUnsubscribeEvent(array $message)
    {
        $fromUser = $message['FromUserName'] ?? '';
        
        // 获取对应用户信息 
        $userInfo = $this->easyService->getWechatUserInfo($fromUser);

        if(isset($userInfo['errcode']) || empty($userInfo['unionid'])){
            // 更新 user_identity 的 wx_event_openid  取消发送模板消息
            (new UserIdentityDao())->updateUserIdentity([['wx_event_openid', '=', $fromUser], ['merchant_id', '=', $this->merchant_id]], ['wx_event_openid' => '']);
            return '取消关注成功';
        }else{
            return '取消关注失败';
        }



        // 处理取消关注事件逻辑
        // 可以更新用户状态等
        
        return ''; // 取消关注事件不需要回复
    }

    /**
     * 处理菜单点击事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleClickEvent(array $message)
    {
        $eventKey = $message['EventKey'] ?? '';
        $fromUser = $message['FromUserName'] ?? '';
        
        // 根据菜单key处理不同的点击事件
        switch ($eventKey) {
            case 'MENU_HELP':
                return '这里是帮助信息...';
            case 'MENU_CONTACT':
                return '联系我们：客服电话 400-xxx-xxxx';
            default:
                return '感谢您的点击！';
        }
    }

    /**
     * 处理菜单跳转事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleViewEvent(array $message)
    {
        $eventKey = $message['EventKey'] ?? '';
        $fromUser = $message['FromUserName'] ?? '';
        
        // 处理菜单跳转事件逻辑
        // 一般不需要回复，因为用户会跳转到网页
        
        return '';
    }

    /**
     * 处理扫码事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleScanEvent(array $message)
    {
        $eventKey = $message['EventKey'] ?? '';
        $ticket = $message['Ticket'] ?? '';
        $fromUser = $message['FromUserName'] ?? '';
        
        // 处理扫码事件逻辑
        
        return '扫码成功！';
    }

    /**
     * 处理地理位置事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleLocationEvent(array $message)
    {
        $latitude = $message['Latitude'] ?? '';
        $longitude = $message['Longitude'] ?? '';
        $precision = $message['Precision'] ?? '';
        
        // 处理地理位置事件逻辑
        
        return '已收到您的位置信息！';
    }

    /**
     * 处理默认事件
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleDefaultEvent(array $message)
    {
        return '收到未知事件';
    }

    /**
     * 处理默认消息
     * 
     * @param array $message
     * @return string|Message
     */
    protected function handleDefaultMessage(array $message)
    {
        return '收到未知类型的消息';
    }



}
