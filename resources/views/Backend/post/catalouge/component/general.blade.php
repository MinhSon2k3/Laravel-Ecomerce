                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">Tiêu đề nhóm bài viết
                                    </label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name" value="{{old('name',($postCatalouge->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">Mô tả ngắn

                                    </label>
                                    <textarea type="text" name="description" value="{{old('description',($postCatalouge->description) ?? '')}}" class="form-control ckeditor " placeholder="" autocomplete="off">
                                    {{old('description',($postCatalouge->description) ?? '')}}
                                    </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">Nội dung

                                    </label>
                                    <textarea type="text" name="content" value="{{old('content')}}" class="form-control ckeditor" placeholder="" autocomplete="off">
                                    {{old('content',($postCatalouge->content) ?? '')}}
                                    </textarea>
                            </div>
                        </div>
                    </div>