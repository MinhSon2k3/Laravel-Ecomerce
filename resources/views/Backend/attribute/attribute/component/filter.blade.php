<form action="{{route('attribute.index')}}">
        <div class="filter-wrapper">
            <div class="perpage">     
                  
                    <div class="col-lg-2">
                    @php
                       $publish=request('publish') ? : old('publish');
                    @endphp    
                            <select name="publish" class="form-control" id="">
                                   
                                    @foreach(__('messages.publish') as $key =>$item)
                                    <option {{($publish==$key) ? 'selected':''}} value="{{$key}}">{{$item}}</option>
                                    @endforeach
                            </select>
                    </div>
                    <div class="col-lg-6 w-100">
                            <div class="col-lg-6 w-100">
                                <input class="form-control "name="keyword" type="search" value="{{request('keyword')? : old('keyword')}}" placeholder="{{__('messages.searchInput')}}" aria-label="Search">
                            </div>
                             <div class="col-lg-6">
                                <button class="btn btn-success"name="search" value="search" type="submit">{{__('messages.search')}}</button>
                            </div>                                                       
                    </div>                  
                    <div class="col-lg-4">
                        <a href="{{route('attribute.create')}}" class="btn btn-danger"><i class="fa fa-plus">{{__('messages.add')}}</i></a>
                    </div>
               
            </div>            
        </div>
</form>