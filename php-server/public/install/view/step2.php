<?php
require_once './common.php';
$envItems = checkEnvironment();
$dirItems = checkDirPermission();
$funcItems = checkFunctionRequirement();

// 检查是否可以继续安装
$canContinue = true;

foreach ($envItems as $item) {
    if ($item[4] == 'error') {
        $canContinue = false;
        break;
    }
}

if ($canContinue) {
    foreach ($dirItems as $item) {
        if ($item[4] == 'error') {
            $canContinue = false;
            break;
        }
    }
}

if ($canContinue) {
    foreach ($funcItems as $item) {
        if ($item[3] == 'error') {
            $canContinue = false;
            break;
        }
    }
}
?>

<div class="step-content">
    <h2>环境检测</h2>
    
    <div class="check-section">
        <h3>系统环境检测</h3>
        <table class="check-table">
            <thead>
                <tr>
                    <th>项目</th>
                    <th>所需配置</th>
                    <th>推荐配置</th>
                    <th>当前状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($envItems as $item): ?>
                <tr>
                    <td><?php echo $item[0]; ?></td>
                    <td><?php echo $item[1]; ?></td>
                    <td><?php echo $item[2]; ?></td>
                    <td class="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="check-section">
        <h3>目录权限检测</h3>
        <table class="check-table">
            <thead>
                <tr>
                    <th>目录/文件</th>
                    <th>所需权限</th>
                    <th>当前状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dirItems as $item): ?>
                <tr>
                    <td><?php echo $item[1]; ?></td>
                    <td><?php echo $item[2]; ?></td>
                    <td class="<?php echo $item[4]; ?>"><?php echo $item[3]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="check-section">
        <h3>函数依赖检测</h3>
        <table class="check-table">
            <thead>
                <tr>
                    <th>函数/扩展</th>
                    <th>类型</th>
                    <th>当前状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcItems as $item): ?>
                <tr>
                    <td><?php echo $item[0]; ?></td>
                    <td><?php echo $item[1]; ?></td>
                    <td class="<?php echo $item[3]; ?>"><?php echo $item[2]; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="action">
        <?php if ($canContinue): ?>
        <a href="install.php?step=3" class="btn btn-primary">环境检测通过，下一步</a>
        <?php else: ?>
        <a href="javascript:void(0);" class="btn btn-disabled">环境检测未通过</a>
        <?php endif; ?>
        <a href="install.php?step=1" class="btn">返回上一步</a>
    </div>
</div> 