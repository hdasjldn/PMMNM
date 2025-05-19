<?php

require_once("app/models/CategoryModel.php"); //  GỌI MODEL CATEGORY
require_once('app/config/database.php'); // GỌI DATABASE
require_once('app/models/ProductModel.php'); // GỌI MODEL PRODUCT




class CategoryController
{

    private $db;
    private $db_category;

    public function __construct()
    {
        $this->db = (new Database())->getConnection(); // KHAI BÁO DB
        $this->db_category = new CategoryModel($this->db); // KHỞI TẠO CATEGORY
    }


    public function Index()
    {
        $dsTheLoai = $this->db_category->DanhSachTheLoai();
        include 'app/views/Category/Index.php'; // TRẢ VỀ TRANG HIỆN TẠI INDEX
    }

    public function Add()
    {
        include 'app/views/Category/add.php'; // TRẢ VỀ TRANG HIỆN TẠI
    }


    public function SaveAdd() // PHƯƠNG THỨC POST CỦA ADD 
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name']; // POST
            $des = $_POST['des'];

            if ($this->db_category->ThemTheLoai($name, $des)) {
                header('Location: /project1/Category'); // DI CHUYỂN QUA TRANG KHÁC
            } else {
                include 'app/view/Category/add.php'; // TRẢ VỀ TRANG HIỆN TẠI 
            }
        }
    }
}
