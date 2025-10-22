<?php

namespace AlibabaCloud\Dara\Util;

use AlibabaCloud\Dara\Exception\DaraException;

class BytesUtil
{
    private static function is_bytes($value)
    {
        if (!\is_array($value)) {
            return false;
        }
        $i = 0;
        foreach ($value as $k => $ord) {
            if ($k !== $i) {
                return false;
            }
            if (!\is_int($ord)) {
                return false;
            }
            if ($ord < 0 || $ord > 255) {
                return false;
            }
            ++$i;
        }

        return true;
    }

    public static function from($input, $encoding = 'utf-8')
    {
        $buffer = '';
    
        if (self::is_bytes($input)) {
            return $input;
        } elseif (is_string($input)) {
            switch (strtolower($encoding)) {
                case 'utf-8':
                case 'utf8':
                    $buffer = $input;
                    break;
                case 'base64':
                    $decoded = base64_decode($input);
                    if ($decoded === false) {
                        throw new DaraException([], 'Invalid base64 input.');
                    }
                    $buffer = $decoded;
                    break;
                case 'hex':
                    $decoded = hex2bin($input);
                    if ($decoded === false) {
                        throw new DaraException([], 'Invalid hex input.');
                    }
                    $buffer = $decoded;
                    break;
                default:
                    throw new DaraException([], 'Unsupported encoding type.');
            }
        } else {
            throw new DaraException([], 'Input must be an bytes or a string.');
        }
        
        $result = [];
        for ($i = 0, $len = strlen($buffer); $i < $len; $i++) {
            $result[] = ord($buffer[$i]);
        }

        return $result;
    }
    /**
     *
     * @param int[] $bytes
     * @return string
     */
    public static function toString($bytes, $type = 'utf8')
    {
        if (\is_string($bytes)) {
            return $bytes;
        }
        $str = '';
        foreach ($bytes as $ch) {
            $str .= \chr($ch);
        }

        if($type == 'hex') {
            return bin2hex($str);
        }

        if($type == 'base64') {
            return base64_encode($str);
        }

        return $str;
    }
}
