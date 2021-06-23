<div class="p-4">
@foreach($typeList as $vo)
    @if($vo['data'])
        <div class="mb-4">
            <div class="p-4">
                <span class="text-base">{{$vo['name']}} </span><small class="ml-2 text-gray-500">{{$vo['desc']}}</small>
            </div>
            <div class="px-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xxl:grid-cols-5 gap-4 ">
                    @foreach($vo['data'] as $data)
                        <div>
                            <a href="{{route($data['url'])}}" target="{{$data['target'] ?: '_self'}}" class="flex items-center space-x-2 shadow bg-gradient-to-r from-{{$vo['color']}}-900 to-{{$vo['color']}}-800 p-4 rounded text-white hover:ring hover:ring-{{$vo['color']}}-900 hover:ring-offset-2 ">
                                <div class="flex-none w-10 h-10">
                                    {!! $data['icon'] !!}
                                </div>
                                <div class="flex-grow">
                                    <h3 class="mb-0">{{$data['name']}}</h3>
                                    <small class="opacity-80">{{$data['desc']}}</small>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach

@if($formList)
<div class="mb-4">
    <div class="p-4">
        <span class="text-base">表单管理 </span><small class="ml-2 text-gray-500">独立表单管理功能</small>
    </div>
    <div class="px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xxl:grid-cols-5 gap-4 ">
            @foreach($formList as $vo)
                <div>
                    <a href="{{route('admin.tools.formData', ['form' => $vo->form_id])}}" class="flex items-center space-x-2 shadow bg-gradient-to-r from-purple-900 to-purple-800 p-4 rounded text-white hover:ring hover:ring-purple-900 hover:ring-offset-2 ">
                    <div class="flex-none w-10 h-10">
                            {!! file_get_contents(module_path('System/Static/Image/form.svg')) !!}
                        </div>
                        <div class="flex-grow">
                            <h3 class="mb-0">{{$vo['name']}}</h3>
                            <small class="opacity-80">{{$vo['description']}}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

</div>
