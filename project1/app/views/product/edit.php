<?php include 'app/views/shares/header.php' ?>

<?php if ($sp != null): ?>
    <div class="container my-5">
        <div class="row g-4 align-items-center">
            <!-- Hình ảnh -->

            <?php if ($sp->image != null): ?>
                <div class="col-md-6">
                    <img src="<?php echo $sp->image; ?>" class="img-fluid rounded shadow" alt="Ảnh sản phẩm">
                </div>
            <?php else: ?>
                <div class="col-md-6">
                    <img src="https://picsum.photos/id/<?php echo ($sp->ID + 109); ?>/600/400" class="img-fluid rounded shadow" alt="Ảnh sản phẩm">
                </div>
            <?php endif ?>


            <div class="col-md-6">
                <!-- Thông tin chi tiết -->
                <form action="/project1/product/SaveEdit" method="POST" enctype="multipart/form-data">
                    <div class="col">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($sp->id) ?>" />
                        <div class="mb-3">
                            <label class="form-label">Tên Sản Phẩm</label>
                            <input name="name" class="form-control fw-bold" value="<?php echo htmlspecialchars($sp->name, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Giá Sản Phẩm</label>
                            <input type="number" name="price" class="form-control fw-bold" value="<?php echo htmlspecialchars($sp->price, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Hình Ảnh</label>
                                    <input readonly value="<?php echo htmlspecialchars($sp->image) ?>" class="form-control">
                                    <div>
                                        <img class="w-50 h-50" src="<?php echo htmlspecialchars($sp->image) ?>" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Hình Ảnh</label>
                                    <input id="AnhVuaTai" type="file" name="hinhanh" class="form-control">
                                    <div class="ChuaAnh"></div>

                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea name="des" class="form-control"><?php echo htmlspecialchars($sp-> description, ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thể Loại</label>
                            <select name="categoryid" class="form-control">
                                <?php if ($theloai != null): ?>
                                    <?php foreach ($theloai as $i): ?>
                                        <?php if ($i->ID == $sp->category_id): ?>
                                            <option selected value="<?php echo htmlspecialchars($i->id) ?>"><?php echo htmlspecialchars($i->name, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php else: ?>
                                            <option value="<?php echo htmlspecialchars($i->id) ?>"><?php echo htmlspecialchars($i->name, ENT_QUOTES, 'UTF-8') ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                <?php endif ?>

                            </select>
                        </div>


                        <!-- Nút hành động -->
                        <div class="mt-4">
                            <button class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <a href="/project1/product/Index" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php else : ?>

<?php endif ?>

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
<?php include 'app/views/shares/footer.php' ?>