
@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['index']['title']])
<div class="row mt20">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
            <h5  class="fw-bold text-danger">{{$seo['meta_title']['index']['table'] }} </h5>
           
            </div>
            <div class="ibox-content">
                @include('backend.product.catalouge.component.filter')
                @include('backend.product.catalouge.component.table')
            </div>
        </div>
    </div>
</div>

