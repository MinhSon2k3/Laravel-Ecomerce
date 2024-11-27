
<div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('messages.parent')}}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">{{__('messages.parentNotice')}}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select name="parent_id" class="form-control setupSelect2">
                                        @foreach($dropdown as $key => $val)
                                            <option {{ 
                                                $key == old('parent_id', (isset($attributeCatalouge->parent_id)) ? $attributeCatalouge->parent_id : '') ? 'selected' : '' 
                                                }} value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('messages.image')}}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                <span class="image img-cover image-target">
                                    <img src="{{ old('image', ($attributeCatalouge->image ?? 'userfiles/image/language/images.png')) }}" alt="">
                                </span>
                                   <input type="text" name="image" value="{{old('image',($attributeCatalouge->image) ?? '')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('messages.advange')}}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                   <div class="mb-15">
                                   <select name="publish" class="form-control setupSelect2">
                                        @foreach(__('messages.publish')  as $key => $val)
                                        <option  {{ 
                                            $key == old('publish', (isset($attributeCatalouge->publish)) ? $attributeCatalouge->publish : '') ? 'selected' : '' 
                                            }}  value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <br>  <br>
                                   </div>
                                    <div>
                                    <select name="follow" class="form-control setupSelect2">
                                        @foreach(__('messages.follow') as $key => $val)
                                        <option  {{ 
                                            $key == old('follow', (isset($attributeCatalouge->follow)) ? $attributeCatalouge->follow : '') ? 'selected' : '' 
                                            }}  value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</div>