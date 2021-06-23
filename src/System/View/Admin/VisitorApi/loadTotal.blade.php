<table class="table-box">
    <thead>
    <tr>
        <th class="">接口</th>
        <th class="">访问量</th>
        <th class="">访客</th>
        <th class=" hidden lg:block">占比</th>
    </tr>
    </thead>
    <tbody class="">
    @foreach($apiList as $vo)
        <tr>
            <td class=" whitespace-nowrap ">
                {{$vo->desc}}
                <div class="text-gray-500">{{$vo->name}}</div>
            </td>
            <td class=" whitespace-nowrap ">
                {{$vo->value}}
            </td>
            <td class=" whitespace-nowrap ">
                {{$vo->uv}}
            </td>
            <td class=" whitespace-nowrap  text-right hidden lg:block">
                <div class="flex items-center">
                    <div class="flex-grow rounded-full h-3 box-border border border-gray-300 relative ">
                        <div class="bg-blue-900  h-3 rounded-l-full absolute  -top-px -left-px" style="width: {{$vo->rate}}% "></div>
                    </div>
                    <span class="flex-none w-8 ml-4 text-gray-500">{{$vo->rate}}%</span>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
