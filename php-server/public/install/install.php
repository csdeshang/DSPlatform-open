<?php
// 安装程序入口文件

// 检查是否已安装
if (file_exists('./install.lock')) {
    // 如果是访问第5步（安装完成页面），允许访问
    $step = isset($_GET['step']) ? intval($_GET['step']) : 1;
    if ($step !== 5) {
        echo "已安装，如需重新安装请先删除 install.lock 文件";
        exit;
    }
}

// 引入公共函数
require_once './common.php';

// 处理AJAX请求
$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($action) {
    header('Content-Type: application/json');
    
    switch ($action) {
        case 'testdb': // 测试数据库连接
            $config = [
                'hostname' => isset($_POST['hostname']) ? $_POST['hostname'] : '',
                'hostport' => isset($_POST['hostport']) ? $_POST['hostport'] : '3306',
                'username' => isset($_POST['username']) ? $_POST['username'] : '',
                'password' => isset($_POST['password']) ? $_POST['password'] : '',
            ];
            
            list($success, $message) = testDbConnection($config);
            echo json_encode(['code' => $success ? 0 : 1, 'msg' => $message]);
            exit;
            
        case 'install': // 执行安装
            session_start();
            
            // 数据库配置
            $dbConfig = isset($_SESSION['db_config']) ? $_SESSION['db_config'] : [];
            if (empty($dbConfig)) {
                echo json_encode(['code' => 1, 'msg' => '数据库配置信息不存在，请返回上一步重新配置']);
                exit;
            }
            
            // 管理员信息
            $adminConfig = [
                'username' => isset($_POST['username']) ? $_POST['username'] : '',
                'password' => isset($_POST['password']) ? $_POST['password'] : '',
                'email' => isset($_POST['email']) ? $_POST['email'] : ''
            ];
            
            if (empty($adminConfig['username']) || empty($adminConfig['password'])) {
                echo json_encode(['code' => 1, 'msg' => '管理员账号和密码不能为空']);
                exit;
            }
            
            // 数据库安装
            list($dbSuccess, $dbMessage) = installDatabase($dbConfig);
            if (!$dbSuccess) {
                echo json_encode(['code' => 1, 'msg' => $dbMessage]);
                exit;
            }
            
            // 配置写入 
            // $configSuccess = writeDatabaseConfig($dbConfig);
            // if (!$configSuccess) {
            //     echo json_encode(['code' => 1, 'msg' => '数据库配置文件写入失败']);
            //     exit;
            // }

            // 写入配置文件
            $configSuccess = writeEnvConfig($dbConfig);
            if (!$configSuccess) {
                echo json_encode(['code' => 1, 'msg' => 'Env 配置文件写入失败']);
                exit;
            }


            
            // 创建管理员
            list($adminSuccess, $adminMessage) = createAdmin($adminConfig, $dbConfig);
            if (!$adminSuccess) {
                echo json_encode(['code' => 1, 'msg' => $adminMessage]);
                exit;
            }
            
            // 创建安装锁定文件
            createLockFile();
            
            // 清理安装缓存
            session_destroy();
            
            echo json_encode(['code' => 0, 'msg' => '安装成功']);
            exit;
    }
}

// 处理步骤间数据传递
session_start();

if (isset($_GET['step']) && $_GET['step'] == 4) {
    // 从步骤3到步骤4，保存数据库配置
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dbConfig = [
            'hostname' => isset($_POST['hostname']) ? $_POST['hostname'] : '',
            'hostport' => isset($_POST['hostport']) ? $_POST['hostport'] : '3306',
            'username' => isset($_POST['username']) ? $_POST['username'] : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'database' => isset($_POST['database']) ? $_POST['database'] : '',
            'prefix' => isset($_POST['prefix']) ? $_POST['prefix'] : 'ds_'
        ];
        
        // 测试连接
        list($success, $message) = testDbConnection($dbConfig);
        if (!$success) {
            $_SESSION['error_message'] = "数据库连接失败: $message";
            header('Location: install.php?step=3');
            exit;
        }
        
        $_SESSION['db_config'] = $dbConfig;
    } elseif (!isset($_SESSION['db_config'])) {
        // 如果直接访问步骤4但没有数据库配置，返回步骤3
        header('Location: install.php?step=3');
        exit;
    }
}

// 加载框架引导文件（仅在访问页面时加载，AJAX请求不需要）
if (empty($action)) {
    // 这里根据实际情况可能需要调整
    if (file_exists('../vendor/autoload.php')) {
        require '../vendor/autoload.php';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DSPlatform商城系统安装向导</title>
    <link rel="stylesheet" href="./images/install.css">
    <script src="./images/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="header">
        <div class="layout">
            <div class="title">DSPlatform商城系统 安装向导</div>
            <div class="version">版本：V3.0</div>
        </div>
    </div>
    
    <div class="main">
        <div class="layout">
            <div class="steps-wrap">
                <div class="steps">
                    <div class="step <?php echo isset($_GET['step']) && $_GET['step'] >= 1 ? 'active' : ''; ?>">
                        <div class="step-num">1</div>
                        <div class="step-text">阅读许可协议</div>
                    </div>
                    <div class="step <?php echo isset($_GET['step']) && $_GET['step'] >= 2 ? 'active' : ''; ?>">
                        <div class="step-num">2</div>
                        <div class="step-text">环境检测</div>
                    </div>
                    <div class="step <?php echo isset($_GET['step']) && $_GET['step'] >= 3 ? 'active' : ''; ?>">
                        <div class="step-num">3</div>
                        <div class="step-text">数据库配置</div>
                    </div>
                    <div class="step <?php echo isset($_GET['step']) && $_GET['step'] >= 4 ? 'active' : ''; ?>">
                        <div class="step-num">4</div>
                        <div class="step-text">管理员配置</div>
                    </div>
                    <div class="step <?php echo isset($_GET['step']) && $_GET['step'] >= 5 ? 'active' : ''; ?>">
                        <div class="step-num">5</div>
                        <div class="step-text">安装完成</div>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <?php
                $step = isset($_GET['step']) ? intval($_GET['step']) : 1;
                
                switch ($step) {
                    case 1: // 阅读许可协议
                        include './view/step1.php';
                        break;
                    case 2: // 环境检测
                        include './view/step2.php';
                        break;
                    case 3: // 数据库配置
                        include './view/step3.php';
                        break;
                    case 4: // 管理员配置
                        include './view/step4.php';
                        break;
                    case 5: // 安装完成
                        include './view/step5.php';
                        break;
                    default:
                        include './view/step1.php';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> DSPlatform商城系统
        </div>
    </div>
</body>
</html> 