<div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <input type="checkbox" value=" " id="checkAll" class="">
                </th>

                <th>Tên</th>
                <th>Từ khóa</th>
                <th>Ngày tạo</th>
                <th>Người tạo</th>
                <th class="text-center">Tình trạng</th>
                <th class="text-center">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($menus)&& is_object($menus))
            @foreach($menus as $menu)
            <tr>
                <td>
                    <input type="checkbox" value="{{ $menu->id }}" id="" class="input-check checkBoxItem">
                </td>
                <td>
                    <div class="menu-item"> {{$menu->name}} </div>
                </td>
                <td>
                    <div class="menu-item"> {{$menu->email}}</div>
                </td>
                <td>
                    <div class="menu-item"> {{$menu->phone}} </div>
                </td>
                <td>
                    <div class="menu-item"> {{$menu->address}}</div>
                </td>
                <td class="text-center js-switch-{{$menu->id}}">
                    <input type="checkbox" value="{{$menu->publish}}" class="js-switch status" data-field="publish"
                        data-model="menu" data-modelId="{{$menu->id}}" {{ ($menu->publish==1) ? 'checked' : ''}} />
                </td>
                <td class="text-center">
                    <a class="btn btn-success" href="{{ route('menu.edit', ['id' => $menu->id]) }}"><i
                            class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('menu.delete', ['id' => $menu->id]) }}"><i
                            class="fa fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>





</div>