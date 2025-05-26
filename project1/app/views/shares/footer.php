<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoppy - Cửa hàng trực tuyến</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
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

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: var(--dark-color);
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
        }

        .category-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .product-placeholder {
            height: 150px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .badge-discount {
            background: #f56565;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8rem;
        }

        .footer {
            background: var(--dark-color);
            color: #a0aec0;
            padding: 3rem 0;
        }

        .footer a {
            color: #a0aec0;
            text-decoration: none;
        }

        .footer a:hover {
            color: white;
        }

        .footer .input-group .form-control {
            border-radius: 50px 0 0 50px;
            border: none;
        }

        .footer .input-group .btn {
            border-radius: 0 50px 50px 0;
        }

        .notification {
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease-out;
        }

        .notification.success { background: #48bb78; }
        .notification.error { background: #f56565; }
        .notification.warning { background: #ecc94b; color: #1a202c; }
        .notification.info { background: #4299e1; }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <h5><i class="fas fa-shopping-bag me-2"></i>CCC</h5>
                    <p>Nền tảng mua sắm trực tuyến hàng đầu Việt Nam</p>
                    <div class="d-flex gap-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <h5>Về chúng tôi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Giới thiệu</a></li>
                        <li class="mb-2"><a href="#">Tuyển dụng</a></li>
                        <li class="mb-2"><a href="#">Hệ thống cửa hàng</a></li>
                        <li class="mb-2"><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Hỗ trợ khách hàng</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#">Trung tâm trợ giúp</a></li>
                        <li class="mb-2"><a href="#">Hướng dẫn mua hàng</a></li>
                        <li class="mb-2"><a href="#">Chính sách đổi trả</a></li>
                        <li class="mb-2"><a href="#">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Đăng ký nhận tin</h5>
                    <p>Nhận thông báo về sản phẩm mới và ưu đãi</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email của bạn" id="newsletterEmail">
                        <button class="btn btn-primary" onclick="subscribeNewsletter()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">© 2025 Shoppy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function XoaSanPham(id) {
            if (confirm("Bạn có muốn xóa sản phẩm với id " + id)) {
                window.location.href = "/project1/Product/Delete/" + id;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($SuccessMessage)): ?>
                showNotification('success', '<?php echo addslashes($SuccessMessage) ?>');
            <?php endif; ?>
            <?php if (!empty($ErrorMessage)): ?>
                showNotification('error', '<?php echo addslashes($ErrorMessage) ?>');
            <?php endif; ?>
            <?php if (!empty($WarningMessage)): ?>
                showNotification('warning', '<?php echo addslashes($WarningMessage) ?>');
            <?php endif; ?>
            <?php if (!empty($InfoMessage)): ?>
                showNotification('info', '<?php echo addslashes($InfoMessage) ?>');
            <?php endif; ?>
        });

        function showNotification(type, message) {
            const notificationArea = document.getElementById('notification-area');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<i class="fas ${getIconForType(type)} me-2"></i>${message}`;
            notificationArea.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 5000);

            notification.addEventListener('click', () => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        }

        function getIconForType(type) {
            switch (type) {
                case 'success': return 'fa-check-circle';
                case 'error': return 'fa-times-circle';
                case 'warning': return 'fa-exclamation-triangle';
                case 'info': return 'fa-info-circle';
                default: return 'fa-info-circle';
            }
        }

        function subscribeNewsletter() {
            const emailInput = document.getElementById('newsletterEmail');
            const email = emailInput.value.trim();
            if (!email) {
                showNotification('warning', 'Vui lòng nhập email!');
                return;
            }
            if (!/\S+@\S+\.\S+/.test(email)) {
                showNotification('error', 'Email không hợp lệ!');
                return;
            }
            showNotification('success', 'Đăng ký nhận tin thành công!');
            emailInput.value = '';
        }
    </script>
</body>
</html>