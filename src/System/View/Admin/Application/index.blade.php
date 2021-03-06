<template>

  <n-layout class="bg-gray-100 h-screen" :native-scrollbar="false">
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
                  <a href="{{$data['url']}}" target="{{$data['target'] ?: '_self'}}" class="flex items-center gap-4 shadow bg-white p-4 rounded  transition hover:shadow-md ">
                    <div class="flex-none w-10 h-10 rounded text-white p-2" style="background-color: {{$data['color']}};">
                      {!! $data['icon'] !!}
                    </div>
                    <div class="flex-grow">
                      <h3 class="text-gray-900">{{$data['name']}}</h3>
                      <small class="text-gray-500">{{$data['desc']}}</small>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    @endforeach
    @if(!$formList->empty())
      <div class="mb-4">
        <div class="p-4">
          <span class="text-base">表单管理 </span><small class="ml-2 text-gray-500">独立表单管理功能</small>
        </div>
        <div class="px-4">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xxl:grid-cols-5 gap-4 ">
            @foreach($formList as $vo)
              <div>
                <a href="{{route('admin.tools.formData', ['form' => $vo->form_id])}}" class="flex items-center gap-4 shadow bg-white p-4 rounded  transition hover:shadow-md">
                <div class="flex-none w-10 h-10 rounded text-white p-2 bg-blue-600">
                    {!! $vo['icon'] !!}
                  </div>
                  <div class="flex-grow">
                    <h3 class="text-gray-900">{{$vo['name']}}</h3>
                    <small class="text-gray-500">{{$vo['desc']}}</small>
                  </div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

  </div>
  </n-layout>

</template>
