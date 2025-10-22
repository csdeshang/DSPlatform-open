<?php

namespace app\deshang\third_party\lbs\providers;
use app\deshang\third_party\lbs\providers\BaseLbs;

use app\deshang\exceptions\CommonException;
use app\deshang\core\InternalResultHelper;


class Tencent extends BaseLbs
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }


    /**
     * 通过IP获取位置信息
     * @link https://lbs.qq.com/service/webService/webServiceGuide/position/webServiceIp
     * @param string $ip IP地址
     * @return array 经纬度信息
     */
    public function getCoordsByIp(string $ip): array
    {
        $url = 'https://apis.map.qq.com/ws/location/v1/ip';

        // 构造请求参数
        $param = [
            'ip' => $ip,
            'key' => $this->config['service_key'],
            'output' => 'json'
        ];
        
        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);
        
        // 腾讯返回格式：
        // status: 0 表示成功
        // result.location.lat 纬度
        // result.location.lng 经度
        if (isset($result['status']) && $result['status'] === 0 
            && isset($result['result']['location'])) {
            $location = $result['result']['location'];
            
            return InternalResultHelper::success('获取经纬度成功', [
                'longitude' => $location['lng'],
                'latitude' => $location['lat']
            ]);
        }else{
            return InternalResultHelper::error('腾讯地图IP定位失败:'.$result['message']??'');
        }
        
        
    }

    /**
     * 根据经纬度获取地理位置信息
     * @link https://lbs.qq.com/service/webService/webServiceGuide/webServiceGcoder
     * @param float $lng 经度
     * @param float $lat 纬度
     * @return array 地理位置信息
     */
    public function getAddressByLngLat(float $lng, float $lat): array
    {
        $url = 'https://apis.map.qq.com/ws/geocoder/v1/';
        
        $location = $lat . ',' . $lng;  // 腾讯地图API要求纬度在前，经度在后
        
        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'location' => $location,
            'get_poi' => 1,           // 返回周边POI点
            'output' => 'json'
        ];
        
        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);
        
        // 腾讯返回格式：
        // status: 0 表示成功
        if (isset($result['status']) && $result['status'] === 0 
            && isset($result['result'])) {
            $address = $result['result']['address_component'];
            
            return [
                'longitude' => $lng,
                'latitude' => $lat,
                'province' => $address['province'] ?? '',
                'city' => $address['city'] ?? '',
                'adcode' => $address['adcode'] ?? '',
                'citycode' => $address['citycode'] ?? '',
                'district' => $address['district'] ?? '',
                'street' => $address['street'] ?? '',
                'name' => $result['result']['address'] ?? '',
                'address' => $result['result']['address'] ?? '',
            ];
        }
        
        return [];
    }

    /**
     * 获取周边位置列表
     * @link https://lbs.qq.com/service/webService/webServiceGuide/search/webServiceSearch
     * @param array $data 包含经纬度的数组
     * @return array 周边位置列表
     */
    public function getAroundAddressList($data): array
    {
        $url = 'https://apis.map.qq.com/ws/place/v1/search';
        
        $location_lat = $data['latitude'];
        $location_lng = $data['longitude'];
        
        // 处理关键词
        $keyword = $data['keyword'] ?? '';
        if(empty($keyword)){
            $keyword = '住宅';
            // $keyword = '酒店|住宅|餐饮|娱乐|公司|商务|学校|大厦|公寓|写字楼';
        }
        
        // 腾讯地图周边搜索需要使用boundary参数
        $boundary = 'nearby(' . $location_lat . ',' . $location_lng . ',30000,1)';
        
        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'keyword' => $keyword,
            'boundary' => $boundary,       // 搜索范围：附近30000米，自动扩大范围
            'filter' => 'category=酒店宾馆,教育学校,房产小区',
            'output' => 'json',
            'page_size' => '20',           // 每页条数
            'page_index' => '1',           // 页码
            'get_subpois' => '1',          // 返回子地点
            'orderby' => '_distance'       // 按距离排序
        ];
        
        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);


        
        if (isset($result['status']) && $result['status'] === 0 
            && isset($result['data']) && !empty($result['data'])) {
            $data = [];
            
            foreach ($result['data'] as $poi) {
                $location = $poi['location'];
                $distance = isset($poi['_distance']) ? 
                    $poi['_distance'] : 
                    haversineGreatCircleDistance($location_lat, $location_lng, $location['lat'], $location['lng']);
                
                $data[] = [
                    'longitude' => $location['lng'],
                    'latitude' => $location['lat'],
                    'province' => $poi['ad_info']['province'] ?? '',
                    'city' => $poi['ad_info']['city'] ?? '',
                    'adcode' => $poi['ad_info']['adcode'] ?? '',
                    'citycode' => $poi['ad_info']['city_code'] ?? '',
                    'district' => $poi['ad_info']['district'] ?? '',
                    'street' => '',
                    'name' => $poi['title'],
                    'address' => $poi['address'] ?: $poi['title'],
                    'distance' => round($distance/1000, 2), // 转换为公里并保留两位小数
                ];
            }
            
            return $data;
        }else{
            throw new CommonException('腾讯地图周边搜索失败'.$result['message']??'');
        }
        
        
    }

    /**
     * 行政区域检索
     * @link https://lbs.qq.com/service/webService/webServiceGuide/search/webServiceSearch
     * @param array $data 包含city和keyword的数组，可选包含经纬度
     * @return array 检索到的位置列表
     */
    public function getCityAddressList($data): array
    {
        $url = 'https://apis.map.qq.com/ws/place/v1/search';
        
        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'keyword' => $data['keyword'] ?? '',
            'output' => 'json',
            'page_size' => '20',           // 每页条数
            'page_index' => '1',           // 页码
            'city_limit' => 'true'         // 仅在当前城市搜索
        ];
        
        // 如果提供了经纬度，添加center参数以优化结果排序
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $location_lat = $data['latitude'];
            $location_lng = $data['longitude'];
            $param['boundary'] = 'nearby(' . $location_lat . ',' . $location_lng . ',30000,1)';
        }
        
        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);
        
        if (isset($result['status']) && $result['status'] === 0 && isset($result['data']) && !empty($result['data'])) {
            $data = [];
            
            // 计算距离的参考点
            $location_lat = $data['latitude'] ?? 0;
            $location_lng = $data['longitude'] ?? 0;
            
            foreach ($result['data'] as $poi) {
                $location = $poi['location'];
                
                // 如果提供了经纬度，计算距离
                $distance = 0;
                if ($location_lat && $location_lng) {
                    $distance = isset($poi['_distance']) ? 
                        $poi['_distance'] : 
                        haversineGreatCircleDistance($location_lat, $location_lng, $location['lat'], $location['lng']);
                }
                
                $data[] = [
                    'longitude' => $location['lng'],
                    'latitude' => $location['lat'],
                    'province' => $poi['ad_info']['province'] ?? '',
                    'city' => $poi['ad_info']['city'] ?? '',
                    'adcode' => $poi['ad_info']['adcode'] ?? '',
                    'citycode' => $poi['ad_info']['city_code'] ?? '',
                    'district' => $poi['ad_info']['district'] ?? '',
                    'street' => '',
                    'name' => $poi['title'],
                    'address' => $poi['address'] ?: $poi['title'],
                    'distance' => $distance ? round($distance/1000, 2) : 0,
                ];
            }
            
            return $data;
        } else {
            throw new CommonException('腾讯地图区域检索失败：' . ($result['message'] ?? '未知错误'));
        }
    }




}