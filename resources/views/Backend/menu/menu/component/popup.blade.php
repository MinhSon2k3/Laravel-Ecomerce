<div class="modal fade" id="createMenuCatalouge" tabindex="-1" aria-labelledby="exampleModalLabel">
    <form class="form create-menu-catalouge">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="exampleModalLabel">Thêm mới vị trí hiển thị của menu</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-error" style="color:red;"></div>
                    <div class="row">
                        <div class="col-lg-12 mb10">
                            <label for="">Tên vị trí hiển thị</label>
                            <input type="text" class="form-control" name="name">
                            <div class="error name" style="color:red;"></div>
                        </div>
                        <div class="col-lg-12 mb10">
                            <label for="">Từ khóa</label>
                            <input type="text" class="form-control" name="keyword">
                            <div class="error keyword" style="color:red;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close"
                        aria-hidden="true">Hủy
                        bỏ</button>
                    <button type="submit" name="create" value="create" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
        </div>
    </form>
</div>