<?php

namespace app\deshang\core;

class InternalResultHelper
{
    /**
     * 成功返回
     * @param mixed $data
     * @param string $msg
     * @return array
     */
    public static function success(string $msg = '操作成功', $data = null): array
    {
        return [
            'success' => true,
            'message'    => $msg,
            'data'   => $data,
        ];
    }

    /**
     * 错误返回
     * @param string $msg
     * @param mixed $data
     * @return array
     */
    public static function error(string $msg = '操作失败', $data = null): array
    {
        return [
            'success' => false,
            'message'    => $msg,
            'data'   => $data,
        ];
    }
}
