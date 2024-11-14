
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
                                @if(isset(${module}s)&& is_object(${module}s))
                                    @foreach(${module}s as ${module})
                                    <tr id="{{${module}->id}}">
                                        <td class="col-lg-1">
                                        <input type="checkbox" value="{{ ${module}->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td class="col-lg-8">
                                            <div class="uk-flex uk-flex-middle">
                                                <div class="image mr5">
                                                    <div class="img-cover image-sizes">
                                                       <img src=" {{${module}->image}}" alt="">
                                                    </div>
                                                   <div class="main-info">
                                                        <div class="name">
                                                            <span class="main-title">  {{${module}->name}}</span>
                                                        </div>
                                                     
                                                        <div class="catalouge">
                                                            <span class="text-danger">{{__('messages.tableGroup')}}</span>
                                                            @foreach(${module}->{module}_catalouges as $val)
                                                            @foreach($val->{module}_catalouge_languages as $cat)
                                                              <a href="{{route('{module}.index',['{module}_catalouge_id'=>$val->id])}}"> {{$cat->name}}</a>
                                                            @endforeach
                                                            @endforeach
                                                        </div>
                                                      
                                                   </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center js-switch-{{${module}->id}} col-lg-1">
                                        <input type="checkbox" value="{{${module}->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="{module}" data-modelId="{{${module}->id}}" {{ (${module}->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center col-lg-2">
                                        <a class="btn btn-success"href="{{ route('{module}.edit', ['id' => ${module}->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('{module}.delete', ['id' => ${module}->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  ${module}s->links('pagination::bootstrap-4') }}
                   
