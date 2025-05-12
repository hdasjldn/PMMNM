<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #e9f5ff 0%, #f4f6f9 100%);
            font-family: 'Roboto', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            padding: 40px 20px;
        }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 20px 30px;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-left: 6px solid #1a3c34;
            position: relative;
            overflow: hidden;
        }
        .header-actions::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(26, 60, 52, 0.05), rgba(39, 174, 96, 0.05));
            z-index: 0;
        }
        .header-actions h1 {
            color: #1a3c34;
            font-weight: 700;
            font-size: 2rem;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        .header-actions a {
            position: relative;
            z-index: 1;
        }
        .product-card {
            background: #ffffff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 200px; /* Reduced minimum height for shorter cards */
        }
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #1a3c34, #27ae60);
            z-index: 1;
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
        }
        .product-card .card-body {
            padding: 20px; /* Reduced padding for shorter appearance */
            
        }
        .product-card .card-title {
            color: #2c3e50;
            font-size: 1.4rem; /* Reduced font size */
            font-weight: 600;
            margin-bottom: 15px;
            background: #f0faff;
            padding: 6px 12px; /* Reduced padding */
            border-radius: 8px;
            display: inline-block;
        }
        .product-card .card-text {
            color: #6c757d;
            font-size: 0.85rem; /* Reduced font size */
            line-height: 1.6;
            background: #f8f9fa;
            padding: 6px 12px; /* Reduced padding */
            border-radius: 8px;
            border-left: 3px solid #27ae60;
            margin-bottom: 15px;
        }
        .product-card .card-footer {
            background: #ffffff;
            border-top: none;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between; /* Split price and buttons */
            align-items: center;
        }
        .product-card .price-tag {
            font-weight: 700;
            color: #e67e22;
            font-size: 1.1rem; /* Reduced font size */
            background: #fff3e6;
            padding: 5px 10px; /* Reduced padding */
            border-radius: 6px;
            display: inline-block;
            border-left: 3px solid #e67e22;
            margin: 0; /* Removed margin */
            
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons .btn-custom {
            border-radius: 10px;
            padding: 6px 15px; /* Reduced padding */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-size: 0.8rem; /* Reduced font size */
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-success {
            background-color: #27ae60;
            border-color: #27ae60;
        }
        .btn-success:hover {
            background-color: #219653;
            border-color: #219653;
            box-shadow: 0 4px 12px rgba(33, 150, 83, 0.3);
        }
        .btn-outline-primary {
            border-color: #1a3c34;
            color: #1a3c34;
        }
        .btn-outline-primary:hover {
            background-color: #1a3c34;
            border-color: #1a3c34;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(26, 60, 52, 0.3);
        }
        .card-body:hover{
            border-color: #1abc9c;
        }
        .btn-outline-danger {
            border-color: #c0392b;
            color: #c0392b;
        }
        .btn-outline-danger:hover {
            background-color: #c0392b;
            border-color: #c0392b;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(192, 57, 43, 0.3);
        }
        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            .product-card .card-title {
                font-size: 1.2rem;
            }
            .product-card .price-tag {
                font-size: 0.9rem;
            }
            .product-card .card-footer {
                flex-wrap: wrap; /* Allows wrapping on small screens */
                justify-content: center;
            }
            .product-card .price-tag {
                margin-bottom: 10px; /* Adds spacing when wrapped */
            }
            .action-buttons .btn-custom {
                font-size: 0.75rem; /* Further reduced on small screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-actions">
            <h1>Danh sách sản phẩm</h1>
            <a href="/project1/Product/add" class="btn btn-success btn-custom">
                <i class="bi bi-plus-circle me-1"></i> Thêm sản phẩm mới
            </a>
        </div>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card product-card h-100">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p class="card-text"><?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="card-footer action-buttons">
                            <p class="price-tag">Giá: <?php echo number_format($product->getPrice(), 0, ',', '.') . ' VND'; ?></p>
                            <div class="action-buttons">
                                <a href="/project1/Product/edit/<?php echo $product->getID(); ?>" class="btn btn-outline-primary btn-custom btn-sm">
                                    <i class="bi bi-pencil me-1"></i> Sửa
                                </a>
                                <a href="/project1/Product/delete/<?php echo $product->getID(); ?>" 
                                   class="btn btn-outline-danger btn-custom btn-sm" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    <i class="bi bi-trash me-1"></i> Xóa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>