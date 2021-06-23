<div class="tabs" id="url-tabs">
    <ul class="tabs-nav" x-data="{tab: 0}">
        @foreach($data as $key => $vo)
            <li>
                <a class="tabs-item tabs-active" :class="{'tabs-active': tab == {{$key}}}" href="javascript:;"
                   @click="tab = {{$key}}; urlTab({{$key}})">{{$vo['name']}}</a>
            </li>
        @endforeach
        <li class="flex-grow"></li>
        <li class="pr-4 h-12 flex items-center">
            <div class="cursor-pointer btn-close h-6 w-6 text-gray-600 hover:text-red-900" modal-close>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </li>
    </ul>
    <div class="p-4">
        <div class="flex space-x-2 mb-4">
            <div><input type="text" data-keyword class="form-input" ></div>
            <div><button class="btn-blue" type="button" data-search>搜索</button></div>
        </div>
        <div id="quick-search">
        </div>
    </div>
</div>

<script>
    Do('base', function () {
        let $table = table.ajax({
            url: "{{route('admin.tools.url.data')}}",
            type: 'list',
            params: {
                key: 0
            },
            query: function () {
                return $('#url-tabs').find('[data-keyword]').val()
            },
            callback: function (object) {
                $('#url-tabs').parents('[modal]').trigger('close')
                window['selectUrl']($(object).data('url'))
            }
        })
        $('#quick-search').html($table)
        $table.trigger('refresh')
        $('#url-tabs').on('click', '[data-search]', function () {
            $table.trigger('refresh')
        })
        window.urlTab = function (num) {
            $table.trigger('config', {
                params: {
                    key: num
                }
            })
            $table.trigger('refresh')
        }
    })
</script>
