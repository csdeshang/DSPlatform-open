<?php
// 应用公共文件

use app\deshang\service\system\DeshangSysConfigService;


define('TIMESTAMP', time());



define('BASE_SITE_ROOT', str_replace('/index.php', '', \think\facade\Request::instance()->root()));
//检测是否安装 系统
if (file_exists("install/") && !file_exists("install/install.lock")) {
    header('Location: ' . BASE_SITE_ROOT . '/install/install.php');
    exit();
}


function sysConfig($key)
{
    return (new DeshangSysConfigService())->getSysConfigByKey($key);
}


/**
 * 生成带前缀的32位唯一out_trade_no 或 out_refund_no
 * @param string $prefix 业务前缀（确保长度为4位）
 * @param string|int $userId 用户ID，默认为空字符串
 * @return string
 */
function generateOutTradeNo($prefix, $userId = '')
{
    // 确保前缀长度为4位，不足部分用0填充（尾部填充）
    $prefix = substr(str_pad($prefix, 4, '0', STR_PAD_RIGHT), 0, 4);

    // 当前时间戳（毫秒级，12位）
    $timestamp = date('ymdHis', floor(microtime(true))); // 12位时间戳（精确到秒）


    // 对用户ID进行哈希处理，取部分哈希值（6位）
    // 如果用户ID为空，使用随机字符串作为哈希的基础
    $hashBase = $userId ?: random_int(100000, 999999); // 如果用户ID为空，使用随机数
    $hash = md5($hashBase . microtime(true)); // 加入时间戳确保每次生成的哈希值不同
    $hashPart = substr($hash, 0, 6); // 6位哈希值

    // 生成10位随机数
    $randomPart = str_pad(random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);

    // 组合生成out_trade_no
    $outTradeNo = $prefix . $timestamp . $hashPart . $randomPart; // 总长度：4 + 12 + 6 + 10 = 32位

    return $outTradeNo;
}


/**
 * 计算两个经纬度之间的距离
 * @param float $latitude1 纬度1
 * @param float $longitude1 经度1
 * @param float $latitude2 纬度2
 * @param float $longitude2 经度2
 * @return float 距离（单位：米）
 */
function haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2)
{
    // 地球半径（单位：米）
    $earthRadius = 6371000;

    // 将角度转为弧度
    $latitude1 = deg2rad($latitude1);
    $latitude2 = deg2rad($latitude2);
    $longitude1 = deg2rad($longitude1);
    $longitude2 = deg2rad($longitude2);

    // 计算经纬度差值
    $latitudeDelta = $latitude2 - $latitude1;
    $longitudeDelta = $longitude2 - $longitude1;

    // 计算经纬度的平方
    $angleSinLatitude = sin($latitudeDelta / 2);
    $angleSinLongitude = sin($longitudeDelta / 2);

    // 计算Haversine公式
    $haversine = $angleSinLatitude * $angleSinLatitude + cos($latitude1) * cos($latitude2) * $angleSinLongitude * $angleSinLongitude;

    // 根据Haversine公式计算距离
    return 2 * $earthRadius * asin(sqrt($haversine));
}



/**
 * 生成带前缀的20位唯一order_no
 * @param string $prefix 业务前缀（确保长度为4位）
 * @param string|int $userId 用户ID，默认为空字符串
 * @return string
 */
function generateOrderNo($prefix, $userId = '')
{
    // 确保前缀长度为4位，不足部分用0填充（尾部填充）
    $prefix = substr(str_pad($prefix, 4, '0', STR_PAD_RIGHT), 0, 4);

    // 当前时间戳（毫秒级，12位）
    $timestamp = date('ymdHis', floor(microtime(true))); // 12位时间戳（精确到秒）


    // 用户ID部分（取用户ID的后2位，如果用户ID为空则使用随机数）
    if ($userId) {
        $userIdPart = substr($userId, -2); // 取用户ID的后2位
    } else {
        $userIdPart = str_pad(random_int(0, 99), 2, '0', STR_PAD_LEFT); // 生成2位随机数
    }

    // 生成7位随机数
    $randomPart = str_pad(random_int(0, 9999999), 7, '0', STR_PAD_LEFT);

    // 组合生成out_trade_no
    $outTradeNo = $prefix . $timestamp . $userIdPart . $randomPart; // 总长度：4 + 12 + 2 + 7 = 25位

    return $outTradeNo;
}


/**
 * 生成随机字符串
 * @param int $length 字符串长度
 * @return string 随机字符串
 */
function generateRandomString($length = 4)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $randomString;
}



/**
 * 生成加密密码
 */
function create_password($password, $salt = '')
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * 校验比对密码和加密密码是否一致
 */
function check_password($password, $hash)
{
    if (!password_verify($password, $hash)) return false;
    return true;
}


/**
 * 
 */
function ds_json_success($message = '', $data = '',  $code = 10000, $http_code = 200)
{
    $data = array('code' => $code, 'message' => $message, 'data' => $data);
    return json($data, $http_code);
}

function ds_json_error($message = '', $data = '',  $code = 10001, $http_code = 200)
{
    $data = array('code' => $code, 'message' => $message, 'data' => $data);
    return json($data, $http_code);
}






/**
 * ids 字符串转数组
 * @param string|null $ids 要转换的字符串，格式如 "1,2,3"
 * @return array 返回数字数组
 */
function ids_to_array($ids)
{
    if (empty($ids) || is_null($ids)) {
        return [];
    }
    $array = explode(',', $ids);
    // 过滤非法字符
    $array = array_filter($array, function ($item) {
        return is_numeric($item); // 确保每个元素是数字
    });
    return $array;
}




/**
 * 将线性数据转换为树形结构
 *
 * @param array $data 线性数据数组
 * @param string $idKey 节点ID的键名，默认为 'id'
 * @param string $parentIdKey 父节点ID的键名，默认为 'parent_id'
 * @param string $childrenKey 子节点数组的键名，默认为 'children'
 * @param int $rootId 根节点ID，默认为 0
 * @return array 树形结构数组
 */
function linearToTree(array $data, string $idKey = 'id', string $parentIdKey = 'parent_id', string $childrenKey = 'children', int $rootId = 0): array
{

    $tree = [];
    foreach ($data as $row) {
        if ($row[$parentIdKey] == $rootId) {
            $temp = $row;
            $child = linearToTree($data, $idKey, $parentIdKey, $childrenKey,  $row[$idKey]);
            if ($child) {
                $temp[$childrenKey] = $child;
            }
            $tree[] = $temp;
        }
    }
    return $tree;
}


/**
 * 检测 parent_id 是否有效，避免自引用
 *
 * @param int $parentId 父节点ID
 * @param int $cateId 分类ID
 * @param array $data 所有节点数据
 * @param string $idKey 节点ID的键名，默认为 'id'
 * @param string $parentIdKey 父节点ID的键名，默认为 'parent_id'
 * @return bool 检测结果，true 表示有效，false 表示无效
 */
function checkParentId(int $parentId, int $cateId, array $data, string $idKey = 'id', string $parentIdKey = 'parent_id'): bool
{
    if ($parentId == 0) {
        return true;
    }
    // 父节点 不能是自身
    if ($parentId == $cateId) {
        return false;
    }
    //判断是否在数组内
    if (!in_array($parentId, array_column($data, $idKey))) {
        return false;
    }
    // 获取所有子节点ID
    $subIds = getSubIdsRecursive($cateId, $data, $idKey, $parentIdKey);

    // 检查父节点ID是否在子节点ID数组中，避免自引用
    if (in_array($parentId, $subIds)) {
        return false; // 自引用，无效
    }

    return true; // 有效
}

/**
 * 递归获取子节点ID
 *
 * @param int $parentId 父节点ID
 * @param array $data 所有节点数据
 * @param string $idKey 节点ID的键名，默认为 'id'
 * @param string $parentIdKey 父节点ID的键名，默认为 'parent_id'
 * @param array $subIds 子节点ID数组（递归参数）
 * @return array 子节点ID数组
 */
function getSubIdsRecursive(int $parentId, array $data, string $idKey = 'id', string $parentIdKey = 'parent_id', array &$subIds = []): array
{
    foreach ($data as $item) {
        if ($item[$parentIdKey] === $parentId) {
            $subIds[] = $item[$idKey];
            getSubIdsRecursive($item[$idKey], $data, $idKey, $parentIdKey, $subIds);
        }
    }
    return $subIds;
}

/**
 * 递归获取父节点ID
 *
 * @param int $childId 子节点ID
 * @param array $data 所有节点数据
 * @param string $idKey 节点ID的键名，默认为 'id'
 * @param string $parentIdKey 父节点ID的键名，默认为 'parent_id'
 * @param array $parentIds 父节点ID数组（递归参数）
 * @return array 父节点ID数组
 */
function getParentIdsRecursive(int $childId, array $data, string $idKey = 'id', string $parentIdKey = 'parent_id', array &$parentIds = []): array
{
    foreach ($data as $item) {
        if ($item[$idKey] === $childId) {
            if ($item[$parentIdKey] !== 0) { // 假设0表示根节点
                $parentIds[] = $item[$parentIdKey];
                getParentIdsRecursive($item[$parentIdKey], $data, $idKey, $parentIdKey, $parentIds);
            }
            break;
        }
    }
    return $parentIds;
}





/**
 * 获取文字首字母（优化版）
 * @param string $str 需要获取首字母的字符串
 * @return string 首字母（大写）
 */
function get_first_charter($str)
{
    // 如果字符串为空，返回Z
    if (empty($str)) {
        return 'Z';
    }

    // 截取第一个字符
    $first_char = mb_substr($str, 0, 1, 'UTF-8');

    // 如果是英文字母，直接返回大写形式
    if (preg_match('/[a-zA-Z]/', $first_char)) {
        return strtoupper($first_char);
    }

    // 使用更可靠的方式进行编码转换
    $s1 = iconv('UTF-8', 'GBK//IGNORE', $first_char);
    if ($s1 === false) {
        return 'Z'; // 转换失败返回Z
    }

    // 获取GBK编码下的ASCII码
    if (strlen($s1) >= 2) {
        $asc = ord($s1[0]) * 256 + ord($s1[1]) - 65536;
    } else {
        // 如果转换后长度小于2，则无法计算汉字区间
        return strtoupper($first_char[0]) ?: 'Z';
    }

    // 汉字区间映射表
    $pinyinFirstChar = 'Z'; // 默认为Z

    // 根据ASCII码范围查找对应的首字母
    if ($asc >= -20319 && $asc <= -20284) $pinyinFirstChar = 'A';
    elseif ($asc >= -20283 && $asc <= -19776) $pinyinFirstChar = 'B';
    elseif ($asc >= -19775 && $asc <= -19219) $pinyinFirstChar = 'C';
    elseif ($asc >= -19218 && $asc <= -18711) $pinyinFirstChar = 'D';
    elseif ($asc >= -18710 && $asc <= -18527) $pinyinFirstChar = 'E';
    elseif ($asc >= -18526 && $asc <= -18240) $pinyinFirstChar = 'F';
    elseif ($asc >= -18239 && $asc <= -17923) $pinyinFirstChar = 'G';
    elseif ($asc >= -17922 && $asc <= -17418) $pinyinFirstChar = 'H';
    elseif ($asc >= -17417 && $asc <= -16475) $pinyinFirstChar = 'J';
    elseif ($asc >= -16474 && $asc <= -16213) $pinyinFirstChar = 'K';
    elseif ($asc >= -16212 && $asc <= -15641) $pinyinFirstChar = 'L';
    elseif ($asc >= -15640 && $asc <= -15166) $pinyinFirstChar = 'M';
    elseif ($asc >= -15165 && $asc <= -14923) $pinyinFirstChar = 'N';
    elseif ($asc >= -14922 && $asc <= -14915) $pinyinFirstChar = 'O';
    elseif ($asc >= -14914 && $asc <= -14631) $pinyinFirstChar = 'P';
    elseif ($asc >= -14630 && $asc <= -14150) $pinyinFirstChar = 'Q';
    elseif ($asc >= -14149 && $asc <= -14091) $pinyinFirstChar = 'R';
    elseif ($asc >= -14090 && $asc <= -13319) $pinyinFirstChar = 'S';
    elseif ($asc >= -13318 && $asc <= -12839) $pinyinFirstChar = 'T';
    elseif ($asc >= -12838 && $asc <= -12557) $pinyinFirstChar = 'W';
    elseif ($asc >= -12556 && $asc <= -11848) $pinyinFirstChar = 'X';
    elseif ($asc >= -11847 && $asc <= -11056) $pinyinFirstChar = 'Y';
    elseif ($asc >= -11055 && $asc <= -10247) $pinyinFirstChar = 'Z';

    return $pinyinFirstChar;
}



/**
 * 写入系统日志(方便调试)
 */
function writeSysAccessLog($result)
{
    $log = array(
        'user_id' => '',
        'username' => '写入测试',
        'ip' => '',
        'root' => '',
        'method' => '',
        'controller' => '',
        'action' => '',
        'url' => '',
        'params' => '', //请求参数 占用较大 不需要可删除
        'result' => json_encode($result, true), //返回结果 占用较大 不需要可删除
        'duration' => '',
        'http_code' => '',
        'code' => '',
    );
    // 写入系统日志
    (new app\common\dao\system\SysAccessLogsDao())->createAccessLog($log);
}


/**
 * 递归删除文件和目录
 * @param string $path 要删除的文件或目录路径
 * @return bool 删除成功返回true，失败返回false
 */
function deleteFileRecursive($path)
{
    // 检查路径是否存在
    if (!file_exists($path)) {
        return true;
    }

    // 如果是文件，直接删除
    if (is_file($path)) {
        return unlink($path);
    }

    // 如果是目录，递归删除
    if (is_dir($path)) {
        $files = array_diff(scandir($path), ['.', '..']);
        
        foreach ($files as $file) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            
            if (!deleteFileRecursive($filePath)) {
                return false;
            }
        }
        
        return rmdir($path);
    }
    
    return false;
}




/**
 * 商业金额精度处理 - 解决浮点数精度问题
 * @param float $amount 金额
 * @param int $decimals 小数位数
 * @return string 精确的商业金额
 */
function ds_commerce_money($amount, $decimals = 2) {
    return round((float)$amount, $decimals);
}







/**
 * 发送HTTP请求到第三方API
 * @param string $url 请求URL
 * @param array $data 请求数据
 * @param string $method 请求方法，支持 'GET' 或 'POST'，默认为 'POST'
 * @return string 响应内容
 * @throws \Exception 请求失败时抛出异常
 */
function send_http_request($url, array $data = [], $method = 'POST')
{
    $ch = curl_init();
    
    // 设置请求方法
    if (strtoupper($method) === 'GET') {
        // GET请求：将参数拼接到URL
        if (!empty($data)) {
            $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    } else {
        // POST请求：默认行为
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
    }
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        throw new \Exception('API请求失败：' . $error);
    }

    if ($httpCode !== 200) {
        throw new \Exception('API请求失败，HTTP状态码：' . $httpCode);
    }

    return $response;
}




/**
 * 调试日志记录方法
 * @param mixed $data 要记录的数据
 * @return void
 */
function ds_debug_log($data, $filename = 'debug.log')
{
    $logFile = app()->getRootPath() . 'runtime' . DIRECTORY_SEPARATOR . $filename;
    $logContent = date('Y-m-d H:i:s') . ' - ' . json_encode($data, JSON_UNESCAPED_UNICODE) . "\n";
    file_put_contents($logFile, $logContent, FILE_APPEND | LOCK_EX);
}
