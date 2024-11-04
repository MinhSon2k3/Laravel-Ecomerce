@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['create']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{route('generate.store')}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Tên mudule
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <div>
                                    <label for="" class="control-lable text-right">Loại module
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    </div>
                                    <div>
                                    <select name="module_type" id="" class="setupSelect2 col-lg-5">
                                        <option value="0">Chọn loại module</option>
                                        <option value="1">Module danh mục</option>
                                        <option value="2">Module chi tiết</option>
                                        <option value="3">Module khác</option>
                                   </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Schema 
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <textarea type="text" name="schema" value="{{old('schema',($generate->schema) ?? '')}}" class="form-control schema " placeholder="" autocomplete="off"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
        
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Thêm module</button>
        </div>
    </div>

</form>



