<?php

namespace app\deshang\third_party\lbs\providers;

use app\deshang\third_party\lbs\providers\BaseLbs;



class Tianditu extends BaseLbs
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }


    // 天地图没有IP定位功能
    public function getCoordsByIp(string $ip): array
    {


        $result = [
            'longitude' => 0,
            'latitude' => 0
        ];



        return $result;
    }


    // 天地图没有IP定位功能
    public function getAddressByLngLat(float $lng, float $lat): array
    {

        $result = [
            'longitude' => 0,
            'latitude' => 0
        ];

        return $result;
    }

    public function getAroundAddressList($data): array
    {

        $result = [
    
        ];

        return $result;
    }

    public function getCityAddressList($data): array
    {

        $result = [

        ];

        return $result;
    }
}