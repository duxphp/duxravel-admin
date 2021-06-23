<table class="table-box ">
    <thead>
    <tr>
        <th class="">接口</th>
        <th class="">速度</th>
        <th class="hidden lg:block">占比</th>
    </tr>
    </thead>
    <tbody>
    @foreach($apiList as $vo)
        <tr>
            <td class=" whitespace-nowrap ">
                {{$vo->desc}}
                <div class="text-gray-500">{{$vo->name}}</div>
            </td>
            <td class=" whitespace-nowrap ">
                {{$vo->value}}s
            </td>
            <td class=" whitespace-nowrap  hidden lg:block text-right">
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
