@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['edit']['title']])

<form action="{{ route('permission.update', ['id' => $permission->id]) }}" method="POST" class="box">
    @csrf
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
                                    <label for="" class="control-lable text-right">Tên ngôn ngữ
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name',($permission->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Canonical
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="canonical" value="{{old('canonical',($permission->canonical) ?? '')}}" class="form-control" placeholder="" autocomplete="off" required>
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



