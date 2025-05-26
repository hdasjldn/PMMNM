<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5 p-4 bg-light rounded shadow-sm">
    <h1 class="mb-4 text-center text-success fw-bold">Xác Nhận Đơn Hàng</h1>
    <div class="text-center p-4 bg-white rounded shadow-sm">
        <div class="mb-4">
            <svg width="64" height="64" fill="#28a745" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>
        <p class="lead text-muted">Cảm ơn bạn đã đặt hàng! Đơn hàng của bạn đã được xử lý thành công.</p>
        <p class="text-secondary">Chúng tôi sẽ sớm liên hệ với bạn để xác nhận chi tiết.</p>
        <div class="mt-4">
            <a href="/project1/product/index" class="btn btn-primary btn-lg px-4">Tiếp Tục Mua Sắm</a>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 600px;
}
.bg-light {
    background-color: #f8f9fa;
}
.text-success {
    color: #28a745;
}
.text-muted {
    font-size: 1.2rem;
    color: #6c757d;
}
.text-secondary {
    font-size: 1rem;
    color: #6c757d;
}
.btn-lg {
    padding: 10px 20px;
    font-size: 1rem;
}
.shadow-sm {
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
</style>
<?php include 'app/views/shares/footer.php'; ?>