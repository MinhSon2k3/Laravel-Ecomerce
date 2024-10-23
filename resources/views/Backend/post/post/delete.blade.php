@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['delete']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('post.destroy', ['id' => $post->id]) }}" method="post" class="box">
    @csrf
   
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        <p> <strong>Bạn có chắc muốn xóa nhóm bài viết :</strong> {{$post->name}}</p>
                        <label for="" class="control-lable text-right">Lưu ý
                            <span class="text-danger">Không thể khôi phục khi xóa</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                   
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Nhóm bài viết
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name',($postCatalouge->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-danger" type="submit" name="send" value="">Xóa dữ liệu</button>
        </div>
    </div>
</form>




