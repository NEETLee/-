<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
</head>
<body>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="book_table" lay-filter="book_table"></table>
        </div>
    </div>
</div>
</body>
<script>
    layui.config({base: '/layuiadmin/'}).extend({index: 'lib/index'}).use('index', () => {
        const $ = layui.$;
        const table = layui.table;
        const form = layui.form;
        const admin = layui.admin;
        let tableInstance = table.render({
            elem: '#book_table',
            url: '{{route('book.list')}}',
            where: {_token: '{{csrf_token()}}'},
            method: 'post',
            height: 'full-50',
            toolbar: '#searchDom',
            page: layui.setter.page,
            defaultToolbar: ['filter', {
                title: '新书入库'
                , layEvent: 'ADD_BOOK'
                , icon: 'layui-icon-add-circle'
            }],
            cols: [[
                {field: 'name', title: '书名'},
                {field: 'author', title: '作者', width: 100},
                {field: 'publisher', title: '出版社', width: 250},
                {field: 'ISBN', title: 'ISBN编号', width: 200},
                {field: 'price', title: '价格', width: 100},
                {field: 'num', title: '库存', width: 100},
                {field: 'lend', title: '借出数量', width: 100},
                {field: 'location', title: '存放区域', width: 100},
                {field: 'category', title: '分类', width: 100},
                {field: 'edition', title: '版次', width: 100},
                {fixed: 'right', title: '操作', width: 120, toolbar: '#optDom'}
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
            }
        });

        table.on('toolbar(book_table)', obj => {
            switch (obj.event) {
                case 'ADD_BOOK':
                    openAddEditDom('书籍入库', '#addSubmit');
                    break;
            }
        });

        table.on('tool', obj => {
            switch (obj.event) {
                case 'edit':
                    openAddEditDom('编辑书籍信息', '#editSubmit', () => {
                        form.val('addEditForm', obj.data);
                        form.render();
                        $('#editSubmit').data('id', obj.data.id);
                    })
                    break;
                case 'delete':
                    layer.confirm('确定要删除该书吗', {icon: 3, skin: 'layui-layer-admin'}, (index) => {
                        admin.req({
                            type: 'get',
                            url: '{{route('book.delete')}}',
                            data: {id: obj.data.id},
                            done: response => {
                                layer.msg(response.msg, {icon: 1});
                                layer.closeAll(1);
                                obj.del();
                            }
                        });
                    });
                    break;
            }
        });

        form.on('submit(searchSubmit)', data => {
            tableInstance.config.where.query = data.field;
            tableInstance.reload();
            return false;
        });

        form.on('submit(addSubmit)', data => {
            admin.req({
                type: 'post',
                url: '{{route('book.create')}}',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {icon: 1});
                    layer.closeAll('page');
                    tableInstance.reload();
                }
            });
        })

        form.on('submit(editSubmit)', data => {
            data.field.id = $('#editSubmit').data('id');
            admin.req({
                type: 'post',
                url: '{{route('book.update')}}',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {icon: 1});
                    layer.closeAll('page');
                    tableInstance.reload();
                }
            });
        })
    });

    function openAddEditDom(title, btn, success = () => {
        layui.form.render()
    }) {
        const admin = layui.admin;
        const $ = layui.$;
        const form = layui.form;
        admin.popup({
            type: 1,
            title: title,
            area: '30%',
            content: $('#addEditDom').html(),
            success: success,
            btn: ['保存'],
            yes: () => {
                $(btn).click();
            }
        });
    }
</script>
<script type="text/html" id="optDom">
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="delete">删除</a>
</script>
<script type="text/html" id="searchDom">
    <form class="layui-form layui-form-pane" id="searchForm" lay-filter="searchForm">
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="name" autocomplete="new" placeholder="书名 - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="author" autocomplete="new" placeholder="作者 - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="publisher" autocomplete="new" placeholder="出版社 - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="ISBN" autocomplete="new" placeholder="ISBN - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <input type="text" class="layui-input" name="category" autocomplete="new" placeholder="分类 - 模糊"/>
        </div>
        <input type="submit" class="layui-btn" lay-filter="searchSubmit" lay-submit value="搜索"/>
        <input type="reset" class="layui-btn layui-btn-normal" value="重置"/>
    </form>
</script>
<script type="text/html" id="addEditDom">
    <div class="layui-form layui-form-pane" id="addEditForm" lay-filter="addEditForm">
        <div class="layui-form-item">
            <label class="layui-form-label">书名</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="name" autocomplete="new" placeholder="书籍全名"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">作者</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="author" autocomplete="new" placeholder="该书作者"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">出版社</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="publisher" autocomplete="new" placeholder="出版社"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">版本</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="edition" autocomplete="new" placeholder="版本"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">ISBN</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="ISBN" autocomplete="new" placeholder="书籍ISBN编号，不可重复"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">类别</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="category" autocomplete="new" placeholder="书籍分类"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="price" autocomplete="new" placeholder="借出价格"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">库存</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="num" autocomplete="new" placeholder="入库数量"
                       lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">位置</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="location" autocomplete="new" placeholder="存放位置"
                       lay-verify="required">
            </div>
        </div>
        @csrf
        <input type="submit" class="layui-hide" id="addSubmit" lay-filter="addSubmit" lay-submit>
        <input type="submit" class="layui-hide" id="editSubmit" lay-filter="editSubmit" lay-submit>
    </div>
</script>
</html>
