<form action="{{route('user.catalouge.index')}}">
        <div class="filter-wrapper">
            <div class="perpage">     
                  
                    <div class="col-lg-2">
                    @php
                       $publish=request('publish') ? : old('publish');
                    @endphp    
                            <select name="publish" class="form-control" id="">
                                   
                                    @foreach(config('apps.general.publish') as $key =>$item)
                                    <option {{($publish==$key) ? 'selected':''}} value="{{$key}}">{{$item}}</option>
                                    @endforeach
                            </select>
                    </div>
                    <div class="col-lg-6">
                       
                                <div class="col-lg-9 w-100">
                                        <input class="form-control "name="keyword" type="search" value="{{request('keyword')? : old('keyword')}}" placeholder="Nhập Từ Khóa" aria-label="Search">
                                </div>
                                <div class="col-lg-3">
                                        <button class="btn btn-success"name="search" value="search" type="submit">Tìm Kiếm</button>
                                </div>                                                       
                    </div>
                                 
                    <div class="col-lg-4">
                        <a href="{{route('user.catalouge.permission')}}" class="btn btn-info"><i class="fa fa-key">Phân quyền</i></a>
                        <a href="{{route('user.catalouge.create')}}" class="btn btn-danger"><i class="fa fa-plus"> Thêm mới nhóm thành viên</i></a>
                    </div>
               
            </div>            
        </div>
</form>