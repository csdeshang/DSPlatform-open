<?php

namespace AlibabaCloud\Dara\Util;

use AlibabaCloud\Dara\Model;

class FormUtil
{
    /**
     *
     * @param array $query
     * @return string
     */
    public static function toFormString($query)
    {
        if (null === $query) {
            return '';
        }
        
        if ($query instanceof Model) {
            $query = $query->toArray();
        }

        return str_replace('+', '%20', http_build_query($query));
    }

    /**
     *
     * @return string
     */
    public static function getBoundary()
    {
        return (string) (mt_rand(10000000000000, 99999999999999));
    }

    /**
     *
     * @param array $map
     * @param string $boundary
     * @return string
     */
    public static function toFileForm($map, $boundary)
    {
        return new FileFormStream($map, $boundary);
    }
}
