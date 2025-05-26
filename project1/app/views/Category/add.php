<?php include 'app/views/shares/header.php'; ?>


<div class="card">
    <div class="card-header row">
        <div class="col-10">
            <h1>Thêm Sản Phẩm</h1>
        </div>
        <div class="col-2">
            <a href="/project1/Category">Quay về</a>
        </div>
    </div>
    <div class="card-body">
        <form action="/project1/Category/SaveAdd" method="POST">
            <div class="mb-3">
                <label class="form-label">Tên Thể Loại</label>
                <input name="name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Mô Tả</label>
                <!-- <input name="des" class="form-control"> -->
                <textarea name="des" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
    </div>
</div>




<?php include 'app/views/shares/footer.php'; ?>