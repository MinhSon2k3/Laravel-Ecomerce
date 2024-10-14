CKEDITOR.editorConfig = function (config) {
    var BASE_URL = "http://127.0.0.1:8000/";

    // Cấu hình đường dẫn để mở CKFinder
    config.filebrowserBrowseUrl =
        BASE_URL + "giaodien/plugin/ckfinder_2/ckfinder.html";
    config.filebrowserImageBrowseUrl =
        BASE_URL + "giaodien/plugin/ckfinder_2/ckfinder.html?type=Images";
    config.filebrowserFlashBrowseUrl =
        BASE_URL + "giaodien/plugin/ckfinder_2/ckfinder.html?type=Flash";

    // Cấu hình đường dẫn để tải lên file
    config.filebrowserUploadUrl =
        BASE_URL +
        "giaodien/plugin/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Files";
    config.filebrowserImageUploadUrl =
        BASE_URL +
        "giaodien/plugin/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Images";
    config.filebrowserFlashUploadUrl =
        BASE_URL +
        "giaodien/plugin/ckfinder_2/core/connector/php/connector.php?command=QuickUpload&type=Flash";

    // Thêm xử lý đường dẫn trong CKEditor
    config.on = {
        instanceReady: function (evt) {
            var editor = evt.editor;
            editor.dataProcessor.htmlFilter.addRules({
                elements: {
                    img: {
                        attributes: {
                            src: function (value) {
                                // Chỉ lấy đường dẫn userfiles
                                return value.replace(
                                    /.*?(\/userfiles\/.*)/,
                                    "$1"
                                ); // Giữ lại đường dẫn bắt đầu từ userfiles
                            },
                        },
                    },
                },
            });
        },
    };
};
