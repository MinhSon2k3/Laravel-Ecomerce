(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.createMenuCatalouge = () => {
        $(document).on("submit", ".create-menu-catalouge", function (e) {
            e.preventDefault();
            let _form = $(this);
            let option = {
                name: _form.find("input[name=name]").val(),
                keyword: _form.find("input[name=keyword]").val(),
                _token: _token,
            };

            $.ajax({
                url: "ajax/menu/createCatalouge",
                type: "POST",
                data: option,
                dataType: "json",
                success: function (res) {
                    let formError = _form.find(".form-error");
                    if (res.code == 0) {
                        formError
                            .removeClass("error")
                            .addClass("success")
                            .html(res.message)
                            .show();
                        $("select[name=menu_catalouge_id]").append(
                            `<option value="${res.data.id}">${res.data.name}</option>`
                        );
                    } else {
                        formError
                            .removeClass("success")
                            .addClass("error")
                            .html(res.message)
                            .show();
                    }
                },
                beforeSend: function () {
                    _form.find(".error").html("");
                },
                error: function (jqXHR) {
                    if (jqXHR.status === 422) {
                        let errors = jqXHR.responseJSON.errors;
                        for (let field in errors) {
                            $("." + field).html(errors[field].join("<br>"));
                        }
                    }
                },
            });
        });
    };

    HT.createMenuRow = () => {
        $(document).on("click", ".add-menu", function (e) {
            e.preventDefault();
            $(".menu-wrapper")
                .append(HT.menuRowHtml())
                .find(".notification")
                .hide();
        });
    };

    HT.menuRowHtml = (option = {}) => {
        let canonical = option.canonical || "";
        let name = option.name || "";
        let $row = $(`
            <div class="row mb10 menu-item ${canonical}">
                <div class="col-lg-4"><input type="text" name="menu[name][]" value="${name}" class="form-control"></div>
                <div class="col-lg-4"><input type="text" name="menu[canonical][]" value="${canonical}" class="form-control"></div>
                <div class="col-lg-2"><input type="text" name="menu[order][]" value="0" class="form-control"></div>
                <div class="col-lg-2 text-center">
                    <a href="#" class="delete-menu"><i class="fa fa-trash-o"></i></a>
                </div>
            </div>
        `);
        return $row.prop("outerHTML");
    };

    HT.deleteMenuRow = () => {
        $(document).on("click", ".delete-menu", function (e) {
            e.preventDefault();
            $(this).closest(".menu-item").remove();
            HT.checkMenuItemLength();
        });
    };

    HT.checkMenuItemLength = () => {
        if ($(".menu-item").length === 0) {
            $(".notice").show();
        }
    };

    HT.getMenu = () => {
        $(document).on("click", ".menu-module", function () {
            let _this = $(this);
            let model = _this.attr("data-model");

            $.ajax({
                url: "ajax/dashboard/getMenu",
                type: "GET",
                data: { model: model },
                dataType: "json",
                beforeSend: function () {
                    _this.parents(".panel-default").find(".menu-list").html("");
                },
                success: function (res) {
                    let menuRowClass = HT.checkMenuRowExist();
                    let html = res.data.data
                        .map((item) => HT.renderModelMenu(item, menuRowClass))
                        .join("");
                    _this
                        .parents(".panel-default")
                        .find(".menu-list")
                        .html(html);
                },
            });
        });
    };

    HT.checkMenuRowExist = () => {
        return $(".menu-item")
            .map(function () {
                return $(this).find("input[name='menu[canonical][]']").val();
            })
            .get();
    };

    HT.renderModelMenu = (object, menuRowClass) => {
        let isChecked = menuRowClass.includes(object.canonical);
        return `
            <div class="m-item">
                <div class="uk-flex uk-flex-middle">
                    <input type="checkbox" class="choose-menu" value="${
                        object.canonical
                    }" ${isChecked ? "checked" : ""}>
                    <label class="mt10 ml5">${object.name}</label>
                </div>
            </div>
        `;
    };

    HT.searchMenu = () => {
        let debounceTimer;

        $(document).on("keyup", ".search-menu", function () {
            clearTimeout(debounceTimer);

            let keyword = $(this).val().trim();
            let model = $(this).closest("form").data("model");
            let menuList = $(this).closest(".panel-default").find(".menu-list"); // Lấy danh sách menu

            if (model && keyword.length >= 2) {
                debounceTimer = setTimeout(function () {
                    $.ajax({
                        url: "ajax/dashboard/getMenu",
                        method: "GET",
                        data: { model: model, keyword: keyword },
                        success: function (response) {
                            console.log(response);
                            let menuRowClass = HT.checkMenuRowExist();
                            let html = response.data.data
                                .map((item) =>
                                    HT.renderModelMenu(item, menuRowClass)
                                )
                                .join("");

                            menuList.html(html); // Xóa menu cũ và hiển thị mới
                        },
                        error: function (xhr) {
                            console.error(
                                "Lỗi khi lấy menu:",
                                xhr.responseText
                            );
                        },
                    });
                }, 500);
            } else {
                menuList.html(""); // Xóa danh sách menu nếu không có từ khóa
            }
        });
    };

    HT.chooseMenu = () => {
        $(document).on("click", ".choose-menu", function () {
            let _this = $(this);
            let canonical = _this.val();
            let name = _this.siblings("label").text();
            let isChecked = _this.prop("checked");

            if (isChecked) {
                $(".menu-wrapper")
                    .append(HT.menuRowHtml({ name, canonical }))
                    .find(".notification")
                    .hide();
            } else {
                $(".menu-wrapper").find(`.menu-item.${canonical}`).remove();
            }
        });
    };
    HT.setupNestable = () => {
        if ($("#nestable2").length) {
            $("#nestable2")
                .nestable({
                    group: 1,
                })
                .on("change", updateOutput);
        }
    };

    HT.updateNestableOutput = () => {
        const updateOutput = (e) => {
            const list = e.length ? e : $(e.target);
            const output = list.data("output");

            if (window.JSON) {
                output.val(JSON.stringify(list.nestable("serialize")));
            } else {
                output.val("JSON browser support required for this demo.");
            }
        };
    };
    HT.runUpdateNestableOutput = () => {
        updateOutput($("#nestable2").data("output", $("#nestable2-output")));
    };
    HT.expandAndCollapse = () => {
        $("#nestable-menu").on("click", function (e) {
            var target = $(e.target),
                action = target.data("action");

            if (action === "expand-all") {
                $(".dd").nestable("expandAll");
            }

            if (action === "collapse-all") {
                $(".dd").nestable("collapseAll");
            }
        });
    };

    $(document).ready(function () {
        HT.createMenuCatalouge();
        HT.createMenuRow();
        HT.deleteMenuRow();
        HT.getMenu();
        HT.searchMenu();
        HT.chooseMenu();
        HT.setupNestable();
        HT.updateNestableOutput();
        HT.runUpdateNestableOutput();
        HT.expandAndCollapse();
    });
})(jQuery);
