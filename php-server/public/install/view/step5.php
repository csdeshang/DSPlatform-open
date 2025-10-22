<?php
// 检查安装状态
$isInstalled = file_exists('./install.lock');

// 如果没有安装，跳转到第一步
if (!$isInstalled) {
    header('Location: install.php?step=1');
    exit;
}
?>

<div class="step-content">
    <h2>安装完成</h2>
    
    <div class="success-box">
        <div class="success-icon">✓</div>
        <div class="success-message">恭喜您，DSPlatform商城系统安装成功！</div>
    </div>
    
    <div class="install-info">
        <h3>安装信息</h3>
        <ul>
            <li>安装时间：<?php echo date('Y-m-d H:i:s'); ?></li>
            <li>系统版本：V3.0</li>
            <li>安装环境：<?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
            <li>PHP版本：<?php echo PHP_VERSION; ?></li>
        </ul>
    </div>
    
    <div class="notice-box warning">
        <p><strong>安全提示：</strong></p>
        <p>为了您的网站安全，安装完成后请删除或重命名 install 目录。</p>
    </div>
    
    <!-- <div class="action">
        <a href="../admin/" class="btn btn-primary">进入管理后台</a>
        <a href="../index.php" class="btn">访问前台首页</a>
    </div> -->
</div> 