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
                                @if(isset($attributeCatalouges)&& is_object($attributeCatalouges))
                                    @foreach($attributeCatalouges as $attributeCatalouge)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $attributeCatalouge->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{ str_repeat('|----', (($attributeCatalouge->level > 0)?($attributeCatalouge->level - 1):0)).$attributeCatalouge->name }} </div>  
                                        </td>
                                        <td class="text-center js-switch-{{$attributeCatalouge->id}}">
                                        <input type="checkbox" value="{{$attributeCatalouge->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="attributeCatalouge" data-modelId="{{$attributeCatalouge->id}}" {{ ($attributeCatalouge->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('attribute.catalouge.edit', ['id' => $attributeCatalouge->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('attribute.catalouge.delete', ['id' => $attributeCatalouge->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $attributeCatalouges->links('pagination::bootstrap-4') }}

                            
</div>      