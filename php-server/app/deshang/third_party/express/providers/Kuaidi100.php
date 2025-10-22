<?php

namespace app\deshang\third_party\express\providers;

use app\deshang\third_party\express\providers\BaseExpress;

class Kuaidi100 extends BaseExpress
{
    /**
     * 查询快递信息
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码（部分快递公司需要）
     * @return array 查询结果
     */
    // 免费版  https://www.yuque.com/kdnjishuzhichi/dfcrg1/wugo6k
    // 收费版  https://www.yuque.com/kdnjishuzhichi/dfcrg1/mza2ln
    public function query(string $express_no, string $express_code, string $phone = ''): array
    {
        // 检查配置
        if (empty($this->config['customer']) || empty($this->config['key'])) {
            throw new \Exception('快递100配置不完整，缺少客户编号或授权码');
        }

        // 根据API类型选择查询方法
        $api_type = $this->config['api_type'] ?? 'paid';
        
        if ($api_type === 'free') {
            return $this->queryFree($express_no, $express_code, $phone);
        } else {
            return $this->queryPaid($express_no, $express_code, $phone);
        }
    }

    /**
     * 免费版查询（GET请求）
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码
     * @return array 查询结果
     */
    private function queryFree(string $express_no, string $express_code, string $phone = ''): array
    {
        // 免费版API参数
        $data = [
            'type' => $express_code,
            'postid' => $express_no,
            'id' => $this->config['customer'],
            'valicode' => $this->config['key'],
            'temp' => time(),
        ];

        if (!empty($phone)) {
            $data['phone'] = $phone;
        }

        // 发送GET请求
        $api_url = 'https://www.kuaidi100.com/query';
        $response = send_http_request($api_url, $data, 'GET');

        return $this->parseFreeResponse($response);
    }

    /**
     * 收费版查询（POST请求）
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码
     * @return array 查询结果
     */
    private function queryPaid(string $express_no, string $express_code, string $phone = ''): array
    {
        // 收费版API参数
        $param = [
            'com' => $express_code,
            'num' => $express_no,
            'phone' => $phone, // 手机号后四位
        ];

        $data = [
            'customer' => $this->config['customer'],
            'param' => json_encode($param, JSON_UNESCAPED_UNICODE),
        ];

        // 生成签名
        $data['sign'] = $this->generateSign($data['param']);

        // 发送POST请求
        $api_url = 'https://poll.kuaidi100.com/poll/query.do';
        $response = send_http_request($api_url, $data, 'POST');

        return $this->parsePaidResponse($response);
    }

    /**
     * 生成签名
     * @param string $param 参数字符串
     * @return string 签名
     */
    private function generateSign(string $param): string
    {
        $signStr = $param . $this->config['key'] . $this->config['customer'];
        return strtoupper(md5($signStr));
    }

    /**
     * 解析免费版响应数据
     * @param string $response 响应内容
     * @return array 解析后的数据
     */
    private function parseFreeResponse(string $response): array
    {
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('快递100免费版API响应数据格式错误');
        }

        // 检查是否成功
        if (!isset($data['status']) || $data['status'] !== '200') {
            $message = $data['message'] ?? '未知错误';
            throw new \Exception('快递100免费版查询失败：' . $message);
        }

        // 返回标准格式的数据
        return [
            'success' => true,
            'provider' => 'kuaidi100_free',
            'express_no' => $data['nu'] ?? '',
            'express_code' => $data['com'] ?? '',
            'state' => $this->parseState($data['state'] ?? ''),
            'state_desc' => $this->getStateDesc($data['state'] ?? ''),
            'traces' => $this->parseTraces($data['data'] ?? []),
            'raw_data' => $data,
        ];
    }

    /**
     * 解析收费版响应数据
     * @param string $response 响应内容
     * @return array 解析后的数据
     */
    private function parsePaidResponse(string $response): array
    {
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('快递100收费版API响应数据格式错误');
        }

        // 检查是否成功
        if (!isset($data['result']) || !$data['result']) {
            $message = $data['message'] ?? '未知错误';
            throw new \Exception('快递100收费版查询失败：' . $message);
        }

        // 返回标准格式的数据
        return [
            'success' => true,
            'provider' => 'kuaidi100_paid',
            'express_no' => $data['nu'] ?? '',
            'express_code' => $data['com'] ?? '',
            'state' => $this->parseState($data['state'] ?? ''),
            'state_desc' => $this->getStateDesc($data['state'] ?? ''),
            'traces' => $this->parseTraces($data['data'] ?? []),
            'raw_data' => $data,
        ];
    }

    /**
     * 解析物流状态
     * @param string $state 原始状态
     * @return int 标准化状态
     */
    private function parseState(string $state): int
    {
        $stateMap = [
            '0' => 0, // 在途中
            '1' => 1, // 已揽收
            '2' => 2, // 疑难
            '3' => 3, // 已签收
            '4' => 4, // 退签
            '5' => 5, // 同城派送中
            '6' => 6, // 退回
            '7' => 7, // 转单
        ];

        return $stateMap[$state] ?? 0;
    }

    /**
     * 获取状态描述
     * @param string $state 状态
     * @return string 状态描述
     */
    private function getStateDesc(string $state): string
    {
        $stateMap = [
            '0' => '在途中',
            '1' => '已揽收',
            '2' => '疑难',
            '3' => '已签收',
            '4' => '退签',
            '5' => '同城派送中',
            '6' => '退回',
            '7' => '转单',
        ];

        return $stateMap[$state] ?? '未知状态';
    }

    /**
     * 解析物流轨迹
     * @param array $traces 原始轨迹数据
     * @return array 标准化轨迹数据
     */
    private function parseTraces(array $traces): array
    {
        $result = [];
        
        foreach ($traces as $trace) {
            $result[] = [
                'time' => $trace['time'] ?? '',
                'station' => $trace['context'] ?? '',
                'remark' => $trace['context'] ?? '',
                'location' => $trace['location'] ?? '',
            ];
        }

        return $result;
    }


} 