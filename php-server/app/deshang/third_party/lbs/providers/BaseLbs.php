<?php

namespace app\deshang\third_party\lbs\providers;


// LBS 驱动接口
abstract class BaseLbs
{
    
    protected function request_get($url = '', $param = array()) {
        if (empty($url) || empty($param)) {
            return false;
        }
        $getUrl = $url . "?" . http_build_query($param);
        
        
        $curl = curl_init(); // 初始化curl
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查   
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_URL, $getUrl); // 抓取指定网页
        curl_setopt($curl, CURLOPT_TIMEOUT, 1000); // 设置超时时间1秒
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // curl不直接输出到屏幕
        curl_setopt($curl, CURLOPT_HEADER, 0); // 设置header
        $data = curl_exec($curl); // 运行curl
        if (!$data) {
            print("an error occured in function request_get(): " . curl_error($curl) . "\n");
        }
        curl_close($curl);
        return $data;
    }

    // 根据IP获取位置信息
    abstract public function getCoordsByIp(string $ip): array;

    // 根据经纬度获取位置信息
    abstract public function getAddressByLngLat(float $lng, float $lat): array;

    // 根据地址获取位置信息
    // abstract public function getPositionByAddress(string $address): array;


    // 获取周边位置列表
    abstract public function getAroundAddressList($data): array;
    // 获取城市地址列表 
    abstract public function getCityAddressList($data): array;


    
}