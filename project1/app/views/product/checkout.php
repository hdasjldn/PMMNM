<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4 text-center">Thanh Toán</h1>

    <?php if (isset($_SESSION["ErrorMessage"])): ?>
        <div class="alert alert-danger text-center">
            <?php echo htmlspecialchars($_SESSION["ErrorMessage"], ENT_QUOTES, 'UTF-8'); ?>
            <?php unset($_SESSION["ErrorMessage"]); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['selected_checkout'])): ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Sản Phẩm</th>
                        <th>Giá (VND)</th>
                        <th>Số Lượng</th>
                        <th>Tổng Tiền (Sản Phẩm)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['selected_checkout'] as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars(number_format($item['price'] * $item['quantity'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h3 class="text-right mt-3">Tổng tiền thanh toán: <span class="text-success"><?php echo htmlspecialchars(number_format($_SESSION['selected_total'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VND</span></h3>

        <form action="/project1/product/processCheckout" method="post" class="mt-4">
            <div class="form-group">
                <label for="name">Tên:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-primary btn-lg">Xác Nhận Đặt Hàng</button>
                <a href="/project1/product/cart" class="btn btn-secondary btn-lg ml-2">Quay Lại Giỏ Hàng</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <p>Không có sản phẩm nào được chọn để thanh toán.</p>
            <a href="/project1/product/cart" class="btn btn-primary">Quay lại giỏ hàng</a>
        </div>
    <?php endif; ?>
</div>

<style>
.table th, .table td {
    vertical-align: middle;
    text-align: center;
}
.table th {
    background-color: #343a40;
    color: white;
}
.text-success {
    font-weight: bold;
}
.alert-info {
    font-size: 1.1rem;
}
.btn-lg {
    padding: 10px 20px;
}
</style>
<?php include 'app/views/shares/footer.php'; ?>