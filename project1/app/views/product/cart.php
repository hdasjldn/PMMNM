<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5 p-4 bg-light rounded shadow-sm">
    <h1 class="mb-4 text-center text-primary fw-bold">Giỏ Hàng Của Bạn</h1>

    <?php if (!empty($cart)): ?>
        <form action="/project1/product/checkout" method="post" id="cartForm">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th style="width: 5%;"><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                            <th style="width: 20%;">Sản Phẩm</th>
                            <th style="width: 15%;">Hình Ảnh</th>
                            <th style="width: 15%;">Giá (VND)</th>
                            <th style="width: 15%;">Số Lượng</th>
                            <th style="width: 15%;">Tổng Tiền (Sản Phẩm)</th>
                            <th style="width: 15%;">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_products[]" value="<?php echo htmlspecialchars($id); ?>" class="form-check-input product-checkbox">
                                </td>
                                <td class="align-middle"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="align-middle">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="ProductImage" class="img-fluid rounded" style="max-width: 80px;">
                                    <?php else: ?>
                                        <span class="text-muted">Không có hình ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle"><?php echo htmlspecialchars(number_format($item['price'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="align-middle"><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="align-middle text-success fw-bold"><?php echo htmlspecialchars(number_format($cartTotals[$id], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="align-middle">
                                    <form action="/project1/product/removeFromCart" method="post" style="display:inline;">
                                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($id); ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4 p-3 bg-white rounded shadow-sm">
                <h3 class="text-success fw-bold">Tổng tiền giỏ hàng: <span><?php echo htmlspecialchars(number_format($overallTotal, 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VND</span></h3>
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary btn-lg" onclick="return validateSelection()">Thanh Toán Sản Phẩm Đã Chọn</button>
                    <a href="/project1/product" class="btn btn-outline-secondary btn-lg">Tiếp Tục Mua Sắm</a>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info text-center p-4" role="alert">
            <h4 class="mb-3">Giỏ hàng của bạn đang trống!</h4>
            <p>Thêm sản phẩm để bắt đầu mua sắm.</p>
            <a href="/project1/product" class="btn btn-primary mt-3">Mua Sắm Ngay</a>
        </div>
    <?php endif; ?>
</div>

<!-- JavaScript for Select All and Validation -->
<script>
function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = source.checked;
    });
}

function validateSelection() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    if (!isAnyChecked) {
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        return false;
    }
    return true;
}
</script>

<style>
.container {
    max-width: 1000px;
}
.table th, .table td {
    vertical-align: middle;
    text-align: center;
}
.table thead {
    background-color: #343a40;
    color: white;
}
.table-hover tbody tr:hover {
    background-color: #e9ecef;
}
.text-success {
    color: #28a745;
}
.alert-info {
    background-color: #e9ecef;
    border: none;
    font-size: 1.1rem;
}
.btn-lg {
    padding: 10px 20px;
    font-size: 1rem;
}
.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}
.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: white;
}
.shadow-sm {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.text-end {
    border-top: 1px solid #dee2e6;
}
</style>
<?php include 'app/views/shares/footer.php'; ?>