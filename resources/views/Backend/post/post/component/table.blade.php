
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                    <th>{{__('messages.title')}}</th>
                                    <th class="text-center">{{__('messages.tableStatus')}}</th>
                                    <th class="text-center">{{__('messages.tableAction')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($posts)&& is_object($posts))
                                    @foreach($posts as $post)
                                    <tr id="{{$post->id}}">
                                        <td class="col-lg-1">
                                        <input type="checkbox" value="{{ $post->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td class="col-lg-8">
                                            <div class="uk-flex uk-flex-middle">
                                                <div class="image mr5">
                                                    <div class="img-cover image-post">
                                                       <img src=" {{$post->image}}" alt="">
                                                    </div>
                                                   <div class="main-info">
                                                        <div class="name">
                                                            <span class="main-title">  {{$post->name}}</span>
                                                        </div>
                                                     
                                                        <div class="catalouge">
                                                            <span class="text-danger">{{__('messages.tableGroup')}}</span>
                                                            @foreach($post->post_catalouges as $val)
                                                            @foreach($val->post_catalouge_languages as $cat)
                                                              <a href="{{route('post.index',['post_catalouge_id'=>$val->id])}}"> {{$cat->name}}</a>
                                                            @endforeach
                                                            @endforeach
                                                        </div>
                                                      
                                                   </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center js-switch-{{$post->id}} col-lg-1">
                                        <input type="checkbox" value="{{$post->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="Post" data-modelId="{{$post->id}}" {{ ($post->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center col-lg-2">
                                        <a class="btn btn-success"href="{{ route('post.edit', ['id' => $post->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('post.delete', ['id' => $post->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $posts->links('pagination::bootstrap-4') }}
                   
