@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['edit']['title']])
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{route('post.update', ['id' => $post->id])}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
              <div class="ibox">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                 @include('backend.post.post.component.general')
                </div>
              </div>
              @include('backend.post.post.component.seo')
            </div>
            <div class="col-lg-3">
            @include('backend.post.post.component.aside')
            </div>
        </div>
        
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Chỉnh sửa bài viết</button>
        </div>
    </div>

</form>



