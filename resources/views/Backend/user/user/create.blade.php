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
<form action="{{route('user.store')}}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung
                    </div>
                    <div class="panel-decsription">
                        Nhập thông tin chung của người dùng
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Email
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="email" value="{{old('email')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Họ tên
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            @php
                                $userCatalogue = [
                                    'Chọn nhóm thành viên',
                                    'Quản trị viên',
                                    'Người dùng'
                                ];
                            @endphp
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-left">Nhóm thành viên
                                        <span class="text-danger">(*)</span>
                                        <select name="user_catalouge_id" class="form-control w-100" id="">
                                            @foreach($userCatalogue as $key =>$item)
                                            <option value="{{$key}}">{{$item}}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Ngày sinh
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="date" name="birthday" value="{{old('birthday')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Mật khẩu
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="password" name="password" value="{{old('password')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Nhập lại mật khẩu
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="password" name="re_password" value="{{old('re_password')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Ảnh đại diện</label>
                                    <input type="text" name="image" value="" class="form-control input-image" placeholder="" autocomplete="off" data-upload="Images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin liên hệ
                    </div>
                    <div class="panel-decsription">
                        Nhập thông tin liên hệ
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin liên hệ</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-left col-lg-12">Thành phố
                                        <span class="text-danger">(*)</span>
                                        <select name="province_id" class="form-control setupSelect2 location" data-target="districts">
                                        <option value="">Chọn thành phố</option>
                                        @if(isset($provinces))
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->code }}" {{ old('province_id') == $province->code ? 'selected' : '' }}>
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-left">Quận
                                        <span class="text-danger">(*)</span>
                                        <select name="district_id" class="form-control  districts  location" data-target="wards">
                                            <option value="0">Chọn quận huyện</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-left">Phường/xã
                                        <span class="text-danger">(*)</span>
                                        <select name="ward_id" class="form-control wards w-100 location" data-target="end">
                                            <option value="0">Chọn Phường/xã</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Địa chỉ
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="address" value="{{old('address')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Số điện thoại
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Ghi chú
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="note" value="{{old('note')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button class="btn btn-primary" type="submit" name="send" value="">Lưu lại</button>
        </div>
    </div>

</form>
<script>
    var province_id='{{old('province_id')}}'
    var district_id='{{old('district_id')}}'
    var ward_id='{{old('ward_id')}}'
</script>


