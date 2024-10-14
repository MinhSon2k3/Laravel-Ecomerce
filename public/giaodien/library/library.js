(function ($) {
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr("content");
    HT.switchery = () => {
        $(".js-switch").each(function () {
            //  let _this=$(this)

            var switchery = new Switchery(this, { color: "#1AB393" });
        });
    };

    HT.select2 = () => {
        if (".setupSelect2".length) {
            $(".setupSelect2").select2();
        }
    };

    HT.changeStatus = () => {
        $(document).on("change", ".status", function () {
            let _this = $(this);
            let option = {
                value: _this.val(),
                modelId: _this.attr("data-modelId"),
                model: _this.attr("data-model"),
                field: _this.attr("data-field"),
                _token: _token,
            };

            $.ajax({
                url: "ajax/dashboard/changeStatus",
                type: "POST",
                data: option,
                dataType: "json",
                success: function (res) {
                    let inputValue = option.value == 1 ? 2 : 1;
                    if (res.flag == true) {
                        _this.val(inputValue);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(
                        "AJAX báo lỗi: " + textStatus + " : " + errorThrown
                    );
                },
            });
        });
    };

    HT.changeStatusAll = () => {
        if ($(".changeStatusAll").length) {
            $(document).on("click", ".changeStatusAll", function () {
                let _this = $(this);
                let id = [];
                $(".checkBoxItem").each(function () {
                    let checkBox = $(this);
                    if (checkBox.prop("checked")) {
                        id.push(checkBox.val());
                    }
                });
                let option = {
                    value: _this.attr("data-value"),
                    model: _this.attr("data-model"),
                    field: _this.attr("data-field"),
                    id: id,
                    _token: _token,
                };
                $.ajax({
                    url: "ajax/dashboard/changeStatusAll",
                    type: "POST",
                    data: option,
                    dataType: "json",
                    success: function (res) {
                        if (res.flag == true) {
                            let cssActive1 =
                                "background-color: rgb(26, 179, 147); border-color: rgb(26, 179, 147); box-shadow: rgb(26, 179, 147) 0px 0px 0px 16px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s, background-color 1.2s ease 0s;";

                            let cssActive2 =
                                "left: 20px; background-color: rgb(255, 255, 255); transition: background-color 0.4s ease 0s, left 0.2s ease 0s;";

                            let cssUnAcctive1 =
                                "background-color: rgb(255, 255, 255); border-color: rgb(223, 223, 223); box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; transition: border 0.4s ease 0s, box-shadow 0.4s ease 0s;";
                            let cssUnAcctive2 =
                                "left: 0px; transition: background-color 0.4s ease 0s, left 0.2s ease 0s;";
                            for (let i = 0; i <= id.length; i++) {
                                if (option.value == 1) {
                                    $(".js-switch-" + id[i])
                                        .find("span.switchery")
                                        .attr("style", cssActive1)
                                        .find("small")
                                        .attr("style", cssActive2);
                                } else if (option.value == 2) {
                                    $(".js-switch-" + id[i])
                                        .find("span.switchery")
                                        .attr("style", cssUnAcctive1)
                                        .find("small")
                                        .attr("style", cssUnAcctive2);
                                }
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(
                            "AJAX báo lỗi: " + textStatus + " : " + errorThrown
                        );
                    },
                });
            });
        }
    };

    HT.checkAll = () => {
        if ($("#checkAll").length) {
            //kiểm tra xem phần tử có ID là checkAll có tồn tại trong DOM hay không.
            $(document).on("click", "#checkAll", function () {
                let isChecked = $(this).prop("checked"); //kiểm tra xem checkbox có được chọn hay không và lưu kết quả đó vào biến isChecked(T/F)
                $(".checkBoxItem").prop("checked", isChecked); //gán checked của tất cả các phần tử checkBoxItem theo giá trị của isChecked.
                $(".checkBoxItem").each(function () {
                    let _this = $(this);
                    if (_this.prop("checked")) {
                        _this.closest("tr").addClass("active-bg");
                    } else {
                        _this.closest("tr").removeClass("active-bg");
                    }
                });
            });
        }
    };
    HT.checkBoxItem = () => {
        if ($(".checkBoxItem").length) {
            $(document).on("click", ".checkBoxItem", function () {
                let _this = $(this);
                let isChecked = $(this).prop("checked");
                let uncheckedCheckboxesExist =
                    $(".input-check:not(:checked)").length > 0;
                $("#checkAll").prop("checked", !uncheckedCheckboxesExist);
                if (isChecked) {
                    _this.closest("tr").addClass("active-bg");
                } else {
                    _this.closest("tr").removeClass("active-bg");
                }
            });
        }
    };

    $(document).ready(function () {
        HT.switchery();
        HT.select2();
        HT.changeStatus();
        HT.checkAll();
        HT.checkBoxItem();
        HT.changeStatusAll();
    });
})(jQuery);
