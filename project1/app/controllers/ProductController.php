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
        session_start();
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
}