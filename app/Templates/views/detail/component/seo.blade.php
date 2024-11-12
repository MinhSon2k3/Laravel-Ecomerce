<div class="ibox">
                <div class="ibox-title">
                    <h5>{{__('messages.seo')}}</h5>
                </div>
                <div class="ibox-content">
                    <div class="seo-container">
                        <div class="h3 meta-title">
                                {{old('meta_title',(${module}->meta_title) ?? __('messages.seoTitle'))}}
                        </div>
                        <div class="canonical">
                              {{old('canonical',(${module}->canonical) ?? __('messages.seoCanonical'))}}
                        </div>
                        <div class="meta-description">
                                 {{old('meta_description',(${module}->meta_description) ?? __('messages.seoDescription'))}}
                        </div>
                    </div>
                    <div class="seo-wrapper">
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right" style="display: flex; justify-content: space-between !important; width: 100% !important;">
                                <span style="margin-right: auto !important;">{{__('messages.seoMetaDescription')}}</span>
                                <span class="count_meta-title" style="margin-left: auto !important;">{{__('messages.character')}}</span>
                            </label>
                                    <input type="text" name="meta_title" value="{{old('meta_title',(${module}->meta_title) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right">
                                <span style="margin-right: auto !important;">{{__('messages.seoMetaKeyword')}}</span>
                              
                            </label>
                                    <input type="text" name="meta_keyword" value="{{old('meta_keyword',(${module}->meta_keyword) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right" style="display: flex; justify-content: space-between !important; width: 100% !important;">
                                <span style="margin-right: auto !important;">{{__('messages.seoMetaDescription')}}</span>
                                <span class="count_meta-description" style="margin-left: auto !important;">{{__('messages.character')}}</span>
                            </label>
                                  <textarea type="text" name="meta_description" value="{{old('meta_description',(${module}->meta_description) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                    {{old('meta_description',(${module}->meta_description) ?? '')}}
                                  </textarea>
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right">
                                <span style="margin-right: auto !important;">{{__('messages.canonical')}}</span>
                                <span class="text-danger">(*)</span>
                            </label>
                             <div class="input-wrapper">
                             <input type="text" name="canonical" value="{{old('canonical',(${module}->canonical) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                             <span class="baseUrl">{{env('APP_URL')}}</span>
                             </div>    
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>