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
                                @if(isset($posts)&& is_object($posts))
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $post->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{ str_repeat('|----', (($post->level > 0)?($post->level - 1):0)).$post->name }} </div>  
                                        </td>
                                        <td class="text-center js-switch-{{$post->id}}">
                                        <input type="checkbox" value="{{$post->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="Post" data-modelId="{{$post->id}}" {{ ($post->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('post..edit', ['id' => $post->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('post..delete', ['id' => $post->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $posts->links('pagination::bootstrap-4') }}

                            
</div>      