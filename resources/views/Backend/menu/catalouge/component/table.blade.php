<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                
                                    <th>Tên nhóm</th>
                                    <th>Số thành viên</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($userCatalouges)&& is_object($userCatalouges))
                                    @foreach($userCatalouges as $userCatalouge)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $userCatalouge->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$userCatalouge->name}} </div>  
                                        </td>
                                        <td>
                                        <div class="user-item"> {{$userCatalouge->users_count}} </div>  
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$userCatalouge->description}} </div>  
                                        </td>
                                      
                                        <td class="text-center js-switch-{{$userCatalouge->id}}">
                                        <input type="checkbox" value="{{$userCatalouge->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="UserCatalouge" data-modelId="{{$userCatalouge->id}}" {{ ($userCatalouge->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('user.catalouge.edit', ['id' => $userCatalouge->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('user.catalouge.delete', ['id' => $userCatalouge->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
                            {{ $userCatalouges->links('pagination::bootstrap-5') }}

                            
</div>      