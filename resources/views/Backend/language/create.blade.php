@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['create']['title']])
<form action="{{route('language.store')}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung
                    </div>
                    <div class="panel-decsription">
                        Nhập thông tin chung của ngôn ngữ
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Tên ngôn ngữ
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Canonical
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="canonical" value="{{old('canonical')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Ảnh đại diện
                                    </label>
                                    <input type="text" name="image" value="" class="form-control input-image" placeholder="" autocomplete="off" data-type="Images">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Ghi chú
                                    </label>
                                    <input type="text" name="description" value="{{old('description')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                          
  
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
        
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Thêm ngôn ngữ</button>
        </div>
    </div>

</form>



