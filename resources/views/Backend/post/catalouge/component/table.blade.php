<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                    <th>Tên nhóm</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($postCatalouges)&& is_object($postCatalouges))
                                    @foreach($postCatalouges as $postCatalouge)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $postCatalouge->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{ str_repeat('|----', (($postCatalouge->level > 0)?($postCatalouge->level - 1):0)).$postCatalouge->name }} </div>  
                                        </td>
                                        <td class="text-center js-switch-{{$postCatalouge->id}}">
                                        <input type="checkbox" value="{{$postCatalouge->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="Language" data-modelId="{{$postCatalouge->id}}" {{ ($postCatalouge->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('post.catalouge.edit', ['id' => $postCatalouge->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('post.catalouge.delete', ['id' => $postCatalouge->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $postCatalouges->links('pagination::bootstrap-4') }}

                            
</div>      