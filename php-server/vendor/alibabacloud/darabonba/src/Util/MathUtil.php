<?php

namespace AlibabaCloud\Dara\Util;

class MathUtil
{
    /**
     *
     * @return double
     */
    public static function random()
    {
        return rand(0, getrandmax() - 1) / getrandmax();
    }
}
