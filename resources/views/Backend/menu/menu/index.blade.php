@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['index']['title']])
<div class="row mt-5">
    @csrf
    <div class="col-lg-12 ">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class="fw-bold text-danger">{{$seo['meta_title']['index']['table'] }} </h5>
                @include('backend.menu.menu.component.toolbox')
            </div>
            <div class="ibox-content">
                @include('backend.menu.menu.component.filter')
                @include('backend.menu.menu.component.table')

            </div>
        </div>
    </div>
</div>