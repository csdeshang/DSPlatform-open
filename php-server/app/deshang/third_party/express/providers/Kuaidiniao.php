<?php

namespace app\deshang\third_party\express\providers;

use app\deshang\third_party\express\providers\BaseExpress;

class Kuaidiniao extends BaseExpress
{
    /**
     * 查询快递信息
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码（部分快递公司需要）
     * @return array 查询结果
     */
    public function query(string $express_no, string $express_code, string $phone = ''): array
    {
        // 检查配置
        if (empty($this->config['ebusiness_id']) || empty($this->config['app_key'])) {
            throw new \Exception('快递鸟配置不完整，缺少商户ID或APP密钥');
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
     * 免费版查询
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码
     * @return array 查询结果
     */
    // 免费版  https://www.yuque.com/kdnjishuzhichi/dfcrg1/wugo6k
    private function queryFree(string $express_no, string $express_code, string $phone = ''): array
    {
        // 免费版API参数 - 按照官方文档格式
        $requestData = [
            'ShipperCode' => $express_code, // 快递公司编码
            'LogisticCode' => $express_no, // 快递单号
        ];

        if (!empty($phone)) {
            $requestData['CustomerName'] = $phone; // 收件人或寄件人手机号后四位
        }

        // 将RequestData转换为JSON字符串
        $requestDataJson = json_encode($requestData, JSON_UNESCAPED_UNICODE);

        $data = [
            'EBusinessID' => $this->config['ebusiness_id'],
            'RequestType' => '1002', // 1002表示查询物流轨迹
            'RequestData' => $requestDataJson,
            'DataType' => '2', // 2表示JSON格式
        ];

        // 生成签名 - 使用RequestData的JSON字符串
        $data['DataSign'] = $this->generateSign($requestDataJson);

        // 发送请求 - 免费版使用专用接口
        $api_url = 'https://api.kdniao.com/api/dist';
        $response = send_http_request($api_url, $data, 'POST');

        return $this->parseFreeResponse($response);
    }

    /**
     * 收费版查询
     * @param string $express_no 快递单号
     * @param string $express_code 快递公司编码
     * @param string $phone 手机号码
     * @return array 查询结果
     */
    // 收费版  https://www.yuque.com/kdnjishuzhichi/dfcrg1/yv7zgv
    private function queryPaid(string $express_no, string $express_code, string $phone = ''): array
    {
        // 收费版API参数 - 按照官方文档格式
        $requestData = [
            'CustomerName' => $phone, // 收件人或寄件人手机号后四位
            'OrderCode' => '', // 订单号，可选
            'ShipperCode' => $express_code, // 快递公司编码
            'LogisticCode' => $express_no, // 快递单号
        ];

        // 将RequestData转换为JSON字符串
        $requestDataJson = json_encode($requestData, JSON_UNESCAPED_UNICODE);

        $data = [
            'EBusinessID' => $this->config['ebusiness_id'],
            'RequestType' => '1002', // 1002表示查询物流轨迹
            'RequestData' => $requestDataJson,
            'DataType' => '2', // 2表示JSON格式
        ];

        // 生成签名 - 使用RequestData的JSON字符串
        $data['DataSign'] = $this->generateSign($requestDataJson);

        // 发送请求
        $api_url = 'https://api.kdniao.com/api/dist';
        $response = send_http_request($api_url, $data, 'POST');

        return $this->parsePaidResponse($response);
    }

    /**
     * 生成签名
     * @param string $requestData 请求数据
     * @return string 签名
     */
    private function generateSign(string $requestData): string
    {
        $signStr = $requestData . $this->config['app_key'];
        return base64_encode(md5($signStr, true));
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
            throw new \Exception('快递鸟免费版API响应数据格式错误');
        }

        // 检查是否成功
        if (!isset($data['Success']) || !$data['Success']) {
            $reason = $data['Reason'] ?? '未知错误';
            throw new \Exception('快递鸟免费版查询失败：' . $reason);
        }

        // 返回标准格式的数据
        return [
            'success' => true,
            'provider' => 'kuaidiniao_free',
            'express_no' => $data['LogisticCode'] ?? '',
            'express_code' => $data['ShipperCode'] ?? '',
            'state' => $this->parseState($data['State'] ?? ''),
            'state_desc' => $this->getStateDesc($data['State'] ?? ''),
            'traces' => $this->parseTraces($data['Traces'] ?? []),
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
            throw new \Exception('快递鸟收费版API响应数据格式错误');
        }

        // 检查是否成功
        if (!isset($data['Success']) || !$data['Success']) {
            $reason = $data['Reason'] ?? '未知错误';
            throw new \Exception('快递鸟收费版查询失败：' . $reason);
        }

        // 返回标准格式的数据
        return [
            'success' => true,
            'provider' => 'kuaidiniao_paid',
            'express_no' => $data['LogisticCode'] ?? '',
            'express_code' => $data['ShipperCode'] ?? '',
            'state' => $this->parseState($data['State'] ?? ''),
            'state_desc' => $this->getStateDesc($data['State'] ?? ''),
            'traces' => $this->parseTraces($data['Traces'] ?? []),
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
            '0' => 0, // 无轨迹
            '1' => 1, // 已揽收
            '2' => 2, // 在途中
            '3' => 3, // 已签收
            '4' => 4, // 问题件
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
            '0' => '无轨迹',
            '1' => '已揽收',
            '2' => '在途中',
            '3' => '已签收',
            '4' => '问题件',
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
                'time' => $trace['AcceptTime'] ?? '',
                'station' => $trace['AcceptStation'] ?? '',
                'remark' => $trace['Remark'] ?? '',
                'location' => $trace['Location'] ?? '',
            ];
        }

        return $result;
    }

} 