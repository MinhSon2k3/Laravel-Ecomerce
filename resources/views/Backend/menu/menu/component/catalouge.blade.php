<div class="wrapper wrapper-content aminated fadeInRight">
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title">
                    Vị trí menu
                </div>
                <div class="panel-decsription">
                    Website có vị trí hiển thị cho từng menu
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox-content">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12 mb10">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <div>
                                    <label for="">Chọn vị trí hiển thị</label>
                                </div>
                                <button data-toggle="modal" data-target="#createMenuCatalouge" type="button"
                                    class="createMenuCatalouge btn btn-danger" style="margin-left: auto;">
                                    Tạo vị trí hiển thị
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 ">
                            @if(count($menuCatalouges))
                            <select class="setupSelect2 form-control" name="menu_catalouge_id" id="">
                                <option value="">Chọn vị trí hiển thị</option>
                                @foreach($menuCatalouges as $key => $val)
                                <option value="{{$val->id}}">{{$val->name}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-lg-6 ">
                            <select class="setupSelect2 form-control" name="type" id="">
                                <option value="">Chọn kiểu menu</option>
                                @foreach(__('module.type') as $key => $val)
                                <option value="{{$key}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>