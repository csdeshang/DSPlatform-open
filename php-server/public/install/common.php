<?php

/**
 * 安装模块公共函数
 */

/**
 * 系统环境检测
 * @return array 检测结果
 */
function checkEnvironment()
{
    $items = [
        'os' => ['操作系统', '不限制', '类Unix', PHP_OS, 'success'],
        'php' => ['PHP版本', '8.0.0', '8.0.0+', PHP_VERSION, 'success'],
        'upload' => ['附件上传', '不限制', '2M+', '未知', 'success'],
        'gd' => ['GD库', '2.0', '2.0+', '未知', 'success'],
        'disk' => ['磁盘空间', '100M', '不限制', '未知', 'success'],
    ];

    // PHP版本检测
    if (version_compare(PHP_VERSION, '8.0.0', '<')) {
        $items['php'][4] = 'error';
    }

    // 上传限制检测
    $uploadSize = ini_get('upload_max_filesize');
    $items['upload'][3] = $uploadSize;
    if ((int)$uploadSize < 2) {
        $items['upload'][4] = 'error';
    }

    // GD库检测
    $gdInfo = function_exists('gd_info') ? gd_info() : [];
    if (!empty($gdInfo)) {
        $items['gd'][3] = $gdInfo['GD Version'];
    } else {
        $items['gd'][3] = '未安装';
        $items['gd'][4] = 'error';
    }

    // 磁盘空间检测
    if (function_exists('disk_free_space')) {
        $diskSpace = floor(disk_free_space('./') / (1024 * 1024));
        $items['disk'][3] = $diskSpace . 'M';
        if ($diskSpace < 100) {
            $items['disk'][4] = 'error';
        }
    }

    return $items;
}

/**
 * 目录权限检测
 * @return array 检测结果
 */
function checkDirPermission()
{
    $items = [
        ['dir', '../../runtime', '可写', '可写', 'success'],
        ['dir', '../attachment', '可写', '可写', 'success'],
        ['dir', '../../config', '可写', '可写', 'success'],
        ['file', '../.env', '可写', '可写', 'success'],
    ];

    foreach ($items as &$item) {
        $path = $item[1];
        if ($item[0] == 'dir') {
            // if (!is_dir($path)) {
            //     @mkdir($path, 0755, true);
            // }
            if (!is_writable($path)) {
                $item[3] = '不可写';
                $item[4] = 'error';
            }
        } else {
            if (file_exists($path)) {
                if (!is_writable($path)) {
                    $item[3] = '不可写';
                    $item[4] = 'error';
                }
            } else {
                if (!is_writable(dirname($path))) {
                    $item[3] = '不可写';
                    $item[4] = 'error';
                }
            }
        }
    }

    return $items;
}

/**
 * 函数检测
 * @return array 检测结果
 */
function checkFunctionRequirement()
{
    $items = [
        ['pdo', '支持', '支持', 'success'],
        ['pdo_mysql', '支持', '支持', 'success'],
        ['curl', '支持', '支持', 'success'],
        // ['fileinfo', '支持', '支持', 'success'],
        ['openssl', '支持', '支持', 'success'],
        ['mbstring', '支持', '支持', 'success'],
    ];

    foreach ($items as &$item) {
        if (!extension_loaded($item[0])) {
            $item[2] = '不支持';
            $item[3] = 'error';
        }
    }

    return $items;
}

/**
 * 测试数据库连接
 * @param array $config 数据库配置
 * @return array [是否成功, 错误信息]
 */
function testDbConnection($config)
{
    try {
        $dsn = "mysql:host={$config['hostname']};port={$config['hostport']}";
        $dbh = new PDO($dsn, $config['username'], $config['password']);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return [true, '连接成功'];
    } catch (PDOException $e) {
        return [false, $e->getMessage()];
    }
}





/**
 * 写入配置文件
 * @param array $config 配置信息
 * @return bool 是否成功
 */
function writeEnvConfig($config)
{
    // 生成随机的 JWT 密钥
    $jwtSecretKey = bin2hex(random_bytes(32)); // 生成一个64字符长度的随机密钥

    //安装时间
    $installTime = date('Y-m-d H:i:s', time());

    $configFile = '../../.env';
    $configContent = <<<EOT
APP_DEBUG = true
CACHE_ENABLED = true
INSTALL_TIME = {$installTime}

DB_TYPE = mysql
DB_HOST = {$config['hostname']}
DB_NAME = {$config['database']}
DB_USER = {$config['username']}
DB_PASS = {$config['password']}
DB_PORT = {$config['hostport']}
DB_CHARSET = utf8mb4
DB_PREFIX = {$config['prefix']}

DEFAULT_LANG = zh-cn

# JWT配置
JWT_SECRET_KEY = {$jwtSecretKey}


EOT;

    try {
        file_put_contents($configFile, $configContent);
        return true;
    } catch (Exception $e) {
        return false;
    }
}




