<?php include 'app/views/shares/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root {
    --primary-color: #5a67d8;
    --secondary-color: #7f9cf5;
    --dark-color: #1a202c;
    --light-color: #edf2f7;
    --success-color: #28a745;
    --danger-color: #dc3545;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: var(--light-color);
}

.product-detail-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.product-detail-card:hover {
    box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}

.product-image {
    max-height: 350px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.02);
}

.product-placeholder {
    height: 350px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.6rem;
}

.badge-discount {
    background: #f56565;
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 500;
}

.btn-primary {
    background: var(--primary-color);
    border: none;
    border-radius: 50px;
    padding: 12px 28px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-warning, .btn-danger, .btn-secondary {
    border-radius: 50px;
    padding: 12px 28px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: #d69e2e;
    border-color: #d69e2e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-danger:hover {
    background: #c53030;
    border-color: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-secondary:hover {
    background: #4a5568;
    border-color: #4a5568;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.text-muted {
    color: #6b7280 !important;
    line-height: 1.6;
}

.alert-warning {
    background-color: #fefcbf;
    border: none;
    border-radius: 12px;
    padding: 2rem;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

<?php if ($sp && property_exists($sp, 'id')): ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="product-detail-card">
                    <!-- Hiển thị hình ảnh sản phẩm -->
                    <div class="mb-5 text-center">
                        <?php if (!empty($sp->image)): ?>
                            <img src="<?php echo htmlspecialchars($sp->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                 alt="<?php echo htmlspecialchars($sp->name ?? 'Sản phẩm không tên', ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="img-fluid product-image">
                        <?php else: ?>
                            <div class="product-placeholder">
                                Hình ảnh không có
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Thông tin chi tiết -->
                    <div class="text-center text-md-start">
                        <h2 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($sp->name ?? 'Sản phẩm không tên', ENT_QUOTES, 'UTF-8'); ?></h2>
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-4">
                            <h4 class="text-danger me-3">
                                <?php echo number_format($sp->price ?? 0, 0, ',', '.'); ?> đ
                            </h4>
                            <span class="badge-discount">Giảm 20%</span>
                        </div>
                        <p class="text-muted mb-4" style="white-space: pre-line;">
                            <?php echo htmlspecialchars($sp->description ?? 'Chưa có mô tả', ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p class="text-muted mb-3"><strong>Tình trạng:</strong> 
                            <span class="<?php echo ($sp->stock > 0) ? 'text-success' : 'text-danger'; ?>">
                                <?php echo ($sp->stock > 0) ? 'Còn hàng' : 'Hết hàng'; ?>
                            </span>
                        </p>
                        <?php if (property_exists($sp, 'category_name')): ?>
                            <p class="text-muted mb-4"><strong>Danh mục:</strong> 
                                <?php echo htmlspecialchars($sp->category_name ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        <?php else: ?>
                            <p class="text-muted mb-4"><strong>Danh mục:</strong> Chưa phân loại</p>
                        <?php endif; ?>

                        <!-- Nút hành động -->
                        <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
                            <a href="/project1/product/edit/<?php echo htmlspecialchars($sp->id ?? 0, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Sửa
                            </a>
                            <a href="/project1/product/delete/<?php echo htmlspecialchars($sp->id ?? 0, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-danger" onclick="return XoaSanPham(<?php echo htmlspecialchars($sp->id ?? 0, ENT_QUOTES, 'UTF-8'); ?>)">
                                <i class="fas fa-trash-alt me-1"></i> Xóa
                            </a>
                            <a href="/project1/product/index" class="btn btn-secondary">
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
        <a href="/project1/product/index" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
<?php endif; ?>

<script>
function XoaSanPham(id) {
    if (confirm('Bạn có chắc muốn xóa sản phẩm này không?')) {
        return true;
    }
    return false;
}
</script>

<?php include 'app/views/shares/footer.php'; ?>