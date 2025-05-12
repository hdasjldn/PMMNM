<!DOCTYPE html>
<html>
<head>
    <title>Thêm sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .error-list {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .form-label {
            font-weight: bold;
        }
    </style>
    <script>
        function validateForm() {
            let name = document.getElementById('name').value;
            let price = document.getElementById('price').value;
            let errors = [];
            if (name.length < 10 || name.length > 100) {
                errors.push('Tên sản phẩm phải có từ 10 đến 100 ký tự.');
            }
            if (price <= 0 || isNaN(price)) {
                errors.push('Giá phải là một số dương lớn hơn 0.');
            }
            if (errors.length > 0) {
                alert(errors.join('\n'));
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1 class="text-center my-4">Thêm sản phẩm mới</h1>
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <ul class="list-unstyled error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <form method="POST" action="/project1/Product/add" onsubmit="return validateForm();">
                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả:</label>
                    <textarea id="description" name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Giá:</label>
                    <input type="number" id="price" name="price" step="0.01" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            </form>
            <a href="/project1/Product/list" class="btn btn-secondary mt-3">Quay lại danh sách sản phẩm</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>