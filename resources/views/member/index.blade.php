<!DOCTYPE html>
<html>
<head>
    @include('layout.head')
</head>
<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space10">
        <div class="layui-col-md3">
            <div class="layui-card" id="infoCard">
                <div class="layui-card-header">会员信息</div>
                <div class="layui-card-body"
                     style="height: 386px;display: flex;align-items: center;justify-content: center;">
                    <p>
                        <span>等待读取会员信息</span>
                        <icon class="layui-icon layui-icon-refresh layui-anim layui-anim-rotate layui-anim-loop"></icon>
                    </p>
                </div>
            </div>
            <div class="layui-card layui-tab layui-tab-brief" id="memberFunction" style="flex-grow:1;">
                <ul class="layui-tab-title layui-card-header">
                    <li class="layui-this">账户充值</li>
                    <li>借书登记</li>
                    <li>修改信息</li>
                    <li>账户注销</li>
                </ul>
                <div class="layui-tab-content" style="padding: 10px 15px;">
                    <div class="layui-tab-item layui-show">
                        <div class="layui-form layui-form-pane" id="chargeForm">
                            <div class="layui-form-item">
                                <label class="layui-form-label">充值金额</label>
                                <div class="layui-input-inline">
                                    <input type="number" step="100" class="layui-input" name="chargeNum"
                                           autocomplete="off" placeholder="100起充，整百" lay-verify="required">
                                </div>
                                @csrf
                                <input type="submit" class="layui-btn layui-btn-normal" value="提交"
                                       lay-filter="chargeSubmit"
                                       lay-submit>
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <div class="layui-form layui-form-pane" id="borrowForm">
                            <div class="layui-form-item">
                                <label class="layui-form-label">ISBN</label>
                                <div class="layui-input-inline">
                                    <input type="number" class="layui-input" name="ISBN"
                                           autocomplete="off" placeholder="书号" lay-verify="required">
                                </div>
                                @csrf
                                <input type="submit" class="layui-btn layui-btn-normal" value="提交"
                                       lay-filter="borrowSubmit" lay-submit>
                            </div>
                        </div>
                    </div>
                    <div class="layui-tab-item">
                        <div class="layui-form layui-form-pane" lay-filter="editForm" id="editForm">
                            <div class="layui-form-item">
                                <label class="layui-form-label">姓名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" autocomplete="new" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">年龄</label>
                                <div class="layui-input-block">
                                    <input type="number" name="age" autocomplete="new" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="gender" value="1" title="男性">
                                    <div class="layui-unselect layui-form-radio">
                                        <i class="layui-anim layui-icon"></i>
                                        <div>男性</div>
                                    </div>
                                    <input type="radio" name="gender" value="0" title="女性">
                                    <div class="layui-unselect layui-form-radio layui-form-radioed">
                                        <i class="layui-anim layui-icon"></i>
                                        <div>女性</div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">职业</label>
                                <div class="layui-input-block">
                                    <input type="text" name="profession" autocomplete="new" class="layui-input">
                                </div>
                            </div>
                            @csrf
                            <a class="layui-btn layui-btn-fluid layui-btn-normal" lay-submit lay-filter="editSubmit">确认修改</a>
                        </div>
                    </div>
                    <div class="layui-tab-item">内容4</div>
                </div>
            </div>
        </div>
        <div class="layui-col-md9">
            <div class="layui-card" id="billCard">
                <div class="layui-card-header">借书记录</div>
                <div class="layui-card-body">
                    <table id="book_table" lay-filter="bill_table"></table>
                </div>
            </div>
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
        const element = layui.element;
        const cards = [
            {id: 1, password: '$2y$10$7Ihi6Ip4CitVkUSQQwGcZOPGbsFm3XaxU2Xy8A4kZiUnhQv5fVL0e'},
        ];
        let total = @json(Auth::guard('memberCard')->user());
        let tableInstance;
        if (total === null) {
            admin.popup({
                type: 1,
                content: '请把借阅证放在读卡器上',
                btn: ['已放置，读卡'],
                shadeClose: false,
                success: elem => elem.find('i').remove(),
                yes: index => {
                    admin.req({
                        url: '{{route('member.loginByCard')}}',
                        type: 'post',
                        data: $.extend(cards[0], {_token: '{{csrf_token()}}'}),
                        done: response => {
                            layer.msg(`会员 <b>${response.data.name}</b> 已${response.msg}`, {icon: 1});
                            layer.close(index);
                            total = response.data;
                            loadInfoForm();
                            loadBillTable();
                        }
                    });
                },
            });
        } else {
            loadInfoForm();
            loadBillTable();
        }

        element.on('tab', function (data) {
            if (data.index === 2) {
                form.val('editForm', total);
                form.render();
            }
        });

        form.on('submit(editSubmit)', data => {
            admin.req({
                type: 'post',
                url: '{{route('member.edit')}}',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {icon: 1, time: 1000});
                    window.location.reload();
                }
            });
        });

        //充值
        form.on('submit(chargeSubmit)', data => {
            admin.req({
                url: '{{route('member.recharge')}}',
                type: 'post',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {
                        icon: 1,
                        time: 1000,
                        end: () => {
                            window.location.reload();
                        }
                    });
                }
            });
        });

        //借书
        form.on('submit(borrowSubmit)', data => {
            admin.req({
                url: '{{route('member.borrow')}}',
                type: 'post',
                data: data.field,
                done: response => {
                    layer.msg(response.msg, {
                        icon: 1,
                        time: 1000,
                        end: () => {
                            tableInstance.reload();
                            $('#borrowForm input[name=ISBN]').val('');
                        }
                    });
                }
            });
        });

        form.on('submit(searchSubmit)', data => {
            tableInstance.config.where.query = data.field;
            tableInstance.reload();
            return false;
        });

        function loadInfoForm() {
            $('#infoCard .layui-card-body').empty().append($('#infoDom').html()).removeAttr('style');
            form.val('infoForm', total);
            form.render();
        }

        function loadBillTable() {
            tableInstance = table.render({
                elem: '#book_table',
                url: '{{route('member.borrowList')}}',
                height: 'full-100',
                toolbar: '#searchDom',
                page: layui.setter.page,
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
                    form.render();
                },
                cols: [[
                    {
                        field: 'name', title: '书名', templet: dd => {
                            return dd.book.name;
                        }
                    },
                    {
                        field: 'author', title: '作者', templet: dd => {
                            return dd.book.author;
                        }, width: 100
                    },
                    {
                        field: 'ISBN', title: '书号', templet: dd => {
                            return dd.book.ISBN;
                        }, width: 150
                    },
                    {
                        field: 'started_at', title: '借出日期', width: 160
                    },
                    {
                        field: 'returnDay', title: '归还期限', templet: dd => {
                            if(dd.return){
                                if (dd.returnDay > dd.ended_at) {
                                    return dd.returnDay;
                                } else {
                                    return `<span style="color:#c8161d;">${dd.returnDay}</span>`;
                                }
                            }else{
                                if (dd.returnTimestamp > Date.now()) {
                                    return dd.returnDay;
                                } else {
                                    return `<span style="color:#c8161d;">${dd.returnDay}</span>`;
                                }
                            }
                        }, width: 120
                    },
                    {
                        field: 'delay', title: '已续借', templet: dd => {
                            return dd.delay ? '是' : '';
                        }, width: 80
                    },
                    {
                        field: 'return', title: '已归还', templet: dd => {
                            return dd.return ? '是' : '';
                        }, width: 80
                    },
                    {fixed: 'right', title: '操作', width: 120, toolbar: '#optDom'}
                ]]
            });
        }

        table.on('tool', obj => {
            switch (obj.event) {
                case 'delay':
                    delay(obj);
                    break;
                case 'return':
                    returnBook(obj);
                    break;
            }
        });

        function delay(obj) {
            layer.confirm('续借10天费用为5元，只能续借一次。', {
                skin: 'layui-layer-admin', yes: (index) => {
                    console.log(index);
                    admin.req({
                        type: 'get',
                        url: '{{route('member.delay')}}',
                        data: {bill_no: obj.data.bill_no},
                        done: response => {
                            layer.msg(response.msg, {
                                icon: 1, time: 1000, end: () => {
                                    tableInstance.reload();
                                    layer.close(index);
                                }
                            });
                        }
                    });
                }
            });
        }

        function returnBook(obj) {
            admin.req({
                type: 'get',
                url: '{{route('member.showCost')}}',
                data: {bill_no: obj.data.bill_no},
                done: response => {
                    let content = '<table class="layui-table"><tbody>';
                    let data = response.data;
                    for (let item in data) {
                        content += `<tr><td>${data[item][0]}</td><td style="text-align: right;">${data[item][1]}元</td></tr>`;
                    }
                    content += '</tbody></table>';
                    admin.popup({
                        type: 1,
                        title: '费用清单',
                        content: content,
                        btn: ['扣费并打印小票'],
                        yes: (index) => {
                            admin.req({
                                type: 'post',
                                url: '{{route('member.return')}}',
                                data: {bill_no: obj.data.bill_no, _token: '{{csrf_token()}}'},
                                done: response => {
                                    layer.msg(response.msg, {
                                        icon: 1, time: 1000, end: () => {
                                            layer.close(index);
                                            self.location.reload();
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });
        }
    });

    function logout() {
        layui.admin.req({
            type: 'get',
            url: '{{route('member.logout')}}',
            done: response => {
                layer.msg(response.msg, {
                    icon: 1, time: 2000, end: () => {
                        window.location.reload();
                    }
                });
            }
        });
    }
</script>
<script type="text/html" id="infoDom">
    <div style="border: #d9d9d9 1px dashed; padding: 10px 15px;border-radius: 5px;">
        <div class="layui-form" lay-filter="infoForm" id="infoForm">
            @csrf
            <input type="hidden" name="id">
            <div class="layui-form-item">
                <label class="layui-form-label">姓名</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" autocomplete="new" class="layui-input" readonly="readonly"
                           disabled="disabled">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">年龄</label>
                <div class="layui-input-inline">
                    <input type="number" name="age" autocomplete="new" class="layui-input" readonly="readonly"
                           disabled="disabled">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="radio" name="gender" value="1" title="男性" readonly="readonly"
                           disabled="disabled">
                    <div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i>
                        <div>男性</div>
                    </div>
                    <input type="radio" name="gender" value="0" title="女性" readonly="readonly" disabled="disabled">
                    <div class="layui-unselect layui-form-radio layui-form-radioed">
                        <i class="layui-anim layui-icon"></i>
                        <div>女性</div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">职业</label>
                <div class="layui-input-inline">
                    <input type="text" name="profession" autocomplete="new" class="layui-input" readonly="readonly"
                           disabled="disabled">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">手机号码</label>
                <div class="layui-input-inline">
                    <input type="text" name="telephone" lay-verify="phone" autocomplete="off" class="layui-input"
                           readonly="readonly" disabled="disabled">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">押金</label>
                <div class="layui-input-inline">
                    <input type="text" name="deposit" lay-verify="money" autocomplete="off" class="layui-input"
                           readonly="readonly" disabled="disabled">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">余额</label>
                <div class="layui-input-inline">
                    <input type="text" name="balance" lay-verify="money" autocomplete="off" class="layui-input"
                           readonly="readonly" disabled="disabled">
                </div>
            </div>
        </div>
        <a class="layui-btn layui-btn-fluid" onclick="logout()">退出登录</a>
    </div>
</script>
<script type="text/html" id="optDom">
    @{{# if(d.return||d.delay){ }}
    <a class="layui-btn layui-btn-xs layui-btn-normal layui-btn-disabled">续借</a>
    @{{# }else{ }}
    <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="delay">续借</a>
    @{{# } }}
    @{{# if(d.return){ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm layui-btn-disabled">归还</a>
    @{{# }else{ }}
    <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="return">归还</a>
    @{{# } }}
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
            <input type="text" class="layui-input" name="ISBN" autocomplete="new" placeholder="ISBN - 模糊"/>
        </div>
        <div class="layui-input-inline">
            <select class="layui-select" name="return">
                <option value="">是否已归还</option>
                <option value="{{\App\Models\Bill::RETURN_YES}}">已还</option>
                <option value="{{\App\Models\Bill::RETURN_NO}}">未还</option>
            </select>
        </div>
        <input type="submit" class="layui-btn" lay-filter="searchSubmit" lay-submit value="搜索"/>
        <input type="reset" class="layui-btn layui-btn-normal" value="重置"/>
    </form>
</script>
<style>
    #infoCard {
        margin-bottom: 10px;
    }
</style>
</html>
