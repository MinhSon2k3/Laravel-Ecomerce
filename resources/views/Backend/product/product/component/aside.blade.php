<div class="ibox">
                    <div class="ibox-title">
                        <h5>{{__('messages.parent')}}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                        <span class="text-danger notice">{{__('messages.parentNotice')}}</span>
                                    <select name="product_catalouge_id" class="form-control setupSelect2">
                                        @foreach($dropdown as $key => $val)
                                            <option {{ 
                                                $key == old('product_catalouge_id', (isset($product->product_catalouge_id)) ? $product->product_catalouge_id : '') ? 'selected' : '' 
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
                            if (isset($product)) {
                                foreach ($product->product_catalouges as $key => $val) {
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
                <div class="ibox w">
                    <div class="ibox-title">
                        <h5>{{ __('messages.product.information') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="">{{ __('messages.product.code') }}</label>
                                    <input 
                                        type="text"
                                        name="code"
                                        value="{{ old('code', ($product->code)  ?? '') }}"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="">{{ __('messages.product.made_in') }}</label>
                                    <input 
                                        type="text"
                                        name="made_in"
                                        value="{{ old('made_in', ($product->made_in) ?? null) }}"
                                        class="form-control "
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="">{{ __('messages.product.price') }}</label>
                                    <input 
                                        type="text"
                                        name="price"
                                        value="{{ old('price', (isset($product)) ? number_format($product->price, 0 , ',', '.') : '') }}"
                                        class="form-control int"
                                    >
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
                                    <img src="{{ old('image', ($product->image ?? 'userfiles/image/language/images.png')) }}" alt="">
                                </span>
                                   <input type="text" name="image" value="{{old('image',($product->image) ?? '')}}">
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
                                            $key == old('publish', (isset($product->publish)) ? $product->publish : '') ? 'selected' : '' 
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
                                            $key == old('follow', (isset($product->follow)) ? $product->follow : '') ? 'selected' : '' 
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