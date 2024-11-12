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
<form action="{{route('{module}.store')}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
              <div class="ibox">
                <div class="ibox-title">
                    <h5>{{__('messages.tableHeading')}}</h5>
                </div>
                <div class="ibox-content">
                 @include('backend.{view}.component.general')
                </div>
              </div>
              @include('backend.{view}.component.seo')
            </div>
            <div class="col-lg-3">
            @include('backend.{view}.component.aside')
            </div>
        </div>
        
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">{{__('messages.add')}}</button>
        </div>
    </div>

</form>



