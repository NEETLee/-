<!-- 侧边菜单 -->
<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="layui-logo">
            <span>{{config('app.chinese_name')}}</span>
        </div>
        <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
            lay-filter="layadmin-system-side-menu">
            <li data-name="book" class="layui-nav-item layui-nav-item layui-this">
                <a href="javascript:void(0);" lay-href="{{route('book.index',[],false)}}" lay-tips="图书管理"
                   lay-direction="2">
                    <i class="layui-icon layui-icon-read"></i>
                    <cite>图书管理</cite>
                </a>
            </li>
            <li data-name="member" class="layui-nav-item">
                <a href="javascript:void(0);" lay-tips="会员管理" lay-direction="2">
                    <i class="layui-icon layui-icon-user"></i>
                    <cite>会员管理</cite>
                </a>
                <dl class="layui-nav-child">
                    <dd data-name="console">
                        <a lay-href="{{route('member.index',['opt'=>'login'],false)}}">业务办理</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{route('member.index',['opt'=>'register'],false)}}">注册发卡</a>
                    </dd>
                    <dd data-name="console">
                        <a lay-href="{{route('member.index',['opt'=>'loss'],false)}}">挂失补卡</a>
                    </dd>
                </dl>
            </li>
            <li data-name="income" class="layui-nav-item">
                <a href="javascript:void(0);" lay-href="{{route('income.index',[],false)}}" lay-tips="收款管理"
                   lay-direction="2">
                    <i class="layui-icon layui-icon-rmb"></i>
                    <cite>收款管理</cite>
                </a>
            </li>
        </ul>
    </div>
</div>
