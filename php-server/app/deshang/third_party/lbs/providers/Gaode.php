<?php

namespace app\deshang\third_party\lbs\providers;

use app\deshang\third_party\lbs\providers\BaseLbs;

use app\deshang\exceptions\CommonException;

use app\deshang\core\InternalResultHelper;

class Gaode extends BaseLbs
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }


    /**
     * 通过IP获取位置信息
     * @link https://lbs.amap.com/api/webservice/guide/api/ipconfig
     * @param string $ip IP地址
     * @return array 经纬度信息
     */
    public function getCoordsByIp(string $ip): array
    {
        $url = 'https://restapi.amap.com/v3/ip';

        // 构造请求参数
        $param = [
            'ip' => $ip,
            'key' => $this->config['service_key'],
            'output' => 'JSON'
        ];

        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);



        // 高德返回格式：
        // array:7 [
        //     "status" => "1"
        //     "info" => "OK"
        //     "infocode" => "10000"
        //     "province" => "北京市"
        //     "city" => "北京市"
        //     "adcode" => "110000"
        //     "rectangle" => "116.0119343,39.66127144;116.7829835,40.2164962"
        //   ]


        // 高德返回格式：
        // status: "1" 表示成功
        // rectangle: "116.3972015,39.90469751;116.4046974,39.91019449" 代表经纬度范围
        if (isset($result['status']) && $result['status'] === '1' && !empty($result['rectangle'])) {
            // 取rectangle的中心点作为位置
            $locations = explode(';', $result['rectangle']);
            $start = explode(',', $locations[0]);
            $end = explode(',', $locations[1]);

            return InternalResultHelper::success('获取经纬度成功', [
                'longitude' => ($start[0] + $end[0]) / 2, // 经度取中间值
                'latitude' => ($start[1] + $end[1]) / 2   // 纬度取中间值
            ]);
        }else{
            return InternalResultHelper::error('高德地图IP定位失败');
        }

    }




    /**
     * 根据经纬度获取地理位置信息
     * @link https://lbs.amap.com/api/webservice/guide/api/georegeo
     * @param float $lng 经度
     * @param float $lat 纬度
     * @return array 地理位置信息
     */
    public function getAddressByLngLat(float $lng, float $lat): array
    {
        $url = 'https://restapi.amap.com/v3/geocode/regeo';

        $location = $lng . ',' . $lat;

        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'location' => $location,
            'output' => 'JSON',
            'extensions' => 'all',    // 返回附近POI、道路等信息
            'radius' => 1000,         // 搜索半径
            'roadlevel' => 1,         // 道路等级
        ];

        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);

        // 高德返回格式：
        // status: "1" 表示成功
        if (isset($result['status']) && $result['status'] === '1' && isset($result['regeocode'])) {
            $regeocode = $result['regeocode'];
            $addressComponent = $regeocode['addressComponent'];



            return [
                'longitude' => $lng,
                'latitude' => $lat,
                'province' => $addressComponent['province'] ?? '',
                'city' => $addressComponent['city'] ?? '',
                'adcode' => $addressComponent['adcode'] ?? '',
                'citycode' => $addressComponent['citycode'] ?? '',
                'district' => $addressComponent['district'] ?? '',
                'street' => $addressComponent['street'] ?? '',
                'name' =>  $regeocode['formatted_address'] ?? '',
                'address' => $regeocode['formatted_address'] ?? '',
            ];
        }

        return [];
    }

    /**
     * 根据经纬度获取周边位置列表
     * @link https://lbs.amap.com/api/webservice/guide/api-advanced/search
     * @param array $data 包含经纬度的数组
     * @return array 周边位置列表
     */
    public function getAroundAddressList($data): array
    {
        $url = 'https://restapi.amap.com/v3/place/around';

        $location_lat = $data['latitude'];
        $location_lng = $data['longitude'];
        $location = $location_lng . ',' . $location_lat;

        $keyword = $data['keyword'];
        if (empty($keyword)) {
            // $keyword = '住宅';
            // $keyword = '酒店|住宅|餐饮|娱乐|公司|商务|学校|大厦|公寓|写字楼';
        }

        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'location' => $location,
            'keywords' => $keyword,
            'types' => '100000|120000',
            'radius' => '30000',
            'offset' => '20',
            'page' => '1',
            'extensions' => 'all',
            'output' => 'JSON'
        ];


        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);




        if (isset($result['status']) && $result['status'] === '1' && !empty($result['pois'])) {
            $data = [];

            foreach ($result['pois'] as $poi) {
                // 处理经纬度
                $coordinates = explode(',', $poi['location']);
                $lng = $coordinates[0];
                $lat = $coordinates[1];

                $data[] = [
                    'longitude' => $lng,
                    'latitude' => $lat,
                    'province' => $poi['province'] ?? '',
                    'city' => $poi['city'] ?? '',
                    'adcode' => $poi['adcode'] ?? '',
                    'citycode' => $poi['citycode'] ?? '',
                    'district' => $poi['district'] ?? '',
                    'street' => '',
                    'name' => $poi['name'],
                    'address' => $poi['address'] ?? $poi['name'],
                    'distance' => isset($poi['distance']) ? round($poi['distance'] / 1000, 2) : 0, // API直接返回距离（米），转换为公里
                ];
            }

            return $data;
        } else {
            throw new CommonException('高德地图周边搜索失败');
        }
    }

    /**
     * 行政区域检索
     * @link https://lbs.amap.com/api/webservice/guide/api/search
     * @param array $data 包含city和keyword的数组，可选包含经纬度
     * @return array 检索到的位置列表
     */
    public function getCityAddressList($data): array
    {
        $url = 'https://restapi.amap.com/v3/place/text';
        
        // 构造请求参数
        $param = [
            'key' => $this->config['service_key'],
            'keywords' => $data['keyword'] ?? '',
            'city' => $data['city'] ?? '',
            'citylimit' => 'true',           // 仅返回指定城市数据
            'extensions' => 'all',           // 返回扩展信息
            'output' => 'JSON',
            'offset' => '20',                // 每页记录数
            'page' => '1'                    // 当前页数
        ];
        
        // 如果提供了经纬度，添加center参数以优化结果排序
        if (isset($data['latitude']) && isset($data['longitude'])) {
            $location_lng = $data['longitude'];
            $location_lat = $data['latitude'];
            $param['location'] = $location_lng . ',' . $location_lat;
        }
        
        $response = $this->request_get($url, $param);
        $result = json_decode($response, true);
        
        if (isset($result['status']) && $result['status'] === '1' && !empty($result['pois'])) {
            $data = [];
            
            // 计算距离的参考点
            $location_lat = $data['latitude'] ?? 0;
            $location_lng = $data['longitude'] ?? 0;
            
            foreach ($result['pois'] as $poi) {
                // 处理经纬度
                $coordinates = explode(',', $poi['location']);
                $lng = $coordinates[0];
                $lat = $coordinates[1];
                
                // 如果提供了经纬度，计算距离
                $distance = 0;
                if ($location_lat && $location_lng) {
                    $distance = isset($poi['distance']) ? 
                        $poi['distance'] : 
                        haversineGreatCircleDistance($location_lat, $location_lng, $lat, $lng);
                }
                
                $data[] = [
                    'longitude' => $lng,
                    'latitude' => $lat,
                    'province' => $poi['pname'] ?? '',
                    'city' => $poi['cityname'] ?? '',
                    'adcode' => $poi['adcode'] ?? '',
                    'citycode' => $poi['citycode'] ?? '',
                    'district' => $poi['adname'] ?? '',
                    'street' => '',
                    'name' => $poi['name'],
                    'address' => $poi['address'] ?: $poi['name'],
                    'distance' => $distance ? round($distance/1000, 2) : 0,
                ];
            }
            
            return $data;
        } else {
            throw new CommonException('高德地图区域检索失败：' . ($result['info'] ?? '未知错误'));
        }
    }
}
