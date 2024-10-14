var urlContainer = document.getElementById("url-container");
var getLocationUrl = urlContainer.getAttribute("data-url");

(function ($) {
    "use strict";

    var HT = {};
    var district_id = ""; // Khai báo biến district_id
    var ward_id = ""; // Khai báo biến ward_id

    HT.getLocation = () => {
        $(document).on("change", ".location", function () {
            let _this = $(this);
            let option = {
                location_id: _this.val(),
                target: _this.attr("data-target"),
            };
            console.log(option);
            HT.sendDataTogetLocation(option);
        });
    };

    HT.sendDataTogetLocation = (option) => {
        $.ajax({
            url: getLocationUrl,
            type: "GET",
            data: option,
            dataType: "json",
            success: function (res) {
                $("." + option.target).html(res.html);

                // Xử lý district_id và ward_id nếu có giá trị
                if (district_id !== "" && option.target === "districts") {
                    $(".districts").val(district_id).trigger("change");
                }
                if (ward_id !== "" && option.target === "wards") {
                    $(".wards").val(ward_id).trigger("change");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(
                    "AJAX báo lỗi: " + textStatus + " : " + errorThrown
                );
            },
        });
    };

    $(document).ready(function () {
        HT.getLocation();
    });
})(jQuery);
