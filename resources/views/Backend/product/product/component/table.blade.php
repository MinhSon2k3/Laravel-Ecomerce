
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
                                @if(isset($products)&& is_object($products))
                                    @foreach($products as $product)
                                    <tr id="{{$product->id}}">
                                        <td class="col-lg-1">
                                        <input type="checkbox" value="{{ $product->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td class="col-lg-8">
                                            <div class="uk-flex uk-flex-middle">
                                                <div class="image mr5">
                                                    <div class="img-cover image-size-product">
                                                       <img src=" {{$product->image}}" alt="">
                                                    </div>
                                                   <div class="main-info">
                                                        <div class="name">
                                                            <span class="main-title">  {{$product->name}}</span>
                                                        </div>
                                                     
                                                        <div class="catalouge">
                                                            <span class="text-danger">{{__('messages.tableGroup')}}</span>
                                                            @foreach($product->product_catalouges as $val)
                                                            @foreach($val->product_catalouge_languages as $cat)
                                                              <a href="{{route('product.index',['product_catalouge_id'=>$val->id])}}"> {{$cat->name}}</a>
                                                            @endforeach
                                                            @endforeach
                                                        </div>
                                                      
                                                   </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center js-switch-{{$product->id}} col-lg-1">
                                        <input type="checkbox" value="{{$product->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="product" data-modelId="{{$product->id}}" {{ ($product->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center col-lg-2">
                                        <a class="btn btn-success"href="{{ route('product.edit', ['id' => $product->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('product.delete', ['id' => $product->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $products->links('pagination::bootstrap-4') }}
                   
