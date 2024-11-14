                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">{{__('messages.title')}}
                                    </label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name" value="{{old('name',($productCatalouge->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">{{__('messages.description')}}

                                    </label>
                                    <textarea type="text" name="description" value="{{old('description',($productCatalouge->description) ?? '')}}" class="form-control ckeditor " placeholder="" autocomplete="off">
                                    {{old('description',($productCatalouge->description) ?? '')}}
                                    </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb15">
                        <div class="col-lg-12">
                            <div class="form-row">
                                    <label for="" class="control-lable text-right">{{__('messages.content')}}
                                    </label>
                                    <textarea type="text" name="content" value="{{old('content')}}" class="form-control ckeditor" placeholder="" autocomplete="off">
                                    {{old('content',($productCatalouge->content) ?? '')}}
                                    </textarea>
                            </div>
                        </div>
                    </div>