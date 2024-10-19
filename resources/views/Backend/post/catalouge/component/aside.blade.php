<div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-lable text-right">Chọn danh mục cha
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select name="parent_id" class="form-control setupSelect2">
                                 @php
    $currentId = $postCatalouge->id ?? null;
    $currentParentId = $postCatalouge->parent_id ?? null;
@endphp

@foreach($dropdown as $key => $val)
    @php
        $isSelected = false;
        if ($currentId === null) {
            // Trường hợp tạo mới
            $isSelected = ($key == 0); // Chọn Root (giả sử key 0 là Root)
        } else {
            // Trường hợp chỉnh sửa
            $current = $currentId;
            while($current != 0) {
                if($current == $key) {
                    $isSelected = true;
                    break;
                }
                $current = $postCatalouge->where('id', $current)->value('parent_id');
            }
        }
    @endphp
    <option value="{{ $key }}" {{ $isSelected ? 'selected' : '' }}>
        {{ $val }}
    </option>
@endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Ảnh đại diện</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                <span class="image img-cover image-target">
                                    <img src="{{ old('image', ($postCatalouge->image ?? 'userfiles/image/language/images.png')) }}" alt="">
                                </span>
                                   <input type="text" name="image" value="{{old('image',($postCatalouge->image) ?? '')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nâng cao</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                   <div class="mb-15">
                                   <select name="publish" class="form-control setupSelect2">
                                        @foreach(config('apps.general.publish') as $key => $val)
                                        <option  {{ 
                                            $key == old('publish', (isset($postCatalouge->publish)) ? $postCatalouge->publish : '') ? 'selected' : '' 
                                            }}  value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <br>  <br>
                                   </div>
                                    <div>
                                    <select name="follow" class="form-control setupSelect2">
                                        @foreach(config('apps.general.follow') as $key => $val)
                                        <option  {{ 
                                            $key == old('follow', (isset($postCatalouge->follow)) ? $postCatalouge->follow : '') ? 'selected' : '' 
                                            }}  value="{{ $key }}">{{ $val }}
                                        </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>