<?php

namespace app\deshang\base;
use think\Model;

class BaseModel extends Model{
    
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';



    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';


    // 金额 格式转化
    protected function formatPrice($price) {
        // 使用 number_format 保留两位小数
        $formatted = number_format($price, 2, '.', '');

        // 将字符串转换为浮点数
        return (float)$formatted;
    }

    // 时间 格式转化
    protected function formatTime($time) {
        $time = (int)$time;
      
        return $time>0 ? date('Y-m-d H:i:s', $time) : '';
    }


}

