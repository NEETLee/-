<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
    <link rel="stylesheet" href="/layuiadmin/style/login.css" media="all">
</head>
<body>

</body>
<script>
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use(['index'], () => {
        const $ = layui.$, admin = layui.admin, form = layui.form;
        admin.popup({
            title: '会员登录',
            content: $('#login').html(),
            shadeClose: false,
            move: false,
            success: (obj, index) => {
                obj.find('i').remove();
            },
            end: () => {
                openWriteCard();
            }
        });

        form.on('submit(loginSubmit)', data => {
            admin.req({
                type: 'post',
                url: '{{route('member.loginByPassword')}}',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {icon: 1, time: 1000});
                    layer.closeAll('page');
                }
            });
        });

        function openWriteCard() {
            layer.prompt({
                formType: 1,
                title: '输入新密码同时将卡放在读卡器上',
                shadeClose: false,
                move: false,
                closeBtn: 0,
                btn: ['已放好，写入'],
            }, (value, index, elem) => {
                admin.req({
                    url: '{{route('member.loss')}}',
                    type: 'get',
                    data: {new: value},
                    done: response => {
                        layer.msg('写入成功，原卡已失效', {
                            icon: 1, time: 1000, end: () => {
                                new Promise(rs => {
                                    top.window.layui.index.openTabsPage('{{route('member.index',['opt'=>'login'],false)}}', '业务办理')
                                    rs(1);
                                }).then(rs => {
                                    top.window.layui.$('li[lay-id="{{route('member.index',['opt'=>'loss'],false)}}"] i').click();
                                });
                            }
                        });
                    }
                });
            });
        }
    });
</script>
<script type="text/html" id="login">
    <div class="layadmin-user-login layadmin-user-display-show">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>会员登录</h2>
                <p>借阅证补办</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                           for="LAY-user-login-username"></label>
                    <input type="number" name="telephone" id="LAY-user-login-username" lay-verify="required"
                           placeholder="手机号"
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
                    <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                           placeholder="密码" autocomplete="off" class="layui-input">
                </div>
                @csrf
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="loginSubmit">登录</button>
                </div>
            </div>
        </div>
    </div>
</script>
</html>
