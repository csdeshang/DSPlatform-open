<div class="step-content">
    <h2>管理员配置</h2>

    <div class="notice-box">
        请设置系统管理员账号信息。该账号为系统超级管理员，拥有最高权限。
    </div>

    <form id="admin-form" action="javascript:;" method="post">
        <div class="form-group">
            <label>管理员账号：</label>
            <input type="text" name="username" required minlength="5" maxlength="20">
            <span class="help">管理员账号长度为5-20个字符</span>
        </div>

        <div class="form-group">
            <label>管理员密码：</label>
            <input type="password" name="password" required minlength="6">
            <span class="help">管理员密码不能少于6个字符</span>
        </div>

        <div class="form-group">
            <label>确认密码：</label>
            <input type="password" name="confirm_password" required>
            <span class="help">请再次输入密码</span>
        </div>

        <div class="form-group">
            <label>管理员邮箱：</label>
            <input type="email" name="email" required>
            <span class="help">请输入有效的邮箱地址，用于找回密码</span>
        </div>

        <div class="form-group" id="install-status" style="display: none;">
            <div class="progress">
                <div class="progress-bar" style="width: 0%;">0%</div>
            </div>
            <div class="status-text">准备安装...</div>
        </div>

        <div class="action">
            <button type="submit" id="install-btn" class="btn btn-primary">开始安装</button>
            <a href="install.php?step=3" class="btn">返回上一步</a>
        </div>
    </form>
</div>

<script>
    $(function() {
        // 安装过程
        $('#admin-form').submit(function() {
            var data = $(this).serialize();
            var username = $('input[name="username"]').val();
            var password = $('input[name="password"]').val();
            var confirm_password = $('input[name="confirm_password"]').val();
            var email = $('input[name="email"]').val();

            // 简单验证
            if (username.length < 5) {
                alert('管理员账号不能少于5个字符');
                return false;
            }

            if (password.length < 6) {
                alert('管理员密码不能少于6个字符');
                return false;
            }

            if (password != confirm_password) {
                alert('两次输入的密码不一致');
                return false;
            }

            if (!email || !/^[\w.-]+@[\w.-]+\.\w+$/.test(email)) {
                alert('请输入有效的邮箱地址');
                return false;
            }

            // 显示安装进度
            $('#install-btn').attr('disabled', true).text('安装中...');
            $('#install-status').show();

            // 执行安装
            updateProgress(10, '正在连接数据库...');

            setTimeout(function() {
                updateProgress(30, '正在创建数据表...');

                setTimeout(function() {
                    updateProgress(60, '正在写入配置信息...');

                    setTimeout(function() {
                        updateProgress(80, '正在创建管理员账号...');

                        // 执行实际安装
                        $.ajax({
                            url: 'install.php?action=install',
                            type: 'POST',
                            dataType: 'json',
                            data: data,
                            success: function(res) {
                                if (res.code == 0) {
                                    updateProgress(100, '安装成功！');
                                    setTimeout(function() {
                                        window.location.href = 'install.php?step=5';
                                    }, 1000);
                                } else {
                                    $('#install-btn').attr('disabled', false).text('重新安装');
                                    updateProgress(0, '安装失败：' + res.msg);
                                }
                            },
                            error: function() {
                                $('#install-btn').attr('disabled', false).text('重新安装');
                                updateProgress(0, '安装出错，请重试');
                            }
                        });

                    }, 1000);

                }, 1000);

            }, 1000);

            return false;
        });

        // 更新安装进度
        function updateProgress(percent, text) {
            $('.progress-bar').css('width', percent + '%').text(percent + '%');
            $('.status-text').text(text);
        }
    });
</script>