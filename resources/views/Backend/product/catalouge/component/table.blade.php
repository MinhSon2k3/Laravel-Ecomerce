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
                                @if(isset($productCatalouges)&& is_object($productCatalouges))
                                    @foreach($productCatalouges as $productCatalouge)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $productCatalouge->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <div class="user-item"> {{ str_repeat('|----', (($productCatalouge->level > 0)?($productCatalouge->level - 1):0)).$productCatalouge->name }} </div>  
                                        </td>
                                        <td class="text-center js-switch-{{$productCatalouge->id}}">
                                        <input type="checkbox" value="{{$productCatalouge->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="productCatalouge" data-modelId="{{$productCatalouge->id}}" {{ ($productCatalouge->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('product.catalouge.edit', ['id' => $productCatalouge->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('product.catalouge.delete', ['id' => $productCatalouge->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $productCatalouges->links('pagination::bootstrap-4') }}

                            
</div>      