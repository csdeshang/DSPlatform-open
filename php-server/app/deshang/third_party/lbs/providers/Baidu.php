<?php

namespace app\deshang\third_party\lbs\providers;

use app\deshang\third_party\lbs\providers\BaseLbs;

use app\deshang\exceptions\CommonException;

use app\deshang\core\InternalResultHelper;

class Baidu extends BaseLbs
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }


    // https://lbs.baidu.com/faq/api?title=webapi/ip-api-base
    public function getCoordsByIp(string $ip): array
    {

        $service_ak = $this->config['service_ak'];
        $url = 'https://api.map.baidu.com/location/ip';

        // 构造请求参数
        $param['ip'] = $ip;
        $param['ak'] = $service_ak;
        $param['coor'] = 'bd09ll';

        $response = $this->request_get($url, $param);
        $result = json_decode($response);


        if ($result->status == '0') {
            return InternalResultHelper::success('获取经纬度成功', [
                'longitude' => $result->content->point->x,
                'latitude' => $result->content->point->y
            ]);
        } else {
            return InternalResultHelper::error('百度地图IP定位失败');
        }

    }


    // https://lbs.baidu.com/faq/api?title=webapi/guide/webservice-geocoding-abroad-base
    public function getAddressByLngLat(float $lng, float $lat): array
    {

        $service_ak = $this->config['service_ak'];

        $url = 'https://api.map.baidu.com/reverse_geocoding/v3';


        $location = $lat . ',' . $lng;


        //坐标的类型,bd09l,wgs84  等
        //        $param['coordtype'] = 'gcj02';
        //        $param['ret_coordtype'] = 'gcj02';

        $param['ak'] = $service_ak;
        $param['output'] = 'json';

        $param['extensions_poi'] = '1';
        $param['location'] = $location;
        $baidu_res = $this->request_get($url, $param);

        $result = json_decode($baidu_res);
        if ($result->status == '0') {
            $result = [
                'longitude' => $result->result->location->lng,
                'latitude' => $result->result->location->lat,
                'province' => $result->result->addressComponent->province,
                'city' => $result->result->addressComponent->city,
                'adcode' => $result->result->addressComponent->adcode ?? '',
                'citycode' => $result->result->citycode ?? '',
                'district' => $result->result->addressComponent->district,
                'street' => $result->result->addressComponent->street,
                'name' => $result->result->formatted_address_poi,
                'address' => $result->result->formatted_address_poi,
            ];
        } else {
            $result = [];
        }

        return $result;
    }



    //  获取周边位置列表
    //  [地点检索]  https://lbs.baidu.com/faq/api?title=webapi/guide/webservice-placeapi/circle   
    public function getAroundAddressList($data): array
    {
        $service_ak = $this->config['service_ak'];
        $url = 'https://api.map.baidu.com/place/v2/search';



        $location_lat = $data['latitude'];
        $location_lng = $data['longitude'];
        $location = $location_lat . ',' . $location_lng;

        $keyword = $data['keyword'];
        if (empty($keyword)) {
            $keyword = '宾馆$酒店$住宅$餐饮$生活娱乐$公司$商务$学校$大厦$公寓$写字楼';
        }


        $param['query'] = $keyword;
        $param['location'] = $location;
        $param['radius'] = '2000';
        $param['output'] = 'json';
        $param['page_size'] = '20';
        $param['scope'] = '2';
        $param['tag'] = '';
        $param['ak'] = $service_ak;
        //        $param['coord_type'] = '2';
        //        $param['ret_coordtype'] = 'gcj02';

        $baidu_res = $this->request_get($url, $param);



        $result = json_decode($baidu_res);

        if ($result->status == '0') {
            $data = array();



            foreach ($result->results as $address) {
                $lng = isset($address->detail_info->navi_location->lng) ? $address->detail_info->navi_location->lng : $address->location->lng;
                $lat = isset($address->detail_info->navi_location->lat) ? $address->detail_info->navi_location->lat : $address->location->lat;
                $distance = isset($address->detail_info->distance) ? $address->detail_info->distance : haversineGreatCircleDistance($location_lat, $location_lng, $lat, $lng);
                $data[] = array(
                    'longitude' => $lng,
                    'latitude' => $lat,
                    'province' => $address->province,
                    'city' => $address->city,
                    'adcode' => $address->adcode ?? '',
                    'citycode' => $address->citycode ?? '',
                    'district' => $address->area,
                    'street' => '',
                    'name' => $address->name,
                    'address' => $address->address ?? $address->name,
                    'distance' => round($distance / 1000, 2),
                );
            }

            return $data;
        } else {
            throw new CommonException('百度地图周边搜索失败');
        }
    }


    // 行政区域检索  https://lbs.baidu.com/faq/api?title=webapi/guide/webservice-placeapi/district
    public function getCityAddressList($data): array
    {
        $service_ak = $this->config['service_ak'];
        // 请求地址
        $url = 'https://api.map.baidu.com/place/v2/search';

        $lat = $data['latitude'];
        $lng = $data['longitude'];

        $location = $lat . ',' . $lng;
        $location_lat = $lat;
        $location_lng = $lng;


        // 构造请求参数
        $param['query'] = $data['keyword'];
        $param['region'] = $data['city'];
        $param['center'] = $location;
        $param['city_limit'] = 'true';
        $param['output'] = 'json';
        $param['page_size'] = '20';
        $param['scope'] = '2';
        $param['tag'] = '';
        $param['ak'] = $service_ak;
        //        $param['coord_type'] = '2';
        //        $param['ret_coordtype'] = 'gcj02';


        $baidu_res = $this->request_get($url, $param);



        $result = json_decode($baidu_res);
        if ($result->status == '0') {
            $data = array();
            foreach ($result->results as $address) {
                $lng = isset($address->detail_info->navi_location->lng) ? $address->detail_info->navi_location->lng : $address->location->lng;
                $lat = isset($address->detail_info->navi_location->lat) ? $address->detail_info->navi_location->lat : $address->location->lat;
                $distance = isset($address->detail_info->distance) ? $address->detail_info->distance : haversineGreatCircleDistance($location_lat, $location_lng, $lat, $lng);
                $data[] = array(
                    'longitude' => $lng,
                    'latitude' => $lat,
                    'province' => $address->province,
                    'city' => $address->city,
                    'adcode' => $address->adcode ?? '',
                    'citycode' => $address->citycode ?? '',
                    'district' => $address->area,
                    'name' => $address->name,
                    'address' => isset($address->address) ? $address->address : $address->name,
                    'distance' => round($distance / 1000, 2),
                );
            }

            return $data;
        } else {
            throw new CommonException('获取地址列表失败,' . $result->status);
        }
    }
}
