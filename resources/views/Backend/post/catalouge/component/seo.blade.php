<div class="ibox">
                <div class="ibox-title">
                    <h5>Cấu hình SEO</h5>
                </div>
                <div class="ibox-content">
                    <div class="seo-container">
                        <div class="h3 meta-title">
                                Bạn chưa có tiêu đề
                        </div>
                        <div class="canonical">
                              Bạn chưa có đường dẫn
                        </div>
                        <div class="meta-description">
                                 Bạn chưa có mô tả 
                        </div>
                    </div>
                    <div class="seo-wrapper">
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right" style="display: flex; justify-content: space-between !important; width: 100% !important;">
                                <span style="margin-right: auto !important;">Mô tả SEO</span>
                                <span class="count_meta-title" style="margin-left: auto !important;">0 kí tự</span>
                            </label>
                                    <input type="text" name="meta_title" value="{{old('meta_title',($postCatalouge->meta_title) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right">
                                <span style="margin-right: auto !important;">Từ khóa SEO</span>
                              
                            </label>
                                    <input type="text" name="meta_keyword" value="{{old('meta_keyword',($postCatalouge->meta_keyword) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right" style="display: flex; justify-content: space-between !important; width: 100% !important;">
                                <span style="margin-right: auto !important;">Mô tả SEO</span>
                                <span class="count_meta-description" style="margin-left: auto !important;">0 kí tự</span>
                            </label>
                                  <textarea type="text" name="meta_description" value="{{old('meta_description',($postCatalouge->meta_description) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                    {{old('meta_description',($postCatalouge->meta_description) ?? '')}}
                                  </textarea>
                            </div>
                            </div>
                        </div>
                        <div class="row mb-15">
                            <div class="col-lg-12">
                            <div class="form-row">
                            <label for="" class="control-label text-right">
                                <span style="margin-right: auto !important;">Đường dẫn</span>
                                <span class="text-danger">(*)</span>
                            </label>
                             <div class="input-wrapper">
                             <input type="text" name="canonical" value="{{old('canonical',($postCatalouge->canonical) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                             <span class="baseUrl">{{env('APP_URL')}}</span>
                             </div>    
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
              </div>