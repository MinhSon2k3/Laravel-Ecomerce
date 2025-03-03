<div class="row">
    <div class="col-lg-5">
        <div class="ibox-title">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                                class="">Liên kết tự tạo</a>
                        </h5>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse ">
                        <div class="panel-body">
                            <div class="panel-title">
                                Tạo menu
                            </div>
                            <div>
                                <p class="text-danger">Cài đặt menu mà bạn muốn hiển thị</p>
                                <a href="#" class="btn btn-default add-menu">Thêm đường dẫn</a>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach(__('module.model') as $key => $val)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#{{$key}}" data-model="{{$key}}"
                                class=" collapsed menu-module">{{$val}}</a>
                        </h4>
                    </div>
                    <div id="{{$key}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form action="" method="get" class="search-model" data-model="{{$key}}">
                                <div class="form-row">
                                    <input type="text" name="keyword" class="form-control search-menu"
                                        placeholder="Nhập từ tìm kiếm ">
                                </div>
                            </form>
                            <div class="menu-list mt10">

                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-4 text-center">
                        <label for="">Tên menu</label>
                    </div>
                    <div class="col-lg-4 text-center">
                        <label for="">Đường dẫn</label>
                    </div>
                    <div class="col-lg-2 text-center">
                        <label for="">Vị trí</label>
                    </div>
                    <div class="col-lg-2 text-center">
                        <label for="">Xóa</label>
                    </div>
                </div>
                <div class="hr-line-dashed" style="margin:10px 0;"></div>
                <div class="menu-wrapper">
                    @php
                    $oldMenu = old('menu',($menuList) ?? null);
                    @endphp
                    @if(!is_array($oldMenu) || empty($oldMenu))
                    <div class="notification text-center">
                        <h5>
                            Danh sách liên kết này chưa có bất kì đường dẫn nào
                        </h5>
                        <p>
                            Hãy nhấn vào <span style="color:blue;">Thêm đường dẫn</span> để bắt đầu thêm
                        </p>
                    </div>
                    @endif
                    @if(is_array($oldMenu) && !empty($oldMenu['name']))
                    @foreach($oldMenu['name'] as $key => $val)
                    <div class="row mb10 menu-item">
                        <div class="col-lg-4">
                            <input type="text" name="menu[name][]" value="{{ $val ?? '' }}" class="form-control">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" name="menu[canonical][]" value="{{ $oldMenu['canonical'][$key] ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" name="menu[order][]" value="{{ $oldMenu['order'][$key] ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-lg-2 text-center">
                            <a href="#" class="delete-menu"><i class="fa fa-trash-o"></i></a>
                            <input type="text" class="hidden" name="menu[id][]"
                                value="{{ $oldMenu['id'][$key] ?? '' }}">
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>