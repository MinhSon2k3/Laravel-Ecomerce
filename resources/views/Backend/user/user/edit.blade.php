@include('backend.dashboard.component.breadcrumb',['title'=>$seo['meta_title']['edit']['title']])
<form action="{{ route('user.update', ['id' => $user->id]) }}" method="post" class="box">
    @csrf
    @method('PUT')
    <div class="wrapper wrapper-content aminated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung
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
                                    <input type="text" name="email" value="{{old('email',($user->email) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Họ tên
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{old('name',($user->name) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            @php
                                $userCatalogue = [
                                    'Chọn nhóm thành viên',
                                    'Quản trị viên',
                                    'Cộng tác viên'
                                ];
                            @endphp
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-left">Nhóm thành viên
                                        <span class="text-danger">(*)</span>
                                        <select name="user_catalouge_id" class="form-control w-100" id="">
                                            @foreach($userCatalogue as $key => $item)
                                                <option value="{{ $key }}" 
                                                    {{ (old('user_catalouge_id', $user->user_catalouge_id) == $key) ? 'selected' : '' }}>
                                                    {{ $item }}
                                                </option>
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
                                    <input type="date" name="birthday" value="{{old('birthday',($user->birthday) ? date('Y-m-d',strtotime($user->birthday)) : '')}}" class="form-control" placeholder="" autocomplete="off">
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
                                    <input type="text" name="address" value="{{old('address',($user->address) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Số điện thoại
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="phone" value="{{old('phone',($user->phone) ?? '')}}" class="form-control" placeholder="" autocomplete="off">
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
            <button class="btn btn-primary" type="submit" name="send" value="">Lưu thay đổi</button>
        </div>
    </div>

</form>
<script>
    var province_id='{{ (isset($user->province_id)) ? $user->province_id : old('province_id ')}}'
    var district_id='{{ (isset($user->district_id)) ? $user->district_id : old('district_id ')}}'
    var ward_id='{{ (isset($user->ward_id)) ? $user->ward_id : old('ward_id ')}}'
</script>



