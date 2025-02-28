<form action="{{route('menu.index')}}">
    <div class="filter-wrapper">
        <div class="perpage">
            <div class="col-lg-3">
                @php
                $menuCatalogue = [
                'Chọn nhóm thành viên',
                'Quản trị viên',
                'Cộng tác viên'
                ];
                @endphp
                <select name="menu_catalouge_id" class="form-control" id="">
                    @foreach($menuCatalogue as $key =>$item)
                    <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-lg-3">
                @php
                $publish=request('publish') ? : old('publish');
                @endphp
                <select name="publish" class="form-control" id="">

                    @foreach(config('apps.general.publish') as $key =>$item)
                    <option {{($publish==$key) ? 'selected':''}} value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-lg-4 w-100">
                <div class="col-lg-6 w-100">
                    <input class="form-control " name="keyword" type="search"
                        value="{{request('keyword')? : old('keyword')}}" placeholder="Nhập Từ Khóa" aria-label="Search">
                </div>
                <div class="col-lg-6">
                    <button class="btn btn-success" name="search" value="search" type="submit">Tìm Kiếm</button>
                </div>
            </div>
            <div class="col-lg-2">
                <a href="{{route('menu.create')}}" class="btn btn-danger"><i class="fa fa-plus"> Thêm menu</i></a>
            </div>

        </div>
    </div>
</form>