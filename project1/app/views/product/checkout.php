<?php include 'app/views/shares/header.php'; ?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}

.product-card {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-card:hover {
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table th {
    background-color: #343a40;
    color: white;
    font-weight: 600;
    text-align: center;
    padding: 15px;
}

.table td {
    vertical-align: middle;
    text-align: center;
    padding: 15px;
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f1f3f5;
}

.form-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.form-control, .form-control:focus {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 10px;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
}

.btn-primary {
    background: #28a745;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: #218838;
    transform: scale(1.05);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: scale(1.05);
}

.text-success {
    font-weight: bold;
    color: #28a745;
}

h1 {
    font-weight: 700;
    color: #343a40;
}

.alert-info, .alert-warning {
    border-radius: 8px;
    font-size: 1.1rem;
}
</style>

<div class="container my-5">
    <h1 class="mb-5 text-center">Thanh Toán</h1>

    <!-- Container for Error Message (to be populated by JavaScript) -->
    <div id="error-message-container" class="mb-4"></div>

    <!-- Checkout Content -->
    <div id="checkout-content">
        <?php if (!empty($_SESSION['selected_checkout'])): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sản Phẩm</th>
                            <th>Giá (VND)</th>
                            <th>Số Lượng</th>
                            <th>Tổng Tiền</th>
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
            <h3 class="text-end mt-4">Tổng tiền thanh toán: <span class="text-success"><?php echo htmlspecialchars(number_format($_SESSION['selected_total'], 0, ',', '.'), ENT_QUOTES, 'UTF-8'); ?> VND</span></h3>

            <div class="form-card">
                <form action="/project1/product/processCheckout" method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ:</label>
                        <textarea class="form-control" id="address" name="address" rows="4" required></textarea>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Xác Nhận Đặt Hàng</button>
                        <a href="/project1/product/cart" class="btn btn-secondary ms-2">Quay Lại Giỏ Hàng</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="product-card p-4 text-center" style="max-width: 500px; margin: 0 auto;">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Không có sản phẩm nào được chọn để thanh toán.
                </div>
                <a href="/project1/product/cart" class="btn btn-primary">Quay lại giỏ hàng</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Pass PHP $ErrorMessage to JavaScript
const errorMessage = <?php echo json_encode($_SESSION["ErrorMessage"] ?? ''); ?>;

function ShowErrorMessage(message) {
    if (!message) return;

    const errorContainer = document.getElementById('error-message-container');
    
    // Create the centered card
    const card = document.createElement('div');
    card.className = 'd-flex justify-content-center align-items-center';

    const cardContent = document.createElement('div');
    cardContent.className = 'product-card p-4 text-center';
    cardContent.style.maxWidth = '500px';

    const alert = document.createElement('div');
    alert.className = 'alert alert-warning mb-3';
    alert.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>' + 
                     message.replace(/</g, '<').replace(/>/g, '>');

    const button = document.createElement('a');
    button.href = '/project1/product/cart';
    button.className = 'btn btn-primary';
    button.innerHTML = '<i class="fas fa-arrow-left me-2"></i> Quay lại giỏ hàng';

    cardContent.appendChild(alert);
    cardContent.appendChild(button);
    card.appendChild(cardContent);
    errorContainer.appendChild(card);

    // Hide checkout content
    document.getElementById('checkout-content').style.display = 'none';
}

// Call the function if there's an error message
ShowErrorMessage(errorMessage);

<?php unset($_SESSION["ErrorMessage"]); ?>
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'app/views/shares/footer.php'; ?>