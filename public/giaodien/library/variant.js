(function ($) {
    "use strict";
    var HT = {};

    // Thiết lập sự kiện cho sản phẩm có nhiều phiên bản
    HT.setupProductVariant = () => {
        if ($(".turnOnVariant").length) {
            $(document).on("click", ".turnOnVariant", function () {
                let _this = $(this);
                // Kiểm tra nếu không có thuộc tính nào được chọn, hiển thị các phiên bản
                if (_this.siblings("input:checked").length == 0) {
                    $(".variant-wrapper").removeClass("hidden");
                } else {
                    $(".variant-wrapper").addClass("hidden");
                }
            });
        }
    };

    // Thêm một phiên bản mới vào danh sách các phiên bản sản phẩm
    HT.addVariant = () => {
        if ($(".add-variant").length) {
            $(document).on("click", ".add-variant", function () {
                // Render một item phiên bản mới
                let html = HT.renderVariantItem(attributeCatalouge);
                $(".variant-body").append(html); // Thêm vào phần thân phiên bản
                HT.checkMaxAttributeGroup(attributeCatalouge); // Kiểm tra số lượng thuộc tính tối đa
                HT.updateDisabledOptions(); // Cập nhật các thuộc tính đã bị vô hiệu hóa
                HT.destroyNiceSelect(); // Hủy bỏ NiceSelect
                HT.niceSelect(); // Khởi tạo lại NiceSelect
            });
        }
    };

    // Hàm render một item phiên bản mới
    HT.renderVariantItem = (attributeCatalouge) => {
        let html = "";
        html += `
            <div class="row mb20 space-top variant-item">
                <div class="col-lg-3">
                    <div class="attribute-catalogue">
                        <select name="" id="" class="choose-attribute niceSelect">
                            <option value="0">Chọn nhóm thuộc tính</option>
        `;
        // Thêm các lựa chọn từ attributeCatalouge vào dropdown
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
                    <button type="button" class="remove-attribute btn btn-danger">
                        <svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15" height="16" viewBox="0 0 15 16" class="bem-Svg" style="display: block;">
                            <path fill="currentColor" d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;
        return html;
    };

    // Khởi tạo NiceSelect (plugin tùy chỉnh cho dropdown)
    HT.niceSelect = () => {
        $(".niceSelect").niceSelect();
    };

    // Hủy bỏ NiceSelect
    HT.destroyNiceSelect = () => {
        if ($(".niceSelect").length) {
            $(".niceSelect").niceSelect("destroy");
        }
    };

    // Cập nhật các thuộc tính đã chọn trong các dropdown
    HT.updateDisabledOptions = () => {
        let selectedIds = [];

        // Lấy tất cả các giá trị đã chọn trong các dropdown
        $(".choose-attribute").each(function () {
            let selectedValue = $(this).find("option:selected").val();
            if (selectedValue != "0" && selectedValue != "") {
                selectedIds.push(selectedValue);
            }
        });

        // Bỏ disabled cho tất cả các tùy chọn
        $(".choose-attribute").find("option").removeAttr("disabled");

        // Disable các tùy chọn đã được chọn trước đó
        $(".choose-attribute").each(function () {
            let $select = $(this);
            let currentValue = $select.find("option:selected").val();
            $select.find("option").each(function () {
                if (
                    selectedIds.includes($(this).val()) &&
                    $(this).val() != currentValue
                ) {
                    $(this).prop("disabled", true); // Vô hiệu hóa các tùy chọn đã chọn
                }
            });
        });

        HT.destroyNiceSelect(); // Hủy bỏ NiceSelect
        HT.niceSelect(); // Khởi tạo lại NiceSelect
    };

    // Kiểm tra số lượng nhóm thuộc tính, nếu vượt quá thì ẩn nút "Thêm phiên bản mới"
    HT.checkMaxAttributeGroup = (attributeCatalouge) => {
        let variantItem = $(".variant-item").length;
        if (variantItem >= attributeCatalouge.length) {
            $(".add-variant").remove(); // Ẩn nút nếu số lượng nhóm thuộc tính đã đủ
        } else {
            $(".variant-foot").html(
                '<button type="button" class="add-variant">Thêm phiên bản mới</button>'
            );
        }
    };

    // Xóa một thuộc tính khỏi danh sách phiên bản
    HT.removeAttribute = () => {
        $(document).on("click", ".remove-attribute", function () {
            let _this = $(this);
            _this.parents(".variant-item").remove(); // Xóa item thuộc tính
            HT.checkMaxAttributeGroup(attributeCatalouge); // Kiểm tra lại số lượng nhóm thuộc tính
            HT.createVariant(); // Cập nhật danh sách phiên bản
        });
    };

    // Xử lý sự kiện khi người dùng chọn nhóm thuộc tính
    HT.chooseVariantGroup = () => {
        $(document).on("change", ".choose-attribute", function () {
            let _this = $(this);
            let attributeCatalougeId = _this.val();
            if (attributeCatalougeId != 0) {
                // Tạo dropdown con mới cho nhóm thuộc tính đã chọn
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(HT.select2Variant(attributeCatalougeId));
                $(".selectVariant").each(function (key, index) {
                    HT.getSelect2($(this)); // Khởi tạo Select2 cho dropdown mới
                });
            } else {
                _this
                    .parents(".col-lg-3")
                    .siblings(".col-lg-8")
                    .html(
                        '<input type="text" name="" disabled="" class="fake-variant form-control">'
                    );
            }

            HT.disabledAttributeCatalogueChoose(); // Cập nhật các thuộc tính đã chọn
        });
    };

    // Render dropdown select2 cho các thuộc tính đã chọn
    HT.select2Variant = (attributeCatalougeId) => {
        let html =
            '<select class="selectVariant variant-' +
            attributeCatalougeId +
            ' form-control" name="attribute[' +
            attributeCatalougeId +
            '][]" multiple data-catid="' +
            attributeCatalougeId +
            '"></select>';
        return html;
    };

    // Khởi tạo Select2 cho các dropdowns
    HT.getSelect2 = (object) => {
        let option = {
            attributeCatalougeId: object.attr("data-catid"),
        };
        $(object).select2({
            minimumInputLength: 2, // Tìm kiếm với ít nhất 2 ký tự
            placeholder: "Nhập tối thiểu 2 kí tự để tìm kiếm",
            ajax: {
                url: "ajax/attribute/getAttribute", // Lấy dữ liệu từ API
                type: "GET",
                dataType: "json",
                deley: 250,
                data: function (params) {
                    return {
                        search: params.term, // Tìm kiếm theo từ khóa
                        option: option,
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items, // Hiển thị kết quả tìm kiếm
                    };
                },
                cache: true,
            },
        });
    };

    // Vô hiệu hóa các thuộc tính đã chọn trong các dropdown
    HT.disabledAttributeCatalogueChoose = () => {
        let id = [];
        $(".choose-attribute").each(function () {
            let _this = $(this);
            let selected = _this.find("option:selected").val();
            if (selected != 0) {
                id.push(selected);
            }
        });

        $(".choose-attribute").find("option").removeAttr("disabled"); // Bỏ vô hiệu hóa cho tất cả
        for (let i = 0; i < id.length; i++) {
            $(".choose-attribute")
                .find("option[value=" + id[i] + "]")
                .prop("disabled", true); // Vô hiệu hóa các thuộc tính đã chọn
        }
        HT.destroyNiceSelect(); // Hủy bỏ NiceSelect
        HT.niceSelect(); // Khởi tạo lại NiceSelect
        $(".choose-attribute").find("option:selected").removeAttr("disabled");
    };

    // Tạo các phiên bản sản phẩm khi người dùng thay đổi lựa chọn trong dropdown
    HT.createProductVariant = () => {
        $(document).on("change", ".selectVariant", function () {
            let _this = $(this);
            HT.createVariant(); // Tạo phiên bản mới
        });
    };

    // Tạo phiên bản từ các thuộc tính đã chọn
    HT.createVariant = () => {
        let attributes = [];
        let variants = [];
        let attributeTitle = [];

        // Lặp qua tất cả các item variant để tạo các thuộc tính và phiên bản
        $(".variant-item").each(function () {
            let _this = $(this);
            let attr = [];
            let attrVariant = [];
            const attributeCatalogueId = _this.find(".choose-attribute").val();
            const optionText = _this
                .find(".choose-attribute option:selected")
                .text();
            const attribute = $(".variant-" + attributeCatalogueId).select2(
                "data"
            );

            // Lưu các thuộc tính và phiên bản đã chọn
            for (let i = 0; i < attribute.length; i++) {
                let item = {};
                let itemVariant = {};
                item[optionText] = attribute[i].text;
                itemVariant[attributeCatalogueId] = attribute[i].id;
                attr.push(item);
                attrVariant.push(itemVariant);
            }
            attributeTitle.push(optionText);
            attributes.push(attr);
            variants.push(attrVariant);
        });

        // Kết hợp các thuộc tính thành các phiên bản
        attributes = attributes.reduce((a, b) =>
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );

        variants = variants.reduce((a, b) =>
            a.flatMap((d) => b.map((e) => ({ ...d, ...e })))
        );

        HT.createTableHeader(attributeTitle); // Tạo tiêu đề cho bảng phiên bản

        // Render các phiên bản vào bảng
        let trClass = [];
        attributes.forEach((item, index) => {
            let $row = HT.createVariantRow(item, variants[index]);
            let classModified =
                "tr-variant-" +
                Object.values(variants[index]).join(", ").replace(/, /g, "-");
            trClass.push(classModified);
            if (!$("table.variantTable tbody tr").hasClass(classModified)) {
                $("table.variantTable tbody").append($row);
            }
        });

        // Xóa các dòng không còn phù hợp trong bảng
        $("table.variantTable tbody tr").each(function () {
            const $row = $(this);
            const rowClasses = $row.attr("class");
            if (rowClasses) {
                const rowClassArray = rowClasses.split(" ");
                let shouldRemove = false;
                rowClassArray.forEach((rowClass) => {
                    if (rowClass == "variant-row") {
                        return;
                    } else if (!trClass.includes(rowClass)) {
                        shouldRemove = true;
                    }
                });
                if (shouldRemove) {
                    $row.remove();
                }
            }
        });
    };
    HT.createVariantRow = (attributeItem, variantItem) => {
        let attributeString = Object.values(attributeItem).join(", ");
        let attributeId = Object.values(variantItem).join(", ");
        let classModified = attributeId.replace(/, /g, "-");

        let $row = $("<tr>").addClass(
            "variant-row tr-variant-" + classModified
        );
        let $td;

        $td = $("<td>").append(
            $("<span>")
                .addClass("image img-cover image-size-post")
                .append(
                    $("<img>")
                        .attr(
                            "src",
                            "https://daks2k3a4ib2z.cloudfront.net/6343da4ea0e69336d8375527/6343da5f04a965c89988b149_1665391198377-image16-p-500.jpg"
                        )
                        .addClass("imageSrc")
                )
        );
        $row.append($td);

        Object.values(attributeItem).forEach((value) => {
            $td = $("<td>").text(value);
            $row.append($td);
        });

        $td = $("<td>").addClass("hidden td-variant");
        let inputHiddenFields = [
            { name: "variant[quantity][]", class: "variant_quantity" },
            { name: "variant[sku][]", class: "variant_sku" },
            { name: "variant[price][]", class: "variant_price" },
            { name: "variant[barcode][]", class: "variant_barcode" },
            { name: "variant[file_name][]", class: "variant_filename" },
            { name: "variant[file_url][]", class: "variant_fileurl" },
            { name: "variant[album][]", class: "variant_album" },
            { name: "attribute[name][]", value: attributeString },
            { name: "attribute[id][]", value: attributeId },
        ];

        $.each(inputHiddenFields, function (_, field) {
            let $input = $("<input>")
                .attr("type", "text")
                .attr("name", field.name)
                .addClass(field.class);
            if (field.value) {
                $input.val(field.value);
            }
            $td.append($input);
        });

        $row.append($("<td>").addClass("td-quantity").text("-"))
            .append($("<td>").addClass("td-price").text("-"))
            .append($("<td>").addClass("td-sku").text("-"))
            .append($td);
        return $row;
    };

    HT.createTableHeader = (attributeTitle) => {
        let $thead = $("table.variantTable thead");
        let $row = $("<tr>");

        // Add 'font-weight-bold' class to the first "Hình Ảnh" column
        $row.append($("<td>").text("Hình Ảnh").addClass("font-weight-bold"));

        // Add 'font-weight-bold' class to the dynamic attribute columns
        for (let i = 0; i < attributeTitle.length; i++) {
            $row.append(
                $("<td>").text(attributeTitle[i]).addClass("font-weight-bold")
            );
        }

        // Add 'font-weight-bold' class to the "Số lượng", "Giá tiền", and "SKU" columns
        $row.append($("<td>").text("Số lượng").addClass("font-weight-bold"));
        $row.append($("<td>").text("Giá tiền").addClass("font-weight-bold"));
        $row.append($("<td>").text("SKU").addClass("font-weight-bold"));

        // Set the row as the table header
        $thead.html($row);
        return $thead;
    };
    HT.variantAlbum = () => {
        $(document).on("click", ".click-to-upload-variant", function (e) {
            HT.browseVariantServerAlbum();
            e.preventDefault();
        });
    };
    HT.browseVariantServerAlbum = () => {
        var type = "Images";
        var finder = new CKFinder();

        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = "";
            for (var i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += '<li class="ui-state-default">';
                html += ' <div class="thumb">';
                html += ' <span class="span image img-scaledown">';
                html += '<img src="' + image + '" alt="' + image + '">';
                html +=
                    '<input type="hidden" name="variantAlbum[]" value="' +
                    image +
                    '">';
                html += "</span>";
                html +=
                    '<button class="variant-delete-image"><i class="fa fa-trash"></i></button>';
                html += "</div>";
                html += "</li>";
            }

            $(".click-to-upload-variant").addClass("hidden");
            $("#sortable2").append(html);
            $(".upload-variant-list").removeClass("hidden");
        };
        finder.popup();
    };
    // Khởi tạo các sự kiện khi trang đã sẵn sàng
    $(document).ready(function () {
        HT.setupProductVariant(); // Thiết lập sản phẩm có nhiều phiên bản
        HT.addVariant(); // Thêm phiên bản mới
        HT.niceSelect(); // Khởi tạo NiceSelect
        HT.removeAttribute(); // Xóa thuộc tính
        HT.chooseVariantGroup(); // Chọn nhóm thuộc tính
        HT.createProductVariant(); // Tạo phiên bản sản phẩm
        HT.variantAlbum();
    });

    // Cập nhật các tùy chọn khi người dùng thay đổi lựa chọn
    $(document).on("change", ".choose-attribute", function () {
        HT.updateDisabledOptions(); // Cập nhật các tùy chọn đã bị vô hiệu hóa
    });
})(jQuery);
