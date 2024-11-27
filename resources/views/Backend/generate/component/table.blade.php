<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                    <th>Module đã tạo </th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($generates)&& is_object($generates))
                                    @foreach($generates as $generate)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $generate->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$generate->name}} </div>  
                                        </td>    
                                        <td class="text-center">
                                        <a class="btn btn-danger"href="{{ route('generate.delete', ['id' => $generate->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $generates->links('pagination::bootstrap-4') }}

                            
</div>      