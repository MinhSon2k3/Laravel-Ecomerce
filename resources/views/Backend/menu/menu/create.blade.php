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
<form action="{{route('menu.store')}}" method="post" class="box">
    @csrf
    @include('backend.menu.menu.component.catalouge')

    @include('backend.menu.menu.component.list')
    <div class="text-right">
        <button class="btn btn-primary" type="submit" name="send" value="">Lưu lại</button>
    </div>
</form>
@include('backend.menu.menu.component.popup')