<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/sortablejs@1"></script>
<script src="https://unpkg.com/vuedraggable@4"></script>
<div class="p-5" id="counter">
    <div class="mb-3">
        <div class="flex items-center">
            <div class="flex-grow">
                <a href="javascript:window.history.back();" class="text-xs items-center text-gray-500 hidden lg:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon w-5 h-5" viewBox="0 0 24 24" stroke-width="2"
                         stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <line x1="5" y1="12" x2="11" y2="18"></line>
                        <line x1="5" y1="12" x2="11" y2="6"></line>
                    </svg>
                    返回
                </a>
                <div class="text-lg lg:text-xl">
                    表单管理
                </div>
            </div>
            <div class="flex-none items-center hidden lg:flex gap-2">
                <button class="btn-blue" type="button" @click="submit" :disabled="saveing">
                    保存
                </button>
            </div>
        </div>
    </div>

    <div class="flex form-edit space-x-4">
        <div class="flex-none w-56 self-start">
            <div class="bg-white border border-b-0 border-r-0 border-gray-300">
                <div class="text-base p-4 border-b border-gray-300 border-r border-gray-300  bg-gray-200">
                    表单组件
                </div>
                <ul class="app-package flex flex-wrap ">
                    <li class="cursor-pointer hover:bg-gray-200 w-1/2 border-b border-r border-gray-300"
                        v-for="(item, index) in formPackage" @click="addFormPackage(index, $event)">
                        <div class="flex flex-col items-center py-4">
                            <div><i class="w-6 h-6 leading-6 text-center text-lg text-gray-700"
                                    :class="item.icon"></i></div>
                            <div class="mt-2 text-gray-500">@{{ item.name }}</div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
        <div class="flex-grow bg-white border border-gray-300 self-start">
            <div class="text-base p-4 border-b border-gray-300 border-gray-200 bg-gray-200">设计区域</div>


            <div class="flex justify-center items-center p-10" v-if="!formData.length">
                <div class="text-base text-gray-500 flex flex-col justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <div class="mt-4">请点击左边工具添加元素</div>
                </div>
            </div>

            <div v-if="formData.length">
                <ul class="app-form p-6 flex flex-col space-y-2 min-h-">
                    <draggable v-model="formData" @start="drag=true" @end="drag=false">
                        <template #item="{element, index}">
                            <div class="border border-gray-400 border-dashed"
                                 :class="{'border-blue-900' : formItemActive === index}"
                                 @click="editForm(index, $event)" ref="formItem">
                                <div class="flex items-center p-4 space-x-2">
                                    <label class="flex-none w-24 truncate">@{{ element.name }}</label>
                                    <div class="pl-6 flex-grow">
                                        <component :is="formPackage[element.type].component"
                                                   :params="element"></component>
                                    </div>
                                    <div class="flex-none">
                                        <span class="btn-red" @click="delForm(index)">删除</span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </ul>
            </div>
        </div>
        <div class="flex-none w-56 self-start" v-if="Object.keys(formItem).length > 0">
            <div class="bg-white border border-gray-300" @click="$event.stopPropagation()">
                <div class="text-base p-4 border-b border-gray-300 border-gray-200 bg-gray-200">元素配置</div>
                <div class="p-4 flex flex-col space-y-4">
                    <div>
                        <label class="mb-2 block text-gray-500">标题</label>
                        <input class="form-input" type="text" placeholder="请输入元素标题" v-model="formItem.name">
                    </div>
                    <div>
                        <label class="mb-2 block text-gray-500">字段名</label>
                        <input class="form-input" type="text" placeholder="请输入字段名称"
                               v-model="formItem.field">
                    </div>
                    <div>
                        <label class="mb-2 block text-gray-500">列表展示</label>
                        <div class="flex flex-col gap-2 flex-wrap">
                            <label>
                                <input class="form-radio" type="radio"
                                       v-model="formItem.list" value="1">
                                显示
                            </label>
                            <label>
                                <input class="form-radio" type="radio"
                                       v-model="formItem.list" value="0">
                                隐藏
                            </label>
                        </div>
                    </div>
                    <div v-for="item in formPackage[formItem.type].options">
                        <div v-if="item.type === 'list'">
                            <div class="mb-2 block text-gray-500 flex">
                                <div class="flex-grow">@{{ item.name }}</div>
                                <div class="flex-none text-blue-900 cursor-pointer hover:underline"
                                     @click="addFormOptions(formItem.data[item.field])">增加
                                </div>
                            </div>
                            <template v-for="(vo, key) in formItem.data[item.field]">
                                <div class="flex gap-4 items-center mt-2">
                                    <input class="form-input flex-grow"
                                           v-model="formItem.data[item.field][key]">
                                    <div class="flex-none text-blue-900 cursor-pointer hover:underline"
                                         @click="delFormOptions(formItem.data[item.field], key)">删除
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div v-if="item.type === 'textarea'">
                            <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
                            <textarea class="form-input" rows=5
                                      :name="item.field">@{{formItem.data[item.field]}} </textarea>
                        </div>
                        <div v-if="item.type === 'text'">
                            <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
                            <input class="form-input" v-model="formItem.data[item.field]">
                        </div>
                        <div v-if="item.type === 'radio'" class="flex flex-col space-y-2">
                            <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
                            <label v-for="(value, key) in item.data">
                                <input class="form-radio mr-2" type="radio"
                                       v-model="formItem.data[item.field]" :value="key">
                                @{{ value }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">

    const defaultData = @json($info->data ? $info->data : []);

    const formPackage = {
        text: {
            name: '文本框',
            icon: 'fa fa-pen',
            field: 'text',
            component: {
                props: ['params'],
                template: `<input disabled type="text" class="form-input" :name="params.field" :placeholder="'请输入' + params.name">`
            },
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
            icon: 'fa fa-list',
            field: 'select',
            component: {
                props: ['params'],
                template: `<select disabled class="form-select" :placeholder="'请选择' + params.name">
                  <option v-for='(item, index) in params.data.options' :value="index" >@{{item}}</option>
                </select>`
            },
            options: [{
                name: '下拉选项',
                field: 'options',
                type: 'list',
            }],
            data: {
                options: []
            }
        },
        radio: {
            name: '单选项',
            icon: 'fa fa-check-circle',
            field: 'radio',
            component: {
                props: ['params'],
                template: `<div class="flex flex-row space-x-4">
                  <label class="block" v-for='(item, index) in params.data.options'>
                      <input class="form-radio mr-2" :checked="index === 0" disabled type="radio" :value="index">
                      @{{item}}
                  </label>
                </div>`
            },
            options: [{
                name: '单选项',
                field: 'options',
                type: 'list',
            }],
            data: {
                options: [
                    '选项一',
                    '选项二'
                ]
            }
        },
        checkbox: {
            name: '多选项',
            icon: 'fa fa-check-square',
            field: 'checkbox',
            component: {
                props: ['params'],
                template: `<div class="flex flex-row space-x-4">
                  <label class="block" v-for='(item, index) in params.data.options'>
                      <input class="form-checkbox mr-2" disabled type="checkbox" :value="index">
                      @{{item}}
                  </label>
                </div>`
            },
            options: [{
                name: '多选项',
                field: 'options',
                type: 'list',
            }],
            data: {
                options: [
                    '选项一',
                    '选项二'
                ]
            }
        },
        image: {
            name: '图片上传',
            icon: 'fa fa-image',
            field: 'image',
            component: {
                props: ['params'],
                template: `<div class="relative w-24 h-24 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block" style="background-image: url('{{route('service.image.placeholder', ['w' => 180, 'h' => 180, 't' => '选择图片'])}}')"></div>`
            },
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
            icon: 'fa fa-images',
            field: 'images',
            component: {
                props: ['params'],
                template: `<div class="flex space-x-4" >
                                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block" style="background-size:90%; background-image:url('{{route('service.image.placeholder',
                    ['w' => 180, 'h' => 180, 't' => '图片'])}}')">
                                </div>
                                <div class="relative w-32 h-32 border-2 border-gray-400 border-dashed rounded bg-cover bg-center bg-no-repeat block">
                                    <div class="text-gray-500 absolute flex items-center justify-center w-full h-full bg-gray-100 bg-opacity-90 rounded cursor-pointer">
                                        <div class="text-base">
                                            上传
                                        </div>
                                    </div>
                                </div>
                            </div>`
            },
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
            icon: 'fa fa-file',
            field: 'file',
            component: {
                props: ['params'],
                template: `<div class="form-input-group form-input-group-after">
                                <input type="text" class="form-input" readonly :placeholder="'请选择' + params.name" disabled>
                                <button type="button" class="form-input-label-after focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"></path><polyline points="9 15 12 12 15 15"></polyline><line x1="12" y1="12" x2="12" y2="21"></line></svg>
                                上传
                                </button>
                            </div>`
            },
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
            icon: 'fa fa-calendar-alt',
            field: 'date',
            component: {
                props: ['params'],
                template: `<input type="text" class="form-input" :placeholder="'请选择' + params.name">`
            },
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
            icon: 'fa fa-edit',
            field: 'editor',
            component: {
                props: ['params'],
                template: `<textarea class="form-textarea" disabled :placeholder="'请输入' + params.name + '内容'"></textarea>`
            },
            options: [{
                name: '验证',
                field: 'required',
                type: 'radio',
                data: {
                    0: '选填',
                    1: '必填'
                }
            },],
            data: {
                required: 0,
            }
        },
        color: {
            name: '颜色选择',
            icon: 'fas fa-swatchbook',
            field: 'color',
            component: {
                props: ['params'],
                template: `<div class="flex flex-row space-x-2">
                    <label class="form-color" v-for="(item, key) in ['while', 'black', 'blue', 'yellow', 'green', 'red', 'purple' ]">
                        <input :checked="key === 0" type="radio" style="" disabled>
                        <span class="form-color-show" :class="'bg-' + (item === 'while' || item === 'black' ? item : item + '-900')"></span>
                    </label>
                </div>`
            },
            options: [
                {
                    name: '类型',
                    field: 'type',
                    type: 'radio',
                    data: {
                        'color': '选项颜色',
                        'picker': '自由颜色',
                    }
                }],
            data: {
                type: 'color'
            }
        }
    };

    const Counter = {
        mounted() {
            document.addEventListener("click", () => {
                this.formItemActive = false
                this.formItem = {}
                this.tableItemActive = false
                this.tableItem = {}
            });
        },
        data() {
            return {
                drag: false,
                saveing: false,
                formData: defaultData ? defaultData : [],
                formItemActive: false,
                formPackage: formPackage,
                formItem: {},
            }
        },
        methods: {
            addFormPackage(index) {
                this.formData.push(JSON.parse(JSON.stringify({
                    type: index,
                    name: this.formPackage[index].name,
                    field: this.formPackage[index].field,
                    data: this.formPackage[index].data,
                    list: 1,
                })))
            },
            editForm(index, e) {
                e.stopPropagation()
                this.formItemActive = index
                this.formItem = this.formData[index]
            },
            delForm(index) {
                this.formData.splice(index, 1)
            },
            addFormOptions(options, value) {
                options.push(value || '');
            },
            delFormOptions(options, key) {
                options.splice(key, 1)
            },
            submit: function () {
                let fields = [];
                for (let i in this.formData) {
                    let item = this.formData[i]
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
                this.saveing = true

                app.ajax({
                    url: "{{route('admin.tools.form.setting.save', ['id' => $id])}}",
                    type: 'post',
                    notify: false,
                    data: {
                        data: this.formData
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
                    this.saveing = false
                })
            }
        }
    }

    const vueApp = Vue.createApp(Counter)
    vueApp.component('draggable', window.vuedraggable)
    vueApp.mount('#counter')
</script>