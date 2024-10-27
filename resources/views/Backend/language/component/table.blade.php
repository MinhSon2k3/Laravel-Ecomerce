<div>
    <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" value=" " id="checkAll" class="">
                                    </th>
                                    <th>Ảnh đại diện</th>
                                    <th>Tên Ngôn ngữ</th>
                                    <th>Canonical</th>
                                    <th>Mô tả</th>
                                    <th class="text-center">Tình trạng</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($languagess)&& is_object($languagess))
                                    @foreach($languagess as $language)
                                    <tr>
                                        <td>
                                        <input type="checkbox" value="{{ $language->id }}" id="" class="input-check checkBoxItem">
                                        </td>
                                        <td>
                                            <span class=" img-cover"> <img class="image1" src="{{ asset($language->image) }}" alt="{{ $language->name }}"> </span>
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$language->name}} </div>  
                                        </td>
                                        <td>
                                        <div class="user-item"> {{$language->canonical}} </div>  
                                        </td>
                                        <td>
                                            <div class="user-item"> {{$language->description}} </div>  
                                        </td>
                                      
                                        <td class="text-center js-switch-{{$language->id}}">
                                        <input type="checkbox" value="{{$language->publish}}" class="js-switch status" data-field="publish" 
                                               data-model="Language" data-modelId="{{$language->id}}" {{ ($language->publish==1) ? 'checked' : ''}} />
                                        </td>
                                        <td class="text-center">
                                        <a class="btn btn-success"href="{{ route('language.edit', ['id' => $language->id]) }}"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger"href="{{ route('language.delete', ['id' => $language->id]) }}"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                </tbody>
    </table>
    {{  $languagess->links('pagination::bootstrap-4') }}

                            
</div>      