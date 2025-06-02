<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web bán hàng ccc</title>
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

        .banner-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .banner-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
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

        .dropdown-menu {
            border: 1px solid var(--light-color);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        .dropdown-item:hover {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Notification Area -->
    <div id="notification-area" style="position: fixed; top: 20px; right: 20px; z-index: 1000;"></div>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-shopping-bag me-2"></i>ccc</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project1/Product">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/project1/Category">Danh mục</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Giới thiệu</a>
                    </li> -->
                </ul>
                <div class="d-flex align-items-center">
                    <!-- <form class="input-group me-3" style="width: 250px;" action="/project1/product/search" method="GET">
                        <input type="text" class="form-control" name="query" placeholder="Tìm kiếm...">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                    </form> -->
                    <div class="dropdown me-2">
                        <?php if (SessionHelper::isLoggedIn()): ?>
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <!-- <li><a class="dropdown-item" href="/project1/account/profile">Hồ sơ</a></li> -->
                                <?php if (SessionHelper::isAdmin()): ?>
                                    <!-- <li><a class="dropdown-item" href="/project1/product/add">Thêm sản phẩm</a></li> -->
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="/project1/account/logout">Đăng xuất</a></li>
                            </ul>
                        <?php else: ?>
                            <a class="btn btn-outline-primary" href="/project1/account/login"><i class="fas fa-user me-1"></i>Đăng nhập</a>
                        <?php endif; ?>
                    </div>
                    <button class="btn btn-outline-primary me-2"><i class="fas fa-heart"></i></button>
                    <button class="btn btn-outline-primary position-relative">
                        <a class="fas fa-shopping-cart" href="/project1/product/cart/"></a>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
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