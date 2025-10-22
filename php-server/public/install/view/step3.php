<?php
// 显示错误信息
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['error_message']);
?>

<div class="step-content">
    <h2>数据库配置</h2>
    
    <?php if ($errorMessage): ?>
    <div class="error-box">
        <?php echo $errorMessage; ?>
    </div>
    <?php endif; ?>
    
    <form id="db-form" action="install.php?step=4" method="post">
        <div class="form-group">
            <label>数据库服务器：</label>
            <input type="text" name="hostname" value="127.0.0.1" required>
            <span class="help">数据库服务器地址，一般为127.0.0.1或localhost</span>
        </div>
        
        <div class="form-group">
            <label>数据库端口：</label>
            <input type="text" name="hostport" value="3306" required>
            <span class="help">数据库服务器端口，默认为3306</span>
        </div>
        
        <div class="form-group">
            <label>数据库用户名：</label>
            <input type="text" name="username" value="root" required>
            <span class="help">数据库用户名</span>
        </div>
        
        <div class="form-group">
            <label>数据库密码：</label>
            <input type="password" name="password" value="">
            <span class="help">数据库密码</span>
        </div>
        
        <div class="form-group">
            <label>数据库名：</label>
            <input type="text" name="database" value="dsplatform" required>
            <span class="help">数据库名称，如果不存在会尝试自动创建</span>
        </div>
        
        <div class="form-group">
            <label>数据表前缀：</label>
            <input type="text" name="prefix" value="ds_" required>
            <span class="help">数据表前缀，同一数据库运行多个系统时请修改为不同的前缀</span>
        </div>
        
        <div class="form-group">
            <button type="button" id="test-db" class="btn">测试连接</button>
            <span id="test-result"></span>
        </div>
        
        <div class="action">
            <button type="submit" class="btn btn-primary">下一步</button>
            <a href="install.php?step=2" class="btn">返回上一步</a>
        </div>
    </form>
</div>

<script>
$(function(){
    // 测试数据库连接
    $('#test-db').click(function(){
        var data = $('#db-form').serialize();
        $(this).text('连接中...').attr('disabled', true);
        
        $.ajax({
            url: 'install.php?action=testdb',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res){
                if(res.code == 0){
                    $('#test-result').html('<span style="color: green;">'+res.msg+'</span>');
                } else {
                    $('#test-result').html('<span style="color: red;">'+res.msg+'</span>');
                }
                $('#test-db').text('测试连接').attr('disabled', false);
            },
            error: function(){
                $('#test-result').html('<span style="color: red;">请求失败，请检查网络</span>');
                $('#test-db').text('测试连接').attr('disabled', false);
            }
        });
    });
});
</script> 