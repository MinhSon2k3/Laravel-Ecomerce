<div class="ibox">
    <div class="ibox-title">
        <div>
            <h5>Sản phẩm có nhiều phiên bản</h5>
        </div>
        <div>
            sadasd
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="variant-checkbox">
                    <input type="checkbox" value="" name="accept" id="variantCheckbox">
                    <label for="variantCheckbox" class="turnOnVariant">Sản phẩm này có nhiều biến thể</label>
                </div>
            </div>
        </div>
        <div class="variant-wrapper hidden">
            <div class="row variant-container">
                <div class="col-lg-3">
                    <div class="attribute-title">
                        Chọn thuộc tính
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="attribute-title">
                        Chọn giá trị thuộc tính
                    </div>
                </div>
            </div>
            <div class="variant-body">
                
            </div>
            <div class="variant-foot">
                <button type="button" class="add-variant">Thêm phiên bản mới</button>
            </div>
        </div>
    </div>
</div>
<div class="ibox product-variant">
    <div class="ibox-title">
        <h5>Danh sách phiên bản</h5>
    </div>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped variantTable">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

        <tr>
            <td colspan="6">
                <div class="updateVariant ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <h5>Cập nhật thông tin phiên bản</h5>
                    <div class="button-group">
                        <div class="uk-flex uk-flex-middle">
                        <button type="button" class="cancelUpdate btn btn-danger">Hủy bỏ </button>
                        <button type="button" class="cancelUpdate btn btn-success">Lưu lại </button>
                        @include('backend.dashboard.component.album')
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </td>
        </tr>


    </div>
</div>

<script>
    var attributeCatalouge = @json($attributeCatalouge->map(function($item){
        $name = $item->attribute_catalouge_languages->first()->name;
        return [
            'id' => $item->id,
            'name' => $name
        ];
    })->values());
</script>
