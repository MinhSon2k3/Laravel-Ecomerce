<div>
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
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ ${module}->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{ str_repeat('|----', ((${module}->level > 0)?(${module}->level - 1):0)).${module}->name }} </div>  
                                        </td>
                                        <td class="text-center js-switch-{{${module}->id}}">
                                        <input type="checkbox" value="{{${module}->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="{module}" data-modelId="{{${module}->id}}" {{ (${module}->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('{view}.edit', ['id' => ${module}->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('{view}.delete', ['id' => ${module}->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  ${module}s->links('pagination::bootstrap-4') }}

                            
</div>      