<?php

namespace AlibabaCloud\Dara\Util;

use AlibabaCloud\Dara\Exception\DaraException;
use AlibabaCloud\Dara\Util\BytesUtil;

class StringUtil
{

    /**
     * @param string   $string
     * @param string   $type
     *
     * @return int[]
     */
    public static function toBytes($string, $type = 'utf8')
    {
        return BytesUtil::from($string, $type);
    }

    /**
     * @param string $str
     * @param string $prefix
     *
     * @return bool
     */
    public static function hasPrefix($str, $prefix)
    {
        if(!is_string($prefix) || !is_string($str)) {
            return false;
        }

        $length = strlen($prefix);

        if ($length == 0) {
            return true;
        }
        return substr($str, 0, $length) === $prefix;
    }

    /**
     * @param string $str
     * @param string $suffix
     *
     * @return bool
     */
    public static function hasSuffix($str, $suffix)
    {
        if(!is_string($suffix) || !is_string($str)) {
            return false;
        }

        $length = strlen($suffix);
        if ($length == 0) {
            return true;
        }
        return substr($str, -$length) === $suffix;
    }

}
