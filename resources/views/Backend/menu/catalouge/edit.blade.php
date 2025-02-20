@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['edit']['title']])

<form action="{{ route('user.catalouge.update', ['id' => $userCatalouges->id]) }}" method="post" class="box">
    @csrf
    @method('PUT')
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung
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
                                    <label for="" class="control-lable text-right">Tên nhóm
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name',($userCatalouges->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Mô tả
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="description" value="{{old('description',($userCatalouges->description) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                        
                         
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Lưu thay đổi</button>
        </div>
    </div>

</form>



