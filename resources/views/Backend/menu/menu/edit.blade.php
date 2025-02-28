@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['edit']['title']])
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-title">Danh sách menu</div>
            <div class="panel-description">
                <p>+ Danh sách Menu giúp bạn dễ dàng kiểm soát bố cục menu. Bạn có thể thêm mới hoặc cập nhật menu bằng
                    nút <span class="text-success">Cập nhật Menu</span></p>
                <p>+ Bạn có thể thay đổi vị trí hiển thị của menu bằng cách <span class="text-success"></span> menu đến
                    vị trí mong muốn</p>
                <p>+ Dễ dàng khởi tạo menu con bằng cách ấn vào nút <span class="text-success">Quản lý menu con</span>
                </p>
                <p>
                    <span class="text-danger">Hỗ trợ đến danh mục con cấp 5</span>
                </p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between col-lg-6">
                        <h5>Menu Chính</h5>
                    </div>
                    <div>
                        <a href="" class="custom-button col-lg-6">Cập nhật Menu</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="dd" id="nestable2">
                        @if(count($menus))
                        <ol class="dd-list">
                            @foreach($menus as $key => $val)
                            <li class="dd-item" data-id="{{$val->id}}">
                                <div class="dd-handle">
                                    <span class="label label-info"><i class="fa fa-users"></i></span> Cras ornare
                                    tristique.
                                </div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="2">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 12:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-cog"></i></span> Vivamus
                                            vestibulum nulla nec ante.
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="3">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-bolt"></i></span> Nunc
                                            dignissim risus id metus.
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 pm </span>
                                            <span class="label label-info"><i class="fa fa-laptop"></i></span>
                                            Vestibulum commodo
                                        </div>
                                    </li>
                                </ol>
                            </li>
                            @endforeach
                        </ol>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>