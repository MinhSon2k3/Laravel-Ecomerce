<div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('messages.parent')}}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                        <span class="text-danger notice">{{__('messages.parentNotice')}}</span>
                                    <select name="post_catalouge_id" class="form-control setupSelect2">
                                        @foreach($dropdown as $key => $val)
                                            <option {{ 
                                                $key == old('post_catalouge_id', (isset($post->post_catalouge_id)) ? $post->post_catalouge_id : '') ? 'selected' : '' 
                                                }} value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>

                        @php
                            $catalouge = [];
                            if (isset($post)) {
                                foreach ($post->post_catalouges as $key => $val) {
                                    $catalouge[] = $val->id;
                                }
                            } else {
                            }
                        @endphp

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label class="control-label">{{__('messages.subparent')}}</label>
                                    <select multiple name="catalouge[]" class="form-control setupSelect2">
                                    @foreach($dropdown as $key => $val)
                                        <option
                                            @if(is_array(old('catalouge', (isset($catalouge)) && count($catalouge)? $catalouge : [])) 
                                            && in_array($key, old('catalouge', (isset($catalouge)) ? $catalouge : [])))
                                                selected
                                            @endif
                                            value="{{ $key }}">{{ $val }}</option>
                                    @endforeach

                                    </select>
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
                                    <img src="{{ old('image', ($post->image ?? 'userfiles/image/language/images.png')) }}" alt="">
                                </span>
                                   <input type="text" name="image" value="{{old('image',($post->image) ?? '')}}">
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
                                        @foreach(__('messages.publish') as $key => $val)
                                        <option  {{ 
                                            $key == old('publish', (isset($post->publish)) ? $post->publish : '') ? 'selected' : '' 
                                            }}  value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <br>  <br>
                                   </div>
                                    <div>
                                    <select name="follow" class="form-control setupSelect2">
                                        @foreach(__('messages.follow')  as $key => $val)
                                        <option  {{ 
                                            $key == old('follow', (isset($post->follow)) ? $post->follow : '') ? 'selected' : '' 
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
</div>