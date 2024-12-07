(function ($) {
    "use strict";
    var HT = {};

    // Thiết lập sản phẩm có nhiều phiên bản
    HT.setupProductVariant = () => {
        if ($(".turnOnVariant").length) {
            $(document).on("click", ".turnOnVariant", function () {
                let _this = $(this);
                if (_this.siblings("input:checked").length == 0) {
                    $(".variant-wrapper").removeClass("hidden");
                } else {
                    $(".variant-wrapper").addClass("hidden");
                }
            });
        }
    };

    // Thêm phiên bản mới
    HT.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                let html = HT.renderVariantItem(attributeCatalouge);
                $(".variant-body").append(html);
                HT.checkMaxAttributeGroup(attributeCatalouge);
                HT.updateDisabledOptions(); // Cập nhật disabled cho các thuộc tính sau khi thêm
                HT.destroyNiceSelect();
                HT.niceSelect();
            });
        }
    };

    // Render item variant
    HT.renderVariantItem = (attributeCatalouge) => {
        let html = "";
        html += `
            <div class="row mb20 space-top variant-item">
                <div class="col-lg-3">
                    <div class="attribute-catalogue">
                        <select name="" id="" class="choose-attribute niceSelect">
                            <option value="0">Chọn nhóm thuộc tính</option>
        `;

        // Thêm các tùy chọn từ attributeCatalogue
        attributeCatalouge.forEach((item) => {
            html += `<option value="${item.id}">${item.name}</option>`;
        });

        html += `
                        </select>
                    </div>
                </div>
                <div class="col-lg-8">
                    <input type="text" disabled class="fake-variant form-control">
                </div>
                <div class="col-lg-1">
                    <button type="button" class="remove-attribute btn btn-danger"><svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-Svg" style="display: block;"><path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path></svg></button>
                </div>
            </div>
        `;
        return html;
    };

    // Khởi tạo NiceSelect
    HT.niceSelect = () => {
        $(".niceSelect").niceSelect();
    };

    // Hủy bỏ NiceSelect
    HT.destroyNiceSelect = () => {
        if ($(".niceSelect").length) {
            $(".niceSelect").niceSelect("destroy");
        }
    };

    // Cập nhật các thuộc tính đã được chọn
    HT.updateDisabledOptions = () => {
        let selectedIds = [];

        // Lấy tất cả các giá trị đã chọn từ các select
        $(".choose-attribute").each(function () {
            let selectedValue = $(this).find("option:selected").val();
            if (selectedValue != "0" && selectedValue != "") {
                selectedIds.push(selectedValue);
            }
        });

        // Bỏ "disabled" trên tất cả các option
        $(".choose-attribute").find("option").removeAttr("disabled");

        // Disable các option đã được chọn
        $(".choose-attribute").each(function () {
            let $select = $(this);
            let currentValue = $select.find("option:selected").val();
            $select.find("option").each(function () {
                if (
                    selectedIds.includes($(this).val()) &&
                    $(this).val() != currentValue
                ) {
                    $(this).prop("disabled", true); // Disable tất cả các option đã được chọn trước đó
                }
            });
        });

        HT.destroyNiceSelect();
        HT.niceSelect();
    };
    HT.checkMaxAttributeGroup = (attributeCatalouge) => {
        let variantItem = $(".variant-item").length;
        if (variantItem >= attributeCatalouge.length) {
            $(".add-variant").remove();
        } else {
            $(".variant-foot").html(
                '<button type="button" class="add-variant">Thêm phiên bản mới</button>'
            );
        }
    };
    HT.removeAttribute = () => {
        $(document).on("click", ".remove-attribute", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove();
            HT.checkMaxAttributeGroup(attributeCatalouge);
            HT.createVariant();
        });
    };
    // Khởi tạo các sự kiện khi trang đã sẵn sàng
    $(document).ready(function () {
        HT.setupProductVariant();
        HT.addVariant();
        HT.niceSelect();
        HT.removeAttribute();
    });

    // Cập nhật disabled options khi người dùng thay đổi lựa chọn
    $(document).on("change", ".choose-attribute", function () {
        HT.updateDisabledOptions();
    });
})(jQuery);
