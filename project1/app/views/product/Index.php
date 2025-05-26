<?php include 'app/views/shares/header.php'; ?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}

.category-card {
    transition: all 0.3s ease;
    border-radius: 10px;
    overflow: hidden;
    background: white;
}

.category-card:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.product-card {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
}

.product-card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.product-card .badge-discount {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
}

.btn-cta {
    background: #28a745;
    border: none;
    padding: 15px 40px;
    font-size: 1.2rem;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-cta:hover {
    background: #218838;
    transform: scale(1.05);
}

.masonry-grid {
    column-count: 1;
    column-gap: 1.5rem;
}

@media (min-width: 768px) {
    .masonry-grid {
        column-count: 2;
    }
}

@media (min-width: 992px) {
    .masonry-grid {
        column-count: 3;
    }
}

.masonry-item {
    break-inside: avoid;
    margin-bottom: 1.5rem;
}
</style>



<!-- Categories Section -->
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Danh mục nổi bật</h2>
        <a href="/project1/category" class="btn btn-outline-dark rounded-pill">Xem tất cả</a>
    </div>
    <div class="row g-3">
        <?php if (!empty($theloai)): ?>
            <?php foreach ($theloai as $i): ?>
                <div class="col-6 col-md-3">
                    <a href="/project1/product/category/<?php echo $i->id; ?>" class="text-decoration-none">
                        <div class="category-card text-center p-4">
                            <i class="fas <?php echo $i->icon ?? 'fa-tag'; ?> fa-2x mb-3 text-dark"></i>
                            <h6 class="mb-1"><?php echo htmlspecialchars($i->name, ENT_QUOTES, 'UTF-8') ?></h6>
                            <small class="text-muted"><?php echo $i->product_count ?? 0; ?> sản phẩm</small>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-4">
                <p class="text-muted">Chưa có danh mục nào</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Products -->
<section class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Sản phẩm nổi bật</h2>
        <div class="d-flex gap-2">
            <a href="/project1/product/add" class="btn btn-dark rounded-pill">
                <i class="fas fa-plus me-1"></i> Thêm sản phẩm
            </a>
            <a href="/project1/product" class="btn btn-outline-dark rounded-pill">
                Xem tất cả
            </a>
        </div>
    </div>

    <div class="masonry-grid">
        <?php if (!empty($ds)): ?>
            <?php foreach ($ds as $j): ?>
                <div class="masonry-item">
                    <div class="product-card p-3">
                        <div class="position-relative mb-3">
                            <?php if ($j->image != null): ?>
                                <img src="<?php echo $j->image; ?>"
                                     class="img-fluid rounded w-100"
                                     alt="<?php echo htmlspecialchars($j->name, ENT_QUOTES, 'UTF-8'); ?>"
                                     style="height: <?php echo rand(200, 300); ?>px; object-fit: cover;">
                            <?php else: ?>
                                <img src="https://picsum.photos/id/<?php echo ($j->id + 109); ?>/600/400"
                                     class="img-fluid rounded w-100"
                                     alt="<?php echo htmlspecialchars($j->name, ENT_QUOTES, 'UTF-8'); ?>"
                                     style="height: <?php echo rand(200, 300); ?>px; object-fit: cover;">
                            <?php endif ?>
                            <span class="badge-discount">Giảm 20%</span>
                        </div>
                        <h5 class="mb-1"><?php echo htmlspecialchars($j->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                        <div class="mb-2">
                            <span class="text-dark fw-bold"><?php echo number_format($j->price, 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="/project1/product/Detail/<?php echo $j->id; ?>"
                                   class="btn btn-sm btn-outline-dark"
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/project1/product/edit/<?php echo $j->id; ?>"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/project1/product/delete/<?php echo $j->id; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Xóa sản phẩm"
                                   onclick="XoaSanPham(<?php echo $j->id; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <a href="/project1/product/addToCart/<?php echo $j->id; ?>" class="btn btn-sm btn-outline-success" title="Thêm vào giỏ">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Chưa có sản phẩm nào
                </div>
                <a href="/project1/product/add" class="btn btn-dark rounded-pill">
                    <i class="fas fa-plus me-2"></i> Thêm sản phẩm mới
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function XoaSanPham(id) {
    if (confirm("Bạn có muốn xóa sản phẩm với id " + id)) {
        window.location.href = "/project1/Product/Delete/" + id;
    }
}
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'app/views/shares/footer.php'; ?>