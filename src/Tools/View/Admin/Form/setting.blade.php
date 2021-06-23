<style>
    body.dragging, body.dragging * {
        cursor: move !important;
    }
    .dragged {
        position: absolute;
        opacity: 0.5;
        z-index: 1000;
        background: #fff;
    }
    .app-form li.placeholder {
        position: relative;
        border: 1px solid #1e5dff;
    }

    .app-form li.placeholder:before {
        position: absolute;
    }
</style>
<div class="flex form-edit p-6 space-x-4">
    <div class="flex-none w-72">
        <div class="bg-white rounded shadow">
            <div class="text-base p-4 border-b border-gray-300">表单组件</div>
            <ul class="app-package grid grid-cols-2 divide-x divide-gray-300" app-package>
            </ul>
        </div>
    </div>
    <div class="flex-grow bg-white rounded shadow">
            <div class="flex p-6 pb-0 items-center">
                <div class="flex-grow text-base">表单设计区域</div>
                <div class="flex-none">
                    <a href="javascript:;" app-save class="btn-blue">保存</a>
                </div>
            </div>
            <ul class="app-form p-6 flex flex-col space-y-2" app-form>
            </ul>
    </div>

</div>
<script>
    Do('sortable', function () {

        let sortIcon = '<i class="fas fa-arrows-alt"></i>';

        let formEl = {
            text: {
                name: '文本框',
                tpl: (info) => {
                    if (info.data.type == 'text') {
                        return `<input type="text" class="form-input" name="${info.field}" placeholder="请输入${info.name}">`
                    }
                    if (info.data.type == 'number') {
                        return `<input type="number" class="form-input" name="${info.field}" placeholder="请输入${info.name}">`
                    }
                    if (info.data.type == 'email') {
                        return `<input type="text" class="form-input" data-js="form-mask" data-inputmask-alias="email" name="${info.field}" value="" placeholder="请输入${info.name}" style="" inputmode="text">`
                    }
                    if (info.data.type == 'tel') {
                        return `<input type="text" class="form-input" data-js="form-mask" data-inputmask-alias="19999999999" name="${info.field}" value="" placeholder="请输入${info.name}" style="" inputmode="text">`
                    }
                    if (info.data.type == 'password') {
                        return `<input type="password" class="form-input" name="${info.field}" placeholder="请输入${info.name}">`
                    }
                    if (info.data.type == 'ip') {
                        return `<input type="text" class="form-input" data-js="form-mask" data-inputmask-alias="ip" name="${info.field}" value="" placeholder="请输入${info.name}" style="" inputmode="text">`
                    }
                    if (info.data.type == 'url') {
                        return `<input type="text" class="form-input" data-js="form-mask" data-inputmask-alias="url" name="${info.field}" value="" placeholder="请输入${info.name}" style="" inputmode="text">`
                    }
                    if (info.data.type == 'date') {
                        return `<input type="date" class="form-input" name="${info.field}" placeholder="请输入${info.name}">`
                    }
                    if (info.data.type == 'time') {
                        return `<input type="time" class="form-input" name="${info.field}" placeholder="请输入${info.name}">`
                    }
                },
                icon: 'fa fa-pen',
                field: 'text',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                    {
                        name: '类型',
                        field: 'type',
                        type: 'radio',
                        data: {
                            'text': '文本',
                            'number': '数字',
                            'email': '邮箱',
                            'tel': '手机号码',
                            'password': '密码',
                            'ip': 'IP地址',
                            'url': '网址',
                            'date': '日期',
                            'time': '时间',
                        }
                    }
                ],
                data: {
                    required: 0,
                    type: 'text'
                }
            },
            select: {
                name: '下拉选择',
                tpl: (info) => {
                    let html = '';
                    html += `<select class="form-select" name="${info.field}">`;

                    let options = info.data.options.split("\n")

                    options.map(value => {
                        html += `<option value="${value}">${value}</option>`;
                    })
                    html += `</select>`;
                    return html;
                },
                icon: 'fa fa-list',
                field: 'select',
                options: [
                    {
                        name: '下拉选项',
                        field: 'options',
                        type: 'textarea',
                        help: '一行一个选项'
                    }
                ],
                data: {
                    options: '选项一\n选项二'
                }
            },
            radio: {
                name: '单选项',
                tpl: (info) => {
                    let html = '';
                    let options = info.data.options.split("\n")
                    html += `<div class="flex flex-row space-x-4">`
                    options.map((value, key) => {
                        html += `
                                <label class="block">
                                  <input class="form-radio mr-2" ${key === 0 ? 'checked' : ''} type="radio" style="" name="${info.field}" value="${value}">
                                  ${value}
                                </label>
                                `
                    })
                    html += `</div>`
                    return html;
                },
                icon: 'fa fa-check-circle',
                field: 'radio',
                options: [
                    {
                        name: '单选项',
                        field: 'options',
                        type: 'textarea',
                        help: '一行一个选项'
                    }
                ],
                data: {
                    options: '选项一\n选项二'
                }
            },
            checkbox: {
                name: '多选项',
                tpl: (info) => {
                    let html = '';
                    let options = info.data.options.split("\n")
                    html += `<div class="flex flex-row space-x-4">`
                    options.map((value, key) => {
                        html += `
                                <label class="block">
                                    <input class="form-checkbox mr-2" ${key === 0 ? 'checked' : ''} type="checkbox" style="" name="${info.field}[]" value="${value}">
                                    ${value}
                                </label>
                               `
                    })
                    html += `</div>`
                    return html;
                },
                icon: 'fa fa-check-square',
                field: 'checkbox',
                options: [
                    {
                        name: '多选项',
                        field: 'options',
                        type: 'textarea',
                        help: '一行一个选项'
                    }
                ],
                data: {
                    options: '选项一\n选项二'
                }
            },
            image: {
                name: '图片上传',
                tpl: (info) => {
                    return `<div class="relative w-24 h-24 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900" style="background-image: url('{{route('service.image.placeholder',
            ['w' => 180, 'h' => 180, 't' => '选择图片'])}}')">
                                <div class="opacity-0 hover:opacity-100 absolute flex items-center justify-center w-full h-full bg-blue-200 bg-opacity-90 rounded cursor-pointer ">
                                    <div class="text-blue-900 w-6 h-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <input name="${info.field}" type="hidden" value="'.$value.'">
                                </div>
                            </div>`
                },
                icon: 'fa fa-image',
                field: 'image',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                    {
                        name: '上传方式',
                        field: 'type',
                        type: 'radio',
                        data: {
                            0: '文件管理器',
                            1: '本地上传'
                        }
                    },
                ],
                data: {
                    required: 0,
                    type: 0,
                }
            },
            images: {
                name: '多图片上传',
                tpl: (info) => {
                    return `<div class="flex space-x-4" >
                                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900" style="background-size:90%; background-image:url('{{route('service.image.placeholder',
                    ['w' => 180, 'h' => 180, 't' => '图片'])}}')">
                                    <div class="opacity-0 hover:opacity-100 absolute flex items-center justify-center w-full h-full bg-blue-200 bg-opacity-90 rounded cursor-pointer">
                                        <button type="button" class="btn-red" data-del="">删除</button>
                                    </div>
                                    <input type="hidden" name="${info.field}[]" value="h">
                                </div>
                                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block hover:border-blue-900">
                                    <div class="text-gray-500 hover:text-blue-900 absolute flex items-center justify-center w-full h-full bg-gray-100 bg-opacity-90 rounded cursor-pointer">
                                        <div class=" w-6 h-6">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                },
                icon: 'fa fa-images',
                field: 'images',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                    {
                        name: '上传方式',
                        field: 'type',
                        type: 'radio',
                        data: {
                            0: '文件管理器',
                            1: '本地上传'
                        }
                    },
                    {
                        name: '图片数量',
                        field: 'num',
                        type: 'text',
                    },
                ],
                data: {
                    type: 0,
                    required: 0,
                    num: 5
                }
            },
            file: {
                name: '文件上传',
                tpl: (info) => {
                    return `<div class="form-input-group form-input-group-after">
                                <input type="text" class="form-input" readonly name="${info.field}" placeholder="请选择${info.name}">
                                <button type="button" class="form-input-label-after focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path><polyline points="9 15 12 12 15 15"></polyline><line x1="12" y1="12" x2="12" y2="21"></line></svg>
                                上传
                                </button>
                            </div>`
                },
                icon: 'fa fa-file',
                field: 'file',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                    {
                        name: '上传方式',
                        field: 'type',
                        type: 'radio',
                        data: {
                            0: '文件管理器',
                            1: '本地上传'
                        }
                    },
                ],
                data: {
                    type: 0,
                    required: 0,
                }
            },
            date: {
                name: '日期时间',
                tpl: (info) => {
                    return `<input type="text" class="form-input" name="${info.field}" data-js="form-date" data-type="${info.data.type}" placeholder="请选择${info.name}">`
                },
                icon: 'fa fa-calendar-alt',
                field: 'date',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                    {
                        name: '类型',
                        field: 'type',
                        type: 'radio',
                        data: {
                            'date': '日期',
                            'time': '时间',
                            'datetime': '日期时间',
                            'range': '时间范围',
                        }
                    }
                ],
                data: {
                    required: 0,
                    type: 'date'
                }
            },
            editor: {
                name: '编辑器',
                tpl: (info) => {
                    return `<textarea class="form-textarea" name="${info.field}" placeholder="请输入${info.name}内容"></textarea>`
                },
                icon: 'fa fa-edit',
                field: 'editor',
                options: [
                    {
                        name: '验证',
                        field: 'required',
                        type: 'radio',
                        data: {
                            0: '选填',
                            1: '必填'
                        }
                    },
                ],
                data: {
                    required: 0,
                }
            },
            color: {
                name: '颜色选择',
                tpl: (info) => {
                    if (info.data.type == 'color') {
                        let html = '', colors = [
                            'while',
                            'black',
                            'blue',
                            'yellow',
                            'green',
                            'red',
                            'purple',
                        ];
                        html += `<div class="flex flex-row space-x-2">`
                        colors.map((vo, key) => {
                            html += `
                                <label class="form-color ">
                                    <input ${key === 0 ? 'checked' : ''} type="radio" style="" name="${info.field}[]" value="${vo}">
                                    <span class="form-color-show bg-${vo === 'while' || vo === 'black' ? vo : vo + '-900'} ring-${vo}-900"></span>
                                </label>
                               `
                        })
                        html += `</div>`
                        return html
                    }
                    if (info.data.type == 'picker') {
                        return `<input type="color" class="form-input" value="" name="${info.field}" style="">`;
                    }
                },
                icon: 'fas fa-swatchbook',
                field: 'color',
                options: [
                    {
                        name: '类型',
                        field: 'type',
                        type: 'radio',
                        data: {
                            'color': '固定颜色',
                            'picker': '自定义颜色',
                        }
                    }
                ],
                data: {
                    type: 'color'
                }
            },
        }

        let formData = @json($info->data ? $info->data : []);

        let renderForm = () => {
            let html = '';
            formData.map((item, key) => {
                html += `<li data-key="${key}" class="border border-gray-400 border-dashed ">
                            <div class="flex items-center p-4 space-x-2">
                                <div class="flex-none icon-move text-gray-500">${sortIcon}</div>
                                <label class="flex-none col-">${item.name}</label>
                                <div class="pl-6 flex-grow">
                                    ${formEl[item.type].tpl(item)}
                                </div>
                                <div class="flex space-x-2">
                                    <a href="javascript:;" data-option="${key}" class="block btn-blue btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
                                        </a>
                                    <a href="javascript:;" data-remove="${key}" class="block btn-red btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
</svg>
                                    </a>
                                </div>
                            </div>
                </li>`
            })
            $('[app-form]').html(html)
        }
        renderForm();

        let renderPackage = () => {
            let html = '';
            for (let key in formEl) {
                let item = formEl[key]
                html += `<li data-key="${key}" class="cursor-pointer hover:bg-gray-200">
                            <div class="flex flex-col items-center py-4 border-b border-gray-300">
                               <div><i class="${item.icon} w-6 h-6 text-lg text-gray-700"></i></div>
                               <div class="mt-2 text-gray-500">${item.name}</div>
                            </div>
                        </li>`
            }
            $('[app-package]').html(html)
        }
        renderPackage();

        let group = $("[app-form]").sortable({
            handle: '.icon-move',
            onDrop: function ($item, container, _super) {
                let data = group.sortable("serialize").get(0)
                let tmpData = []
                data.map((item) => {
                    tmpData.push(formData[item.key])
                })
                formData = tmpData
                renderForm()
                _super($item, container)
            }
        });

        $("[app-package]").on('click', '> li', function () {
            let key = $(this).data('key');
            formData.push({
                type: key,
                name: formEl[key].name,
                field: formEl[key].field,
                data: formEl[key].data,
                list: formEl[key].list
            })
            renderForm()
        })

        $("[app-form]").on('click', '[data-option]', function () {
            let html = '<form class="p-4 flex flex-col space-y-4">';
            let key = $(this).data('option')
            let info = formData[key]
            let config = formEl[info.type]

            html += `<div>
                      <label class="mb-2 block">标题</label>
                      <input class="form-input" type="text" placeholder="请输入元素标题" name="name" value="${info.name}">
                    </div>`;

            html += `<div>
                      <label class="mb-2 block">字段名</label>
                      <input class="form-input" type="text" placeholder="请输入字段名称" name="field" value="${info.field}">
                    </div>`;


            html += `<div>
                      <label class="mb-2 block">列表展示</label>
                      <div class="flex flex-col space-y-2">
                            <label>
                              <input class="form-radio mr-2" ${info.list ? 'checked' : ''} type="radio" style="" name="list" value="1">
                              显示
                            </label>
                            <label>
                              <input class="form-radio mr-2" ${!info.list ? 'checked' : ''} type="radio" style="" name="list" value="0">
                              隐藏
                            </label>
                        </div>
                    </div>`;

            if (config.options) {
                config.options.map(item => {
                    html += `<div><label class="mb-2 block">${item.name}</label>`
                    if (item.type == 'textarea') {
                        html += `<textarea class="form-input" rows=5 name="${item.field}">${info.data[item.field]}</textarea>`
                    }
                    if (item.type == 'text') {
                        html += `<input class="form-input" name="${item.field}" value="${info.data[item.field]}">`
                    }
                    if (item.type == 'radio') {
                        html += `<div class="flex flex-col space-y-2">`
                        for (let k in item.data) {
                            html += `
                                        <label>
                                          <input class="form-radio mr-2" ${info.data[item.field] == k ? 'checked' : ''} type="radio" style="" name="${item.field}" value="${k}">
                                          ${item.data[k]}
                                        </label>
                                    `
                        }
                        html += `</div>`
                    }
                    if (item.help) {
                        html += `<div class="mt-1 text-gray-500">${item.help}</div>`;
                    }
                    html += `</div>`
                })
            }
            html += '</form>';
            html += `<div class="flex justify-end p-4 space-x-2">
                        <button type="button" class="btn" modal-close>取消</button>
                        <button type="button" class="btn-blue" data-save modal-close>保存</button>
                    </div>`;

            dialog.layout({
                title: '字段配置',
                html: html,
                callback: function ($modal) {
                    let form = $modal.find('form')
                    $modal.find('[data-save]').click(function () {
                        let data = app.serializeObject(form)
                        for (let k in data) {
                            if (k === 'name' || k === 'field' || k === 'list') {
                                formData[key][k] = data[k]
                            } else {
                                formData[key]['data'][k] = data[k]
                            }
                        }
                        renderForm()
                    })
                }
            });
        })

        $("[app-form]").on('click', '[data-remove]', function () {
            let key = $(this).data('remove')
            $(this).parents('li').remove()
            formData.splice(key, 1)
            renderForm()
        })

        $("[app-save]").on('click', function () {
            let fields = [];
            for (let i in formData) {
                let item = formData[i]
                if (!item.field) {
                    app.error('表单元素未设置字段名')
                    return false
                }
                if (fields.length && $.inArray(item.field, fields) !== -1) {
                    app.error('表单元素字段名有重复')
                    return false
                }
                fields.push(item.field)
            }

            app.ajax({
                url: "{{route('admin.tools.form.setting.save', ['id' => $id])}}",
                type: 'post',
                notify: false,
                data: {
                    data: formData
                }
            }).then(function (data) {
                dialog.confirm({
                    title: '完成',
                    content: data.message,
                    success: function () {
                        window.location.href = data.url
                    },
                    cancel: function () {
                        location.reload();
                    }
                });
            }).catch(function (error) {
                app.error(error.message)
            })
        })

    });
</script>
