<?php

namespace app\deshang\utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{
    protected $jwt_key;
    
    /**
     * 构造函数
     * 从 .env 文件中获取 JWT 密钥
     */
    public function __construct()
    {
        $this->jwt_key = env('JWT_SECRET_KEY');
        // 如果密钥未设置，抛出异常
        if (empty($this->jwt_key)) {
            throw new \Exception('JWT密钥未配置，请检查.env文件中的JWT_SECRET设置');
        }
    }

    /**
     * 生成新的令牌（自动添加版本控制）
     * 
     * @param array $payload_data Token载荷数据
     * @param int $expire 过期时间（秒）
     * @return array
     */
    public function generateToken($payload_data, $expire)
    {
        // 如果payload包含role和user_id，自动添加版本号
        if (isset($payload_data['role']) && isset($payload_data['user_id'])) {
            $payload_data['token_version'] = (new TokenCache())->getCurrentVersion(
                $payload_data['role'], 
                $payload_data['user_id']
            );

        }

        $payload = [
            'iss' => request()->host(true), // 签发者
            'aud' => request()->host(true), // 接收者
            'iat' => time(), // 签发时间
            'nbf' => time(), // 在此之前不可用
            'exp' => time() + $expire, // 过期时间
            'data' => $payload_data,
        ];
        $token = JWT::encode($payload, $this->jwt_key, 'HS256');
        return array(
            'token' => $token,
            'exp' => $payload['exp'],
        );
    }

    /**
     * 验证令牌（自动检查版本）
     * 
     * @param string $token JWT Token
     * @return array|false
     */
    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));
            $token_data = (array) $decoded->data;
            
            // 如果token包含版本信息，自动验证版本
            if (isset($token_data['role']) && isset($token_data['user_id']) && isset($token_data['token_version'])) {
                if (!(new TokenCache())->validateTokenVersion(
                    $token_data['role'], 
                    $token_data['user_id'], 
                    $token_data['token_version']
                )) {
                    return false; // Token版本不匹配，已失效
                }
            }
            
            return $token_data;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 刷新令牌 【后期删除 暂时用不到， 保证安全性  refreshToken  与   accessToken  存储数据应该分开】
     * 
     * @param string $token JWT Token
     * @return string|false
     */
    public function refreshToken($token)
    {
        $jwt_expire = 3600 * 5;
        try {
            // $decoded = JWT::decode($token, $this->jwt_key, ['HS256']);
            $decoded = JWT::decode($token, new Key($this->jwt_key, 'HS256'));

            $payload = (array) $decoded;
            $payload['iat'] = time();
            $payload['nbf'] = time();
            $payload['exp'] = time() + $jwt_expire;
            return JWT::encode($payload, $this->jwt_key, 'HS256');
        } catch (\Exception $e) {
            return false;
        }
    }
}
