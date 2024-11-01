<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                    <th>Quyền</th>
                                    <th>Canonical</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($permissions)&& is_object($permissions))
                                    @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $permission->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$permission->name}} </div>  
                                        </td>
                                        <td>
                                        <div class="user-item"> {{$permission->canonical}} </div>  
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('permission.edit', ['id' => $permission->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('permission.delete', ['id' => $permission->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $permissions->links('pagination::bootstrap-4') }}

                            
</div>      