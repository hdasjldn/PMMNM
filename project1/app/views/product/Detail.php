<?php include 'app/views/shares/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary-color: #5a67d8;
    --secondary-color: #7f9cf5;
    --dark-color: #1a202c;
    --light-color: #edf2f7;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--light-color);
}

.product-detail-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.product-detail-card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.product-placeholder {
    height: 200px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
}

.badge-discount {
    background: #f56565;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.9rem;
}

.btn-primary {
    background: var(--primary-color);
    border: none;
    border-radius: 50px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: var(--secondary-color);
}

.btn-warning, .btn-danger, .btn-secondary {
    border-radius: 50px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: #d69e2e;
    border-color: #d69e2e;
}

.btn-danger:hover {
    background: #c53030;
    border-color: #c53030;
}

.btn-secondary:hover {
    background: #4a5568;
    border-color: #4a5568;
}
</style>

<?php
// Kiểm tra và gán giá trị mặc định nếu $sp không tồn tại
$sp = $sp ?? null;
?>

<?php if ($sp): ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="product-detail-card">
                    <!-- Hiển thị hình ảnh sản phẩm -->
                    <div class="mb-4">
                        <?php if (!empty($sp->image)): ?>
                            <img src="  <?php echo htmlspecialchars($sp->image, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($sp->name ?? 'Sản phẩm không tên', ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="product-placeholder">
                                Hình ảnh không có
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thông tin chi tiết -->
                    <div class="text-center text-md-start">
                        <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($sp->name ?? 'Sản phẩm không tên', ENT_QUOTES, 'UTF-8'); ?></h2>
                        <div class="d-flex align-items-center mb-3">
                            <h4 class="text-danger me-3">
                                <?php echo number_format($sp->price ?? 0, 0, ',', '.'); ?> đ
                            </h4>
                            <span class="badge-discount">Giảm 20%</span>
                        </div>
                        <p class="text-muted mb-3" style="white-space: pre-line;">
                            <?php echo htmlspecialchars($sp->description ?? 'Chưa có mô tả', ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p class="text-muted mb-2"><strong>Tình trạng:</strong> <?php echo isset($sp->stock) && $sp->stock > 0 ? 'Còn hàng' : 'Hết hàng'; ?></p>
                        <?php if (isset($sp->category)): ?>
                            <p class="text-muted mb-4"><strong>Danh mục:</strong> <?php echo htmlspecialchars($sp->category ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endif; ?>

                        <!-- Nút hành động -->
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/project1/product/edit/<?php echo $sp->ID ?? 0; ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Sửa
                            </a>
                            <a href="/project1/product/delete/<?php echo $sp->ID ?? 0; ?>" class="btn btn-danger" onclick="XoaSanPham(<?php echo $sp->ID ?? 0; ?>)">
                                <i class="fas fa-trash-alt me-1"></i> Xóa
                            </a>
                            <a href="/project1/product/Index" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container my-5 text-center">
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i> Không tìm thấy sản phẩm hoặc lỗi truy cập.
        </div>
        <a href="/project1/product/Index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
<?php endif; ?>

<script>
function XoaSanPham(id) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này không?')) {
        window.location.href = '/project1/product/delete/' + id;
    }
    return false; // Ngăn hành vi mặc định của link
}
</script>

<?php include 'app/views/shares/footer.php'; ?>