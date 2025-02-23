(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");

    HT.createMenuCatalouge = () => {
        $(document).on("submit", ".create-menu-catalouge", function (e) {
            e.preventDefault(); // Đúng cú pháp

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
                    if (res.code == 0) {
                        _form
                            .find(".form-error")
                            .removeClass("error")
                            .addClass("success")
                            .html(res.message)
                            .show();
                        const menuCatalougeSelect = $(
                            "select[name=menu_catalouge_id]"
                        );
                        menuCatalougeSelect.append(
                            '<option value="' +
                                res.data.id +
                                '">' +
                                res.data.name +
                                "</option>"
                        );
                    } else {
                        _form
                            .find(".form-error")
                            .removeClass("success")
                            .addClass("error")
                            .html(res.message)
                            .show();
                    }
                },
                beforeSend: function () {
                    _form.find(".error").html("");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 422) {
                        let errors = jqXHR.responseJSON.errors;

                        for (let field in errors) {
                            let errorMessage = errors[field];
                            errorMessage.forEach(function (message) {
                                $("." + field).html(message);
                            });
                        }
                    }
                },
            });
        });
    };

    $(document).ready(function () {
        HT.createMenuCatalouge();
    });
})(jQuery);
