<?php include 'app/views/shares/header.php'; ?>


<div class="card container my-3">
    <div class="card-header row">
        <div class="col-10">
            <h1>Thêm Sản Phẩm</h1>
        </div>
        <div class="col-2">
            <a href="/project1/product" class="btn btn-success">Quay về</a>
        </div>
    </div>
    <div class="card-body">
        <form action="/project1/product/SaveAdd" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên Thể Sản Phẩm</label>
                <input name="name" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Giá Sản Phẩm</label>
                <input name="price" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Hình Ảnh</label>
                <input id="AnhVuaTai" type="file" name="hinhanh" class="form-control">
                <div class="ChuaAnh"></div>

            </div>
            <div class="mb-3">
                <label class="form-label">Thể Loại Sản Phẩm</label>
                <select name='categoryid' class="form-control">
                    <?php if ($theloai != null): ?>
                        <?php foreach ($theloai as $i): ?>
                            <option value="<?php echo htmlspecialchars($i->id) ?>"><?php echo htmlspecialchars($i->name) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </select>
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



<script>
    var anh = document.querySelector("#AnhVuaTai");
    anh.addEventListener('change', function(e) {
        let a = e.target.files[0]; // lấy thằng file đầu tiên
        if (a != null) {
            document.querySelector(".ChuaAnh").innerHTML = ""; // Xóa hình ảnh cũ
            let path = URL.createObjectURL(a) // lấy ra địa chỉ

            let hinhanhthem = document.createElement('img');

            hinhanhthem.setAttribute('src', path);
            hinhanhthem.style.width = "100px";
            hinhanhthem.style.height = "100px";
            hinhanhthem.classList.add('my-2');

            document.querySelector(".ChuaAnh").appendChild(hinhanhthem);
        }
    });
</script>
<?php include 'app/views/shares/footer.php'; ?>