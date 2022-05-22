<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
    <link rel="stylesheet" href="./layuiadmin/style/login.css" media="all">
</head>
<body>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <p>{{config('app.name')}}</p>
            <h2>{{config('app.chinese_name')}}</h2>
        </div>
        <form class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input type="text" name="account" id="LAY-user-login-username" lay-verify="required" placeholder="用户名"
                       class="layui-input" autocomplete="off">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                       placeholder="密码" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required"
                               placeholder="图形验证码" class="layui-input" autocomplete="off">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="https://www.oschina.net/action/user/captcha" class="layadmin-user-login-codeimg"
                                 id="LAY-user-get-vercode" alt="点击切换验证码">
                        </div>
                    </div>
                </div>
            </div>
            @csrf
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登录</button>
            </div>
        </form>
    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>© {{date('Y')}} <a href="#" target="_blank">neetlee.xyz</a></p>
    </div>
</div>

<script>
    if (self !== top) {
        window.top.location.href = '/';
    }
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use(['index'], function () {
        const admin = layui.admin, form = layui.form;
        form.on('submit(LAY-user-login-submit)', function (obj) {
            admin.req({
                url: '{{route('login')}}',
                type: 'post',
                data: obj.field,
                done: function (res) {
                    layer.msg(res.msg, {
                        offset: '150px'
                        , icon: 1
                        , time: 1000
                    }, function () {
                        location.href = '/';
                    });
                }
            });
            return false;
        });
    });
</script>
</body>
</html>
