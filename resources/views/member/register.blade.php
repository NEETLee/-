<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
    <link rel="stylesheet" href="/layuiadmin/style/login.css" media="all">
</head>
<body>
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>会员办卡</h2>
            <p>工本费20元，押金100元，余额50，共170元</p>
        </div>
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <div class="layui-col-xs6">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                           for="LAY-user-login-nickname"></label>
                    <input type="text" name="name" id="LAY-user-login-nickname" lay-verify="nickname"
                           placeholder="姓名"
                           class="layui-input" autocomplete="new">
                </div>
                <div class="layui-col-xs6">
                    <div class="layui-input-inline">
                        <input type="radio" name="gender" value="1" title="男性">
                        <input type="radio" name="gender" value="0" title="女性">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-tree"></label>
                <input type="text" name="age" lay-verify="required" placeholder="年龄" class="layui-input"
                       autocomplete="new">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-face-smile"
                       for="LAY-user-login-nickname"></label>
                <input type="text" name="profession" placeholder="职业" lay-verify="required" class="layui-input"
                       autocomplete="new">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-cellphone"
                       for="LAY-user-login-cellphone"></label>
                <input type="text" name="telephone" id="LAY-user-login-cellphone" lay-verify="phone" placeholder="手机号"
                       class="layui-input" autocomplete="new">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required"
                               placeholder="验证码" class="layui-input" autocomplete="new">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <button type="button" class="layui-btn layui-btn-primary layui-btn-fluid"
                                    id="LAY-user-getsmscode">获取验证码
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="pass" placeholder="密码"
                       class="layui-input" autocomplete="new">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-repass"></label>
                <input type="password" name="repass" id="LAY-user-login-repass" lay-verify="required" placeholder="确认密码"
                       class="layui-input" autocomplete="new">
            </div>
            <div class="layui-form-item">
                <input type="checkbox" name="agreement" lay-skin="primary" title="同意用户协议" checked="checked">
                <div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin="primary">
                    <span>同意用户协议</span><i class="layui-icon layui-icon-ok"></i></div>
            </div>
            @csrf
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-reg-submit">注 册</button>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use(['index', 'user'], function () {
        var $ = layui.$
            , admin = layui.admin
            , form = layui.form
        form.render();

        //提交
        form.on('submit(LAY-user-reg-submit)', function (obj) {
            var field = obj.field;

            //确认密码
            if (field.password !== field.repass) {
                return layer.msg('两次密码输入不一致');
            }

            //是否同意用户协议
            if (!field.agreement) {
                return layer.msg('你必须同意用户协议才能注册');
            }

            //请求接口
            admin.req({
                url: '{{route('member.register')}}',
                type: 'post',
                data: field,
                done: function (res) {
                    layer.msg('发卡成功', {
                        offset: '15px'
                        , icon: 1
                        , time: 1000
                    }, function () {
                        new Promise(rs => {
                            top.window.layui.index.openTabsPage('{{route('member.index',['opt'=>'login'],false)}}', '业务办理')
                            rs(1);
                        }).then(rs => {
                            top.window.layui.$('li[lay-id="{{route('member.index',['opt'=>'register'],false)}}"] i').click();
                        });
                    });
                }
            });
            return false;
        });
    });
</script>
</html>