/**
 * 写入配置文件
 * @param array $config 配置信息
 * @return bool 是否成功
 */
function writeDatabaseConfig($config)
{
    $configFile = '../../config/database.php';
    $configContent = <<<EOT
<?php
return [
    // 默认使用的数据库连接配置
    'default'         => 'mysql',

    // 自定义时间查询规则
    'time_query_rule' => [],

    // 自动写入时间戳字段
    'auto_timestamp'  => true,

    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => 'mysql',
            // 服务器地址
            'hostname'          => '{$config['hostname']}',
            // 数据库名
            'database'          => '{$config['database']}',
            // 用户名
            'username'          => '{$config['username']}',
            // 密码
            'password'          => '{$config['password']}',
            // 端口
            'hostport'          => '{$config['hostport']}',
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8
            'charset'           => 'utf8mb4',
            // 数据库表前缀
            'prefix'            => '{$config['prefix']}',
            
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 监听SQL
            'trigger_sql'       => true,
            // 开启字段缓存
            'fields_cache'      => false,
        ],
    ],
];
EOT;

    try {
        file_put_contents($configFile, $configContent);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * 安装数据库
 * @param array $config 数据库配置
 * @return array [是否成功, 消息]
 */
function installDatabase($config)
{
    try {
        $dsn = "mysql:host={$config['hostname']};port={$config['hostport']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 判断数据库是否存在
        $sql = "SHOW DATABASES LIKE '{$config['database']}'";
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch();

        if (empty($result)) {
            // 创建数据库
            $sql = "CREATE DATABASE `{$config['database']}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $pdo->exec($sql);
        }

        // 切换到指定数据库
        $sql = "USE `{$config['database']}`";
        $pdo->exec($sql);

        // 设置连接参数
        $pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");

        // 执行数据库脚本
        $sqlFiles = [
            './db/deshang.sql',
            './db/video.sql',
            './db/initarea.sql',
            './db/points-goods.sql',

            // 可以添加其他数据库脚本
        ];

        foreach ($sqlFiles as $sqlFile) {
            if (!file_exists($sqlFile)) {
                continue;
            }

            $sqlContent = file_get_contents($sqlFile);
            $sqlContent = str_replace('#__', $config['prefix'], $sqlContent);

            // 分割SQL语句
            $sqlStatements = splitSqlFile($sqlContent);

            foreach ($sqlStatements as $statement) {
                $statement = trim($statement);
                if ($statement) {
                    $pdo->exec($statement);
                }
            }
        }

        return [true, '数据库安装成功'];
    } catch (PDOException $e) {
        return [false, '数据库安装失败：' . $e->getMessage()];
    }
}

/**
 * 分割SQL文件为单独的语句
 * @param string $sql SQL内容
 * @return array SQL语句数组
 */
function splitSqlFile($sql)
{
    $sql = str_replace("\r", "\n", $sql);
    $sqlLines = explode("\n", $sql);

    $statements = [];
    $statement = '';

    foreach ($sqlLines as $line) {
        $line = trim($line);

        // 跳过注释和空行
        if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0) {
            continue;
        }

        $statement .= ' ' . $line;

        // 如果是语句结束符
        if (substr($line, -1) == ';') {
            $statements[] = $statement;
            $statement = '';
        }
    }

    // 处理最后一个语句
    if (!empty($statement)) {
        $statements[] = $statement;
    }

    return $statements;
}

/**
 * 创建管理员账号
 * @param array $config 管理员信息
 * @param array $dbConfig 数据库配置
 * @return array [是否成功, 消息]
 */
function createAdmin($config, $dbConfig)
{
    try {
        $dsn = "mysql:host={$dbConfig['hostname']};port={$dbConfig['hostport']};dbname={$dbConfig['database']}";
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 生成密码盐
        $salt = substr(md5(uniqid(true)), 0, 6);
        // 加密密码
        $password = password_hash($config['password'], PASSWORD_DEFAULT);

        // 创建管理员
        $adminTable = $dbConfig['prefix'] . 'admin';
        $sql = "INSERT INTO `{$adminTable}` (`username`, `password`, `login_time`, `login_num`, `login_ip`, `is_super`, `role_id`, `create_at`, `create_by`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $config['username'],
            $password,
            time(),
            0,
            '',
            1,
            0,
            time(),
            $config['username']
        ]);

        if ($result) {
            return [true, '管理员创建成功'];
        } else {
            return [false, '管理员创建失败'];
        }
    } catch (PDOException $e) {
        return [false, '管理员创建失败：' . $e->getMessage()];
    }
}

/**
 * 生成安装锁定文件
 * @return bool 是否成功
 */
function createLockFile()
{
    $content = '安装时间：' . date('Y-m-d H:i:s') . "\n";
    $content .= '请勿删除此文件，否则可能被重新安装！';

    try {
        file_put_contents('./install.lock', $content);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
