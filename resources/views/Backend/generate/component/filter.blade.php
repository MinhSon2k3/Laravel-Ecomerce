<form action="{{route('generate.index')}}">
        <div class="filter-wrapper">
            <div class="perpage">     
                  
                    <div class="col-lg-2">
                    </div>
                    <div class="col-lg-6 w-100">
                            <div class="col-lg-6 w-100">
                                <input class="form-control "name="keyword" type="search" value="{{request('keyword')? : old('keyword')}}" placeholder="Nhập Từ Khóa" aria-label="Search">
                            </div>
                             <div class="col-lg-6">
                                <button class="btn btn-success"name="search" value="search" type="submit">Tìm Kiếm</button>
                            </div>                                                       
                    </div>                  
                    <div class="col-lg-4">
                        <a href="{{route('generate.create')}}" class="btn btn-danger"><i class="fa fa-plus"> Taọ module mới</i></a>
                    </div>
               
            </div>            
        </div>
</form>