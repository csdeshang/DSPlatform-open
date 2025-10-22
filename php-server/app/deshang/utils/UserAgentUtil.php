<?php

namespace app\deshang\utils;

/**
 * User-Agent 解析工具类
 * 专门用于解析 User-Agent 字符串，获取设备类型、浏览器、操作系统等信息
 */
class UserAgentUtil
{
    /**
     * 解析 User-Agent 获取设备信息
     * 
     * @param string $userAgent User-Agent 字符串
     * @return array 设备信息
     */
    public static function parse(string $userAgent): array
    {
        return [
            'device_type' => self::getDeviceType($userAgent),
            'browser' => self::getBrowser($userAgent),
            'browser_version' => self::getBrowserVersion($userAgent),
            'os' => self::getOperatingSystem($userAgent),
            'os_version' => self::getOperatingSystemVersion($userAgent),
            'device_brand' => self::getDeviceBrand($userAgent),
            'is_robot' => self::isRobot($userAgent),
            'is_mobile' => self::isMobile($userAgent),
            'is_tablet' => self::isTablet($userAgent),
            'is_desktop' => self::isDesktop($userAgent)
        ];
    }

    /**
     * 获取设备类型
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 设备类型
     */
    public static function getDeviceType(string $userAgent): string
    {
        if (preg_match('/Mobile|Android|iPhone/', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/iPad/', $userAgent)) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    /**
     * 获取浏览器信息
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 浏览器名称
     */
    public static function getBrowser(string $userAgent): string
    {
        if (preg_match('/Chrome/', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Firefox/', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Safari/', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/Opera/', $userAgent)) {
            return 'Opera';
        } else {
            return 'Unknown';
        }
    }

    /**
     * 获取操作系统信息
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 操作系统名称
     */
    public static function getOperatingSystem(string $userAgent): string
    {
        if (preg_match('/iPhone/', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iPad/', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/Windows/', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac/', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'Linux';
        } else {
            return 'Unknown';
        }
    }

    /**
     * 检测访问平台类型
     * 
     * @param string $userAgent User-Agent 字符串
     * @param string $url 当前URL
     * @return string 访问平台类型
     */
    public static function getPlatformType(string $userAgent, string $url = null): string
    {
        if ($url === null) {
            $url = request()->url(true);
        }
        
        // 检测微信小程序
        if (preg_match('/miniProgram|MicroMessenger.*miniProgram/', $userAgent)) {
            return 'mini';
        }
        
        // 检测微信H5
        if (preg_match('/MicroMessenger/', $userAgent)) {
            return 'h5';
        }
        
        // 检测支付宝小程序
        if (preg_match('/AlipayClient/', $userAgent)) {
            return 'mini';
        }
        
        // 检测APP (原生应用)
        if (preg_match('/AppName|CustomApp|MyApp/', $userAgent)) {
            return 'app';
        }
        
        // 检测移动端浏览器 (H5)
        if (preg_match('/Mobile|Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/', $userAgent)) {
            return 'h5';
        }
        
        // 检测PC浏览器 (Web)
        if (preg_match('/Windows|Macintosh|Linux|X11/', $userAgent)) {
            return 'web';
        }
        
        // 默认未知平台
        return 'unknown';
    }

    /**
     * 获取浏览器版本
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 浏览器版本
     */
    public static function getBrowserVersion(string $userAgent): string
    {
        // Chrome
        if (preg_match('/Chrome\/([0-9.]+)/', $userAgent, $matches)) {
            return $matches[1];
        }
        // Firefox
        elseif (preg_match('/Firefox\/([0-9.]+)/', $userAgent, $matches)) {
            return $matches[1];
        }
        // Safari
        elseif (preg_match('/Version\/([0-9.]+).*Safari/', $userAgent, $matches)) {
            return $matches[1];
        }
        // Edge
        elseif (preg_match('/Edg\/([0-9.]+)/', $userAgent, $matches)) {
            return $matches[1];
        }
        // Opera
        elseif (preg_match('/OPR\/([0-9.]+)/', $userAgent, $matches)) {
            return $matches[1];
        }
        
        return 'Unknown';
    }


    /**
     * 获取操作系统版本
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 操作系统版本
     */
    public static function getOperatingSystemVersion(string $userAgent): string
    {
        // Windows
        if (preg_match('/Windows NT ([0-9.]+)/', $userAgent, $matches)) {
            $version = $matches[1];
            switch ($version) {
                case '10.0': return '10';
                case '6.3': return '8.1';
                case '6.2': return '8';
                case '6.1': return '7';
                case '6.0': return 'Vista';
                default: return $version;
            }
        }
        // macOS
        elseif (preg_match('/Mac OS X ([0-9_]+)/', $userAgent, $matches)) {
            return str_replace('_', '.', $matches[1]);
        }
        // Android
        elseif (preg_match('/Android ([0-9.]+)/', $userAgent, $matches)) {
            return $matches[1];
        }
        // iOS
        elseif (preg_match('/OS ([0-9_]+)/', $userAgent, $matches)) {
            return str_replace('_', '.', $matches[1]);
        }
        
        return 'Unknown';
    }

    /**
     * 获取设备品牌
     * 
     * @param string $userAgent User-Agent 字符串
     * @return string 设备品牌
     */
    public static function getDeviceBrand(string $userAgent): string
    {
        if (preg_match('/iPhone/', $userAgent)) {
            return 'Apple';
        } elseif (preg_match('/iPad/', $userAgent)) {
            return 'Apple';
        } elseif (preg_match('/Android/', $userAgent)) {
            // 尝试提取具体品牌
            if (preg_match('/(Samsung|Huawei|Xiaomi|Oppo|Vivo|OnePlus|LG|Sony|Motorola)/', $userAgent, $matches)) {
                return $matches[1];
            }
            return 'Android';
        } elseif (preg_match('/Windows/', $userAgent)) {
            return 'Microsoft';
        } elseif (preg_match('/Mac/', $userAgent)) {
            return 'Apple';
        } else {
            return 'Unknown';
        }
    }


    /**
     * 判断是否为机器人/爬虫
     * 
     * @param string $userAgent User-Agent 字符串
     * @return bool 是否为机器人
     */
    public static function isRobot(string $userAgent): bool
    {
        $robotPatterns = [
            '/bot/i', '/crawler/i', '/spider/i', '/scraper/i',
            '/Googlebot/', '/Bingbot/', '/Slurp/', '/DuckDuckBot/',
            '/Baiduspider/', '/YandexBot/', '/facebookexternalhit/',
            '/Twitterbot/', '/LinkedInBot/', '/WhatsApp/',
            '/TelegramBot/', '/Applebot/', '/SemrushBot/'
        ];
        
        foreach ($robotPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 判断是否为移动设备
     * 
     * @param string $userAgent User-Agent 字符串
     * @return bool 是否为移动设备
     */
    public static function isMobile(string $userAgent): bool
    {
        return preg_match('/Mobile|Android|iPhone/', $userAgent) > 0;
    }

    /**
     * 判断是否为平板设备
     * 
     * @param string $userAgent User-Agent 字符串
     * @return bool 是否为平板设备
     */
    public static function isTablet(string $userAgent): bool
    {
        return preg_match('/iPad/', $userAgent) > 0;
    }

    /**
     * 判断是否为桌面设备
     * 
     * @param string $userAgent User-Agent 字符串
     * @return bool 是否为桌面设备
     */
    public static function isDesktop(string $userAgent): bool
    {
        return !self::isMobile($userAgent) && !self::isTablet($userAgent);
    }

    /**
     * 获取设备唯一标识
     * 
     * @param string $userAgent User-Agent 字符串
     * @param string $ip IP地址
     * @return string 设备ID
     */
    public static function getDeviceId(string $userAgent, string $ip): string
    {
        return md5($userAgent . $ip);
    }

}
