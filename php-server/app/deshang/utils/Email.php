<?php

namespace app\deshang\utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * 邮件发送工具类
 * 
 * 基于PHPMailer库封装的邮件发送功能
 * 
 */



class Email
{
    /**
     * @var PHPMailer PHPMailer实例
     */
    protected $mailer;

    /**
     * @var array 默认配置
     */
    protected $config = [
        'smtp_host' => '',      // SMTP服务器地址
        'smtp_port' => 465,     // SMTP服务器端口
        'smtp_user' => '',      // SMTP用户名
        'smtp_pass' => '',      // SMTP密码
        'smtp_secure' => 'ssl', // 安全协议(ssl/tls)
        'smtp_debug' => 0,      // 调试级别(0-4)
        'charset' => 'utf-8',   // 字符集
        'smtp_from_email' => '',     // 发件人邮箱
        'smtp_from_name' => '',      // 发件人姓名
    ];

    /**
     * 构造函数
     * 
     * @param array $config 邮件配置
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
        $this->mailer = new PHPMailer(true);
        $this->init();
    }

    /**
     * 初始化PHPMailer实例
     * 
     * @return void
     */
    protected function init(): void
    {
        try {
            // 服务器设置
            $this->mailer->SMTPDebug = $this->config['smtp_debug'];
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp_user'];
            $this->mailer->Password = $this->config['smtp_pass'];

            // 设置加密方式
            if ($this->config['smtp_secure'] == 'tls') {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            }
            $this->mailer->Port = $this->config['smtp_port'];

            // 设置字符集
            $this->mailer->CharSet = $this->config['charset'];

            // 设置默认发件人
            if (!empty($this->config['smtp_from_email'])) {
                $this->mailer->setFrom(
                    $this->config['smtp_from_email'],
                    $this->config['smtp_from_name'] ?: $this->config['smtp_from_email']
                );
            }
        } catch (Exception $e) {
            throw new \Exception("邮件初始化失败: {$e->getMessage()}");
        }
    }

    /**
     * 设置发件人
     * 
     * @param string $email 发件人邮箱
     * @param string $name 发件人姓名(可选)
     * @return self
     */
    public function setFrom(string $email, string $name = ''): self
    {
        try {
            $this->mailer->setFrom($email, $name ?: $email);
        } catch (Exception $e) {
            throw new \Exception("设置发件人失败: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * 添加收件人
     * 
     * @param string $email 收件人邮箱
     * @param string $name 收件人姓名(可选)
     * @return self
     */
    public function addAddress(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addAddress($email, $name);
        } catch (Exception $e) {
            throw new \Exception("添加收件人失败: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * 添加抄送
     * 
     * @param string $email 抄送邮箱
     * @param string $name 抄送姓名(可选)
     * @return self
     */
    public function addCC(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addCC($email, $name);
        } catch (Exception $e) {
            throw new \Exception("添加抄送失败: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * 添加密送
     * 
     * @param string $email 密送邮箱
     * @param string $name 密送姓名(可选)
     * @return self
     */
    public function addBCC(string $email, string $name = ''): self
    {
        try {
            $this->mailer->addBCC($email, $name);
        } catch (Exception $e) {
            throw new \Exception("添加密送失败: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * 添加附件
     * 
     * @param string $path 附件路径
     * @param string $name 附件名称(可选)
     * @return self
     */
    public function addAttachment(string $path, string $name = ''): self
    {
        try {
            $this->mailer->addAttachment($path, $name);
        } catch (Exception $e) {
            throw new \Exception("添加附件失败: {$e->getMessage()}");
        }
        return $this;
    }

    /**
     * 清除所有收件人和附件
     * 
     * @return self
     */
    public function clearAll(): self
    {
        $this->mailer->clearAddresses();
        $this->mailer->clearCCs();
        $this->mailer->clearBCCs();
        $this->mailer->clearReplyTos();
        $this->mailer->clearAttachments();
        return $this;
    }

    /**
     * 发送邮件
     * 
     * @param string $subject 邮件主题
     * @param string $body 邮件内容(HTML)
     * @param string $altBody 邮件内容(纯文本，可选)
     * @return bool 是否发送成功
     * @throws \Exception
     */
    public function send(string $subject, string $body, string $altBody = ''): bool
    {
        try {
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            if (!empty($altBody)) {
                $this->mailer->AltBody = $altBody;
            } else {
                // 如果没有提供纯文本内容，则自动从HTML中提取
                $this->mailer->AltBody = strip_tags($body);
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            throw new \Exception("邮件发送失败: {$this->mailer->ErrorInfo}");
        }
    }

    /**
     * 获取PHPMailer实例
     * 
     * @return PHPMailer
     */
    public function getMailer(): PHPMailer
    {
        return $this->mailer;
    }
}
