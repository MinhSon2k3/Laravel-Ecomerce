<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                  
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Địa chỉ</th>
                                    <th class="text-center">Vai trò</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($users)&& is_object($users))
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $user->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$user->name}} </div>  
                                        </td>
                                        <td>
                                            <div class="user-item">  {{$user->email}}</div>
                                        </td>
                                        <td>
                                            <div class="user-item">  {{$user->phone}} </div>
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$user->address}}</div>
                                        </td>
                                        <td>
                                            <div class="user-item text-center"> {{$user->user_catalouge->name}} </div>
                                        </td>
                                    
                                        <td class="text-center js-switch-{{$user->id}}">
                                        <input type="checkbox" value="{{$user->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="User" data-modelId="{{$user->id}}" {{ ($user->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('user.edit', ['id' => $user->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('user.delete', ['id' => $user->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
                            {{ $users->links('pagination::bootstrap-5') }}

                            

    
</div>      