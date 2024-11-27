
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
                                @if(isset($attributes)&& is_object($attributes))
                                    @foreach($attributes as $attribute)
                                    <tr id="{{$attribute->id}}">
                                        <td class="col-lg-1">
                                        <input type="checkbox" value="{{ $attribute->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td class="col-lg-8">
                                            <div class="uk-flex uk-flex-middle">
                                                <div class="image mr5">
                                                    <div class="img-cover image-sizes">
                                                       <img src=" {{$attribute->image}}" alt="">
                                                    </div>
                                                   <div class="main-info">
                                                        <div class="name">
                                                            <span class="main-title">  {{$attribute->name}}</span>
                                                        </div>
                                                     
                                                        <div class="catalouge">
                                                            <span class="text-danger">{{__('messages.tableGroup')}}</span>
                                                            @foreach($attribute->attribute_catalouges as $val)
                                                            @foreach($val->attribute_catalouge_languages as $cat)
                                                              <a href="{{route('attribute.index',['attribute_catalouge_id'=>$val->id])}}"> {{$cat->name}}</a>
                                                            @endforeach
                                                            @endforeach
                                                        </div>
                                                      
                                                   </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center js-switch-{{$attribute->id}} col-lg-1">
                                        <input type="checkbox" value="{{$attribute->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="attribute" data-modelId="{{$attribute->id}}" {{ ($attribute->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center col-lg-2">
                                        <a class="btn btn-success"href="{{ route('attribute.edit', ['id' => $attribute->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('attribute.delete', ['id' => $attribute->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $attributes->links('pagination::bootstrap-4') }}
                   
