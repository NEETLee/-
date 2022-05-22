<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
</head>
<body class="layui-layout-body">

<div id="LAY_app">
    <div class="layui-layout layui-layout-admin">
    @include('layout.header')
    @include('layout.side')
    @include('layout.navigation')
    <!-- 主体内容 -->
        <div class="layui-body" id="LAY_app_body">
            <div class="layadmin-tabsbody-item layui-show">
                <iframe src="{{route('book.index',[],false)}}" frameborder="0" class="layadmin-iframe"></iframe>
            </div>
        </div>

        <!-- 辅助元素，一般用于移动设备下遮罩 -->
        <div class="layadmin-body-shade" layadmin-event="shade"></div>
    </div>
</div>

<script>
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use('index');
</script>
</body>
</html>


