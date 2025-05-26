<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/ProductModel.php');

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
        var_dump($sp); // Debug output
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
        $theloai = $this->CategoryModel->DanhSachTheLoai();
        include 'app/views/product/add.php';
    }

    private function SaveImage($imageFile, $subFolder)
    {
        if (!isset($imageFile) || $imageFile['error'] !== UPLOAD_ERR_OK || $imageFile['size'] == 0) {
            throw new Exception("File không hợp lệ!");
        }

        // Validate image type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageFile['type'], $allowedTypes)) {
            throw new Exception("Chỉ chấp nhận file JPEG, PNG hoặc GIF!");
        }

        // Validate file size (e.g., max 5MB)
        $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION["ErrorMessage"] = "Phương thức không hợp lệ";
            header('Location: /project1/product/add');
            exit();
        }

        // Validate inputs
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $des = filter_input(INPUT_POST, 'des', FILTER_SANITIZE_STRING);
        $categoryid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT) ?? 0; // Add stock input

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
            include 'app/views/product/add.php';
        }
    }

    public function edit($id)
    {
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION["ErrorMessage"] = "Phương thức không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        // Validate inputs
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $des = filter_input(INPUT_POST, 'des', FILTER_SANITIZE_STRING);
        $categoryid = filter_input(INPUT_POST, 'categoryid', FILTER_VALIDATE_INT);
        $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT) ?? 0; // Add stock input

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

            if ($edit) {
                $_SESSION["SuccessMessage"] = "Cập nhật thành công";
                header('Location: /project1/product');
                exit();
            } else {
                throw new Exception("Không thể cập nhật sản phẩm");
            }
        } catch (Exception $e) {
            $_SESSION["ErrorMessage"] = $e->getMessage();
            include 'app/views/product/edit.php';
        }
    }

    public function Delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION["ErrorMessage"] = "ID sản phẩm không hợp lệ";
            header('Location: /project1/product');
            exit();
        }

        $sp = $this->ProductModel->LaySanPhamTheoID($id);
        if ($sp && $sp->hinhanh) {
            $this->DeleteImage($sp->hinhanh, "san-pham");
        }

        if ($this->ProductModel->XoaSanPham($id)) {
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
            'image' => $product->image // Note: Check if 'image' is correct or should be 'hinhanh'
        ];
    }

    // // Debug: Print the cart contents
    // var_dump($_SESSION['cart']);
    // exit; // Temporarily stop execution to inspect the cart

    header('Location: /project1/Product/cart');
}
public function cart()
{
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    $cartTotals = [];
    $overallTotal = 0;

    // Calculate totals for each product and the overall total
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

    // Check if selected_products were submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_products'])) {
        $selectedIds = $_POST['selected_products'];
        foreach ($selectedIds as $id) {
            if (isset($cart[$id])) {
                $selectedProducts[$id] = $cart[$id];
                $selectedTotal += $cart[$id]['price'] * $cart[$id]['quantity'];
            }
        }

        // Store selected products in session for processCheckout
        $_SESSION['selected_checkout'] = $selectedProducts;
        $_SESSION['selected_total'] = $selectedTotal;
    } else {
        // If no products were selected, redirect back to cart
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

            // Nếu số lượng <= 0 thì xóa sản phẩm khỏi giỏ hàng
            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }

        header('Location: /project1/Product/cart');
    }
public function processCheckout()
{
    error_log("processCheckout method called at " . date('Y-m-d H:i:s') . " with REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("POST request detected, proceeding with checkout process.");
        $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
        $phone = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING));
        $address = trim(filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING));

        // Debug logging
        error_log("Received POST data: " . print_r($_POST, true));
        error_log("Session cart: " . print_r($_SESSION['cart'], true));

        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION["ErrorMessage"] = "Giỏ hàng trống.";
            error_log("Error: Cart is empty or not set.");
            header('Location: /project1/product/cart');
            exit();
        }

        if (empty($name) || empty($phone) || empty($address)) {
            $_SESSION["ErrorMessage"] = "Vui lòng điền đầy đủ thông tin (tên, số điện thoại, địa chỉ).";
            error_log("Error: Missing required fields - name: $name, phone: $phone, address: $address");
            header('Location: /project1/product/checkout');
            exit();
        }

        // Calculate total order cost
        $cart = $_SESSION['cart'];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        error_log("Calculated total price: $total");

        $this->db->beginTransaction();

        try {
            error_log("Starting database transaction.");
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
            error_log("Order inserted, order_id: $order_id");

            // Temporarily comment out order_details to isolate the issue
            /*
            foreach ($cart as $product_id => $item) {
                error_log("Processing product_id: $product_id");
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price)
                          VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
                if (!$stmt->execute()) {
                    throw new Exception("Lỗi khi lưu chi tiết đơn hàng cho product_id $product_id: " . print_r($stmt->errorInfo(), true));
                }
                error_log("Order detail inserted for product_id: $product_id");
            }
            */

            unset($_SESSION['cart']);
            $this->db->commit();
            error_log("Test commit without details, order_id: $order_id, total: $total");
            header('Location: /project1/product/orderConfirmation');
            exit();
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION["ErrorMessage"] = "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            error_log("Checkout error: " . $e->getMessage());
            header('Location: /project1/product/checkout');
            exit();
        }
    } else {
        error_log("Non-POST request received, method: " . $_SERVER['REQUEST_METHOD']);
        $_SESSION["ErrorMessage"] = "Yêu cầu không hợp lệ, chỉ chấp nhận POST.";
        header('Location: /project1/product/checkout');
        exit();
    }
}
public function removeFromCart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }
    header('Location: /project1/product/cart');
    exit;
}

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
}
?>