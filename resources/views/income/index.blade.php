<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
</head>
<body>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="income_table"></table>
        </div>
    </div>
</div>
</body>
<script>
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use('index', () => {
        const $ = layui.$;
        const table = layui.table;
        const form = layui.form;
        const laydate = layui.laydate;
        let tableInstance = table.render({
            elem: '#income_table',
            url: '{{route('income.list')}}',
            where: {_token: '{{csrf_token()}}'},
            method: 'post',
            height: 'full-50',
            toolbar: '#searchDom',
            page: layui.setter.page,
            totalRow: true,
            cols: [[
                {field: 'bill_no', title: '订单号', width: 300},
                {
                    field: 'name', title: '书名', templet: dd => {
                        return dd.book.name;
                    }
                },
                {
                    field: 'author', title: '作者', templet: dd => {
                        return dd.book.author;
                    }, width: 200
                },
                {
                    field: 'ISBN', title: 'ISBN编号', width: 200, templet: dd => {
                        return dd.book.ISBN;
                    }
                },
                {
                    field: 'member', title: '借书人', width: 100, templet: dd => {
                        return dd.member.name;
                    }
                },
                {
                    field: 'member', title: '手机号', width: 150, templet: dd => {
                        return dd.member.telephone;
                    }
                },
                {
                    field: 'started_at', title: '借书日期', width: 180
                },
                {
                    field: 'ended_at', title: '还书日期', width: 150
                },
                {
                    field: 'money', title: '总费用', width: 100, templet: dd => {
                        return `${dd?.money}元`;
                    }, totalRow: true,
                },
                {
                    field: 'penalty', title: '罚款', width: 100, templet: dd => {
                        if (dd.penalty !== null) {
                            return `${dd.penalty.money}元`;
                        } else {
                            return '-';
                        }
                    }
                },
            ]],
            response: {statusCode: 200},
            parseData: (res) => {
                return {
                    code: res.code,
                    msg: res.message,
                    count: res.data.meta.count,
                    data: res.data.data,
                    query: res.data.meta.query
                };
            },
            done: response => {
                form.val('searchForm', response.query);
                laydate.render({elem: '#started_at', range: '~'});
                laydate.render({elem: '#ended_at', range: '~'});
            }
        });

        form.on('submit(searchSubmit)', data => {
            tableInstance.config.where.query = data.field;
            tableInstance.reload();
            return false;
        });
    });
</script>
<script type="text/html" id="searchDom">
    <form class="layui-form layui-form-pane" id="searchForm" lay-filter="searchForm">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="name" autocomplete="new" placeholder="书名 - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="ISBN" autocomplete="new" placeholder="ISBN - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="telephone" autocomplete="new" placeholder="借书人手机号"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="started_at" autocomplete="new" id="started_at"
                   placeholder="借书日期（范围）"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="ended_at" autocomplete="new" id="ended_at"
                   placeholder="还书日期（范围）"/>
        </div>
        <input type="submit" class="layui-btn" lay-filter="searchSubmit" lay-submit value="搜索"/>
        <input type="reset" class="layui-btn layui-btn-normal" value="重置"/>
    </form>
</script>
</html>
