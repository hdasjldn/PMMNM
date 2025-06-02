<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/ProductModel.php');
require_once('app/helpers/SessionHelper.php');

class productController
{
    private $db;
    private $ProductModel;
    private $CategoryModel;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->ProductModel = new ProductModel($this->db);
        $this->CategoryModel = new CategoryModel($this->db);
    }

    // Kiểm tra quyền Admin
    private function isAdmin() {
        $isAdmin = SessionHelper::isAdmin();
        // error_log("isAdmin check at " . date('Y-m-d H:i:s') . ": " . ($isAdmin ? "true" : "false") . ", Session: " . print_r($_SESSION, true), 3, '/tmp/product_controller.log');
        return $isAdmin;
    }

    // Verify CSRF token (temporary debug mode)
    private function verifyCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            error_log("CSRF token not set in session at " . date('Y-m-d H:i:s'), 3, '/tmp/product_controller.log');
            return true; // Skip check for debugging
        }
        $postToken = $_POST['csrf_token'] ?? 'none';
        error_log("CSRF check at " . date('Y-m-d H:i:s') . ": POST token=$postToken, Session token={$_SESSION['csrf_token']}", 3, '/tmp/product_controller.log');
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION["ErrorMessage"] = "Yêu cầu không hợp lệ (CSRF token mismatch)";
            header('Location: /project1/product');
            exit();
        }
    }

    public function Index()
    {
        $SuccessMessage = isset($_SESSION["SuccessMessage"]) ? $_SESSION["SuccessMessage"] : null;
        unset($_SESSION["SuccessMessage"]);

        $ErrorMessage = isset($_SESSION["ErrorMessage"]) ? $_SESSION["ErrorMessage"] : null;
        unset($_SESSION["ErrorMessage"]);

        $WarningMessage = isset($_SESSION["WarningMessage"]) ? $_SESSION["WarningMessage"] : null;
        unset($_SESSION["WarningMessage"]);

        $InfoMessage = isset($_SESSION["InfoMessage"]) ? $_SESSION["InfoMessage"] : null;
        unset($_SESSION["InfoMessage"]);

        $ds = $this->ProductModel->DanhSachSanPham();
        $theloai = $this->CategoryModel->DanhSachTheLoai();

        include 'app/views/product/Index.php';
    }

    public function Detail($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION["ErrorMessage"] = "ID sản phẩm không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        $sp = $this->ProductModel->LaySanPhamTheoID($id);

        if ($sp) {
            $sp->stock = $sp->stock ?? 0;
            include 'app/views/Product/Detail.php';
        } else {
            $_SESSION["ErrorMessage"] = "Không tìm thấy sản phẩm";
            header('Location: /project1/product');
            exit();
        }
    }

    public function add()
    {
        if (!$this->isAdmin()) {
            $_SESSION["ErrorMessage"] = "Bạn không có quyền thêm sản phẩm";
            header('Location: /project1/product');
            exit();
        }
        $theloai = $this->CategoryModel->DanhSachTheLoai();
        include 'app/views/product/add.php';
    }

    private function SaveImage($imageFile, $subFolder)
    {
        if (!isset($imageFile) || $imageFile['error'] !== UPLOAD_ERR_OK || $imageFile['size'] == 0) {
            throw new Exception("File không hợp lệ!");
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageFile['type'], $allowedTypes)) {
            throw new Exception("Chỉ chấp nhận file JPEG, PNG hoặc GIF!");
        }

        $maxFileSize = 5 * 1024 * 1024;
        if ($imageFile['size'] > $maxFileSize) {
            throw new Exception("File quá lớn! Kích thước tối đa là 5MB.");
        }

        $uploadsFolder = __DIR__ . '/../../public/uploads/' . $subFolder;

        if (!file_exists($uploadsFolder)) {
            mkdir($uploadsFolder, 0777, true);
        }

        $fileExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $fileName = pathinfo($imageFile['name'], PATHINFO_FILENAME);
        $uniqueFileName = $fileName . '_' . uniqid() . '.' . $fileExtension;
        $filePath = $uploadsFolder . '/' . $uniqueFileName;

        if (!move_uploaded_file($imageFile['tmp_name'], $filePath)) {
            throw new Exception("Không thể lưu file!");
        }

        return '/project1/public/uploads/' . $subFolder . '/' . $uniqueFileName;
    }

    private function DeleteImage($imageURL, $subFolder)
    {
        if (empty($imageURL)) {
            return false;
        }

        $fileName = basename($imageURL);
        $filePath = __DIR__ . '/../../public/uploads/' . $subFolder . '/' . $fileName;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function SaveAdd()
    {
        if (!$this->isAdmin()) {
            $_SESSION["ErrorMessage"] = "Bạn không có quyền thêm sản phẩm";
            header('Location: /project1/product');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION["ErrorMessage"] = "Phương thức không hợp lệ";
            header('Location: /project1/product/add');
            exit();
        }

        $this->verifyCsrfToken();

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $des = filter_input(INPUT_POST, 'des', FILTER_SANITIZE_STRING);
        $categoryid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT) ?? 0;

        if (!$name || $price === false || !$categoryid) {
            $_SESSION["ErrorMessage"] = "Vui lòng điền đầy đủ thông tin hợp lệ";
            include 'app/views/product/add.php';
            return;
        }

        $hinhanh = null;

        try {
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
                $hinhanh = $this->SaveImage($_FILES['hinhanh'], 'san-pham');
            }

            $edit = $this->ProductModel->ThemSanPham($name, $price, $des, $categoryid, $hinhanh, $stock);
            error_log("ThemSanPham result at " . date('Y-m-d H:i:s') . ": " . ($edit ? "success" : "failed"), 3, '/tmp/product_controller.log');

            if ($edit) {
                $_SESSION["SuccessMessage"] = "Thêm thành công";
                header('Location: /project1/product');
                exit();
            } else {
                throw new Exception("Không thể thêm sản phẩm vào cơ sở dữ liệu");
            }
        } catch (Exception $e) {
            if ($hinhanh) {
                $this->DeleteImage($hinhanh, 'san-pham');
            }
            $_SESSION["ErrorMessage"] = $e->getMessage();
            error_log("SaveAdd error at " . date('Y-m-d H:i:s') . ": " . $e->getMessage(), 3, '/tmp/product_controller.log');
            include 'app/views/product/add.php';
        }
    }

    public function edit($id)
    {
        if (!$this->isAdmin()) {
            $_SESSION["ErrorMessage"] = "Bạn không có quyền chỉnh sửa sản phẩm";
            header('Location: /project1/product');
            exit();
        }

        if (!is_numeric($id) || $id <= 0) {
            $_SESSION["ErrorMessage"] = "ID sản phẩm không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        $sp = $this->ProductModel->LaySanPhamTheoID($id);
        if (!$sp) {
            $_SESSION["ErrorMessage"] = "Không tìm thấy sản phẩm";
            header('Location: /project1/product');
            exit();
        }

        $theloai = $this->CategoryModel->DanhSachTheLoai();
        include 'app/views/product/edit.php';
    }

    public function SaveEdit()
    {
        if (!$this->isAdmin()) {
            $_SESSION["ErrorMessage"] = "Bạn không có quyền chỉnh sửa sản phẩm";
            header('Location: /project1/product');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION["ErrorMessage"] = "Phương thức không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        $this->verifyCsrfToken();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $des = filter_input(INPUT_POST, 'des', FILTER_SANITIZE_STRING);
        $categoryid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT) ?? 0;

        if (!$id || !$name || $price === false || !$categoryid) {
            $_SESSION["ErrorMessage"] = "Vui lòng điền đầy đủ thông tin hợp lệ";
            include 'app/views/product/edit.php';
            return;
        }

        try {
            $hinhanh = null;
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] === UPLOAD_ERR_OK) {
                $sp = $this->ProductModel->LaySanPhamTheoID($id);
                if ($sp && $sp->hinhanh) {
                    $this->DeleteImage($sp->hinhanh, "san-pham");
                }
                $hinhanh = $this->SaveImage($_FILES['hinhanh'], 'san-pham');
            }

            $edit = $this->ProductModel->ChinhSuaSanPham($id, $name, $price, $des, $categoryid, $hinhanh, $stock);
            error_log("ChinhSuaSanPham result at " . date('Y-m-d H:i:s') . ": " . ($edit ? "success" : "failed"), 3, '/tmp/product_controller.log');

            if ($edit) {
                $_SESSION["SuccessMessage"] = "Cập nhật thành công";
                header('Location: /project1/product');
                exit();
            } else {
                throw new Exception("Không thể cập nhật sản phẩm");
            }
        } catch (Exception $e) {
            $_SESSION["ErrorMessage"] = $e->getMessage();
            error_log("SaveEdit error at " . date('Y-m-d H:i:s') . ": " . $e->getMessage(), 3, '/tmp/product_controller.log');
            include 'app/views/product/edit.php';
        }
    }

    public function Delete($id)
    {
        if (!$this->isAdmin()) {
            $_SESSION["ErrorMessage"] = "Bạn không có quyền xóa sản phẩm";
            header('Location: /project1/product');
            exit();
        }

        if (!is_numeric($id) || $id <= 0) {
            $_SESSION["ErrorMessage"] = "ID sản phẩm không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        $sp = $this->ProductModel->LaySanPhamTheoID($id);
        if ($sp && $sp->hinhanh) {
            $this->DeleteImage($sp->hinhanh, "san-pham");
        }

        $result = $this->ProductModel->XoaSanPham($id);
        error_log("XoaSanPham result at " . date('Y-m-d H:i:s') . ": " . ($result ? "success" : "failed"), 3, '/tmp/product_controller.log');

        if ($result) {
            $_SESSION["SuccessMessage"] = "Xóa thành công";
        } else {
            $_SESSION["ErrorMessage"] = "Xóa thất bại";
        }
        header('Location: /project1/product');
        exit();
    }

    public function addToCart($id)
    {
        $product = $this->ProductModel->LaySanPhamTheoID($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->hinhanh
            ];
        }

        header('Location: /project1/Product/cart');
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cartTotals = [];
        $overallTotal = 0;

        foreach ($cart as $id => $item) {
            $totalPerProduct = $item['price'] * $item['quantity'];
            $cartTotals[$id] = $totalPerProduct;
            $overallTotal += $totalPerProduct;
        }

        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $selectedProducts = [];
        $selectedTotal = 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
            $selectedIds = $_POST['selected_products'];
            foreach ($selectedIds as $id) {
                if (isset($cart[$id])) {
                    $selectedProducts[$id] = $cart[$id];
                    $selectedTotal += $cart[$id]['price'] * $cart[$id]['quantity'];
                }
            }

            $_SESSION['selected_checkout'] = $selectedProducts;
            $_SESSION['selected_total'] = $selectedTotal;
        } else {
            $_SESSION["ErrorMessage"] = "Vui lòng chọn ít nhất một sản phẩm để thanh toán.";
            header('Location: /project1/product/cart');
            exit();
        }

        include 'app/views/product/checkout.php';
    }

    public function updateCart($id, $action)
    {
        if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$id])) {
            header('Location: /project1/Product/cart');
            return;
        }

        if ($action == 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($action == 'decrease') {
            $_SESSION['cart'][$id]['quantity']--;

            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }

        header('Location: /project1/Product/cart');
    }

    public function processCheckout()
    {
        error_log("processCheckout method called at " . date('Y-m-d H:i:s') . " with REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'], 3, '/tmp/product_controller.log');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
            $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
            $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING));

            error_log("Received POST data: " . print_r($_POST, true), 3, '/tmp/product_controller.log');
            error_log("Session cart: " . print_r($_SESSION['cart'], true), 3, '/tmp/product_controller.log');

            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                $_SESSION["ErrorMessage"] = "Giỏ hàng trống.";
                error_log("Error: Cart is empty or not set.", 3, '/tmp/product_controller.log');
                header('Location: /project1/product/cart');
                exit();
            }

            if (empty($name) || empty($phone) || empty($address)) {
                $_SESSION["ErrorMessage"] = "Vui lòng điền đầy đủ thông tin (tên, số điện thoại, địa chỉ).";
                error_log("Error: Missing required fields - name: $name, phone: $phone, address: $address", 3, '/tmp/product_controller.log');
                header('Location: /project1/product/checkout');
                exit();
            }

            $cart = $_SESSION['cart'];
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            error_log("Calculated total price: $total", 3, '/tmp/product_controller.log');

            $this->db->beginTransaction();

            try {
                $query = "INSERT INTO orders (name, phone, address, total) VALUES (:name, :phone, :address, :total)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':total', $total, PDO::PARAM_STR);
                if (!$stmt->execute()) {
                    throw new Exception("Lỗi khi lưu đơn hàng: " . print_r($stmt->errorInfo(), true));
                }

                $order_id = $this->db->lastInsertId();
                error_log("Order inserted, order_id: $order_id", 3, '/tmp/product_controller.log');

                unset($_SESSION['cart']);
                $this->db->commit();
                error_log("Checkout completed, order_id: $order_id, total: $total", 3, '/tmp/product_controller.log');
                header('Location: /project1/product/orderConfirmation');
                exit();
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION["ErrorMessage"] = "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
                error_log("Checkout error: " . $e->getMessage(), 3, '/tmp/product_controller.log');
                header('Location: /project1/product/checkout');
                exit();
            }
        } else {
            $_SESSION["ErrorMessage"] = "Yêu cầu không hợp lệ, chỉ chấp nhận POST.";
            error_log("Non-POST request received, method: " . $_SERVER['REQUEST_METHOD'], 3, '/tmp/product_controller.log');
            header('Location: /project1/product/checkout');
            exit();
        }
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            $_SESSION["ErrorMessage"] = "Yêu cầu không hợp lệ";
            header('Location: /project1/product/cart');
            exit();
        }

        $this->verifyCsrfToken();

        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        if ($product_id && isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }

        header('Location: /project1/product/cart');
        exit();
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>  