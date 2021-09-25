<template>
  <div class="flex flex-col h-screen">
    <widget-header title="表单设计" back="true" class="flex-none">
      <n-button type="primary" @click="onSubmit" :loading="saveing">保存</n-button>
    </widget-header>

    <div class="flex flex-grow h-10">
      <n-layout class="flex-none w-56  bg-gray-100 " :native-scrollbar="false">
        <ul class="app-package flex flex-col gap-4 p-4">
          <li class="cursor-pointer bg-white shadow hover:shadow-md rounded"
              v-for="(item, index) in formPackage" @click="addFormPackage(index, $event)">
            <div class="flex items-center p-4 gap-4">
              <div class="flex-none">
                <div v-html="item.icon" class="w-6 h-6 flex items-center"></div>
              </div>
              <div class="flex-grow text-right">@{{ item.name }}</div>
            </div>
          </li>
        </ul>
      </n-layout>

      <n-layout class="bg-gray-100 flex-grow" :native-scrollbar="false">

          <div class="flex justify-center m-4 items-center p-6 bg-white shadow hover:shadow-md rounded" v-if="!formData.length">
            <div class="text-base text-gray-500 flex flex-col justify-center items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                   viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
              </svg>
              <div class="mt-4">请点击左边工具添加元素</div>
            </div>
          </div>

          <ul v-if="formData.length" class="p-4 pl-2">
            <draggable v-model="formData" @start="drag=true" @end="drag=false" class="flex flex-col  gap-4">
              <template #item="{element, index}">
                <div class="bg-white shadow hover:shadow-md rounded"
                     :class="{'border-blue-900' : formItemActive === index}"
                >
                  <div class="flex items-center p-4 gap-4">
                    <div v-html="formPackage[element.type].icon" class="flex-none w-8 h-8 flex items-center"></div>

                    <div class="flex-grow">
                      <div class="text-base mb-2">@{{ element.name }} <span class="ml-4 text-gray-400"># @{{ element.field }}</span>
                      </div>
                      <n-tag size="small">@{{formPackage[element.type].name}}</n-tag>
                    </div>

                    <div class="flex-none flex gap-4">
                      <n-button type="primary" size="small" @click="editForm(index, $event)">编辑</n-button>
                      <n-button type="error" size="small" @click="delForm(index)">删除</n-button>
                    </div>
                  </div>
                </div>
              </template>
            </draggable>
          </ul>
      </n-layout>
    </div>
    <n-modal :show="dialog">
      <n-card class="max-w-2xl my-4" content-style="padding: 0;">
        <div className="flex items-center p-4 border-b border-gray-300">
          <div className="flex-grow text-xl">编辑元素</div>
          <div className="cursor-pointer btn-close h-6 w-6 text-gray-600 hover:text-red-900" @click="dialog = false">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </div>
        </div>
        <div class="p-4 flex flex-col space-y-4">
          <div>
            <label class="mb-2 block text-gray-600">展示名称</label>
            <n-input placeholder="展示名称，如标题" v-model:value="formItem.name"/>
          </div>
          <div>
            <label class="mb-2 block text-gray-600">字段名称</label>
            <n-input placeholder="数据库字段名称，如 title" v-model:value="formItem.field"/>
          </div>
          <div>
            <label class="mb-2 block text-gray-600">列表展示</label>
            <n-switch v-model:value="formItem.list"/>
          </div>
          <div v-for="item in formPackage[formItem.type].options">
            <div v-if="item.type === 'list'">
              <label class="mb-2 block text-gray-600">@{{ item.name }}</label>
              <n-dynamic-input v-model:value="formItem.data[item.field]" placeholder="请输入选项内容"/>
            </div>
            <div v-if="item.type === 'textarea'">
              <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
              <n-input
                v-model:value="formItem.data[item.field]"
                type="textarea"
              />
            </div>
            <div v-if="item.type === 'text'">
              <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
              <n-input v-model:value="formItem.data[item.field]"/>
            </div>
            <div v-if="item.type === 'radio'" class="flex flex-col space-y-2">
              <label class="mb-2 block text-gray-500">@{{ item.name }}</label>
              <n-radio-group v-model:value="formItem.data[item.field]" name="radiogroup">
                <n-space>
                  <n-radio v-for="(value, key) in item.data" :key="key" :value="key">
                    @{{ value }}
                  </n-radio>
                </n-space>
              </n-radio-group>
            </div>
          </div>
        </div>
        <div className="p-4 flex justify-end gap-2">
          <n-button @click="dialog = false">取消</n-button>
          <n-button type="primary" @click="saveForm">保存</n-button>
        </div>
      </n-card>
    </n-modal>
  </div>
</template>

<script>
  const defaultData = @json($info->data ? $info->data : []);

  const formPackage = {
    text: {
      name: '文本框',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><rect x="6" y="6" width="36" height="36" rx="3" fill="none" stroke="#333" stroke-width="3" stroke-linejoin="round"/><path d="M16 19V16H32V19" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 34H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 18L24 34" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'text',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path d="M40 28L24 40L8 28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 10H40" stroke="#333" stroke-width="3" stroke-linecap="round"/><path d="M8 18H40" stroke="#333" stroke-width="3" stroke-linecap="round"/></svg>',
      field: 'select',
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="20" fill="none" stroke="#333" stroke-width="3"/><circle cx="24" cy="24" r="8" fill="none" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'radio',
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M39 6H9C7.34315 6 6 7.34315 6 9V39C6 40.6569 7.34315 42 9 42H39C40.6569 42 42 40.6569 42 39V9C42 7.34315 40.6569 6 39 6Z" fill="none" stroke="#333" stroke-width="3"/><path d="M32 16H16V32H32V16Z" fill="none" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'checkbox',
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M38 21V40C38 41.1046 37.1046 42 36 42H8C6.89543 42 6 41.1046 6 40V12C6 10.8954 6.89543 10 8 10H26.3636" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 31.0308L18 23L21 26L24.5 20.5L32 31.0308H12Z" fill="none" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M34 10H42" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/><path d="M37.9941 5.79544V13.7954" stroke="#333" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'image',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
        },
        {
          name: '上传方式',
          field: 'type',
          type: 'radio',
          data: [
            '文件管理器',
            '本地上传'
          ]
        },
      ],
      data: {
        required: 0,
        type: 0,
      }
    },
    images: {
      name: '多图片上传',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><rect x="4" y="6" width="40" height="30" rx="2" fill="none"/><rect x="4" y="6" width="40" height="30" rx="2" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 42H28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M34 42H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 42H6" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M42 42H44" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 42H14" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'images',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
        },
        {
          name: '上传方式',
          field: 'type',
          type: 'radio',
          data: [
            '文件管理器',
            '本地上传'
          ]
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path d="M11.6777 20.271C7.27476 21.3181 4 25.2766 4 30C4 35.5228 8.47715 40 14 40C14.9474 40 15.864 39.8683 16.7325 39.6221" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36.0547 20.271C40.4577 21.3181 43.7324 25.2766 43.7324 30C43.7324 35.5228 39.2553 40 33.7324 40V40C32.785 40 31.8684 39.8683 30.9999 39.6221" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M36 20C36 13.3726 30.6274 8 24 8C17.3726 8 12 13.3726 12 20" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.0654 27.881L23.9999 20.9236L31.1318 28" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 38V24.4618" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'file',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
        },
        {
          name: '上传方式',
          field: 'type',
          type: 'radio',
          data: [
            '文件管理器',
            '本地上传'
          ]
        },
      ],
      data: {
        type: 0,
        required: 0,
      }
    },
    date: {
      name: '日期时间',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><rect x="4" y="4" width="40" height="40" rx="2" fill="none" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 14H44" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="44" y1="11" x2="44" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 22H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 22H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 22H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 29H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 29H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 29H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 36H16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 36H26" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M32 36H36" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><line x1="4" y1="11" x2="4" y2="23" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'date',
      options: [
        {
          name: '验证',
          field: 'required',
          type: 'radio',
          data: [
            '选填',
            '必填'
          ]
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
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="6" width="36" height="36" rx="3" fill="none" stroke="#333" stroke-width="3"/><path d="M14 16L18 32L24 19L30 32L34 16" stroke="#333" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
      field: 'editor',
      options: [{
        name: '验证',
        field: 'required',
        type: 'radio',
        data: [
          '选填',
          '必填'
        ]
      },],
      data: {
        required: 0,
      }
    },
    color: {
      name: '颜色选择',
      icon: '<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" fill="white" fill-opacity="0.01"/><path fill-rule="evenodd" clip-rule="evenodd" d="M37 37C39.2091 37 41 35.2091 41 33C41 31.5272 39.6667 29.5272 37 27C34.3333 29.5272 33 31.5272 33 33C33 35.2091 34.7909 37 37 37Z" fill="#333"/><path d="M20.8535 5.50439L24.389 9.03993" stroke="#333" stroke-width="3" stroke-linecap="round"/><path d="M23.6818 8.33281L8.12549 23.8892L19.4392 35.2029L34.9955 19.6465L23.6818 8.33281Z" stroke="#333" stroke-width="3" stroke-linejoin="round"/><path d="M12 20.0732L28.961 25.6496" stroke="#333" stroke-width="3" stroke-linecap="round"/><path d="M4 43H44" stroke="#333" stroke-width="3" stroke-linecap="round"/></svg>',
      field: 'color',
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

  export default {
    data() {
      return {
        dialog: false,
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
        this.dialog = true
        this.formItemActive = index
        this.formItem = JSON.parse(JSON.stringify(this.formData[index]))
      },
      saveForm() {
        this.formData[this.formItemActive] = this.formItem
        this.dialog = false
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
      onSubmit() {
        let fields = [];
        for (let i in this.formData) {
          let item = this.formData[i]
          if (!item.field) {
            window.message.error('表单元素未设置字段名')
            return false
          }

          if (fields.length && fields.includes(item.field)) {
            window.message.error('表单元素字段名有重复')
            return false
          }
          fields.push(item.field)
        }
        this.saveing = true

        window.ajax({
          url: "{{route('admin.tools.form.setting.save', ['id' => $id], true)}}",
          method: 'post',
          successMsg: true,
          data: {
            data: this.formData
          }
        }).then((res) => {
          this.saveing = false
        }).catch((error) => {
          this.saveing = false
        })
      }
    }
  }
</script>
