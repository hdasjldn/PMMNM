<?php
class ProductModel
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $category_id;
    private $db;
    private $table_name = 'product';

    // Constructor for data model
    public function __construct($db, $id = null, $name = null, $description = null, $price = null, $category_id = null, $image = null)
    {
        $this->db = $db;
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->category_id = $category_id;
        $this->image = $image;
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int) $id; // Ensure ID is an integer
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = htmlspecialchars($name); // Sanitize input
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = htmlspecialchars($description); // Sanitize input
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        if (!is_numeric($price) || $price < 0) {
            throw new InvalidArgumentException("Price must be a positive number.");
        }
        $this->price = (float) $price;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = (int) $category_id; // Ensure category_id is an integer
    }

    // Database interaction methods
    public function DanhSachSanPham()
    {
        $query = "
                    SELECT p.*, c.name AS category
                    FROM " . $this->table_name . " p
                    JOIN category c ON p.category_id = c.id
                ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

public function LaySanPhamTheoID($id)
    {
        $query = "
            SELECT p.*, c.name AS category_name
            FROM {$this->table_name} p
            LEFT JOIN category c ON p.category_id = c.id
            WHERE p.id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    public function ThemSanPham($name, $price, $description, $category_id, $image = null)
    {
        $error = [];
        if (empty($name)) {
            $error['name'] = "Chưa có tên sản phẩm";
        }
        if (empty($description)) {
            $error['description'] = "Chưa có mô tả sản phẩm";
        }
        if (!is_numeric($price) || $price < 0) {
            $error['price'] = 'Giá sản phẩm không phù hợp';
        }

        if (count($error) > 0) {
            return $error;
        }

        $query = "
                INSERT INTO {$this->table_name} (name, price, description, category_id, image) 
                VALUES (:name, :price, :description, :category_id, :image)
        ";

        $stmt = $this->db->prepare($query);

        $name = htmlspecialchars($name);
        $description = htmlspecialchars($description);
        $price = (float) $price;
        $category_id = (int) $category_id;

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':image', $image);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function ChinhSuaSanPham($id, $name, $price, $description, $category_id, $image = null)
    {
        if ($image === null) {
            $query = "
                    UPDATE {$this->table_name}
                    SET name = :name, price = :price, description = :description, category_id = :category_id
                    WHERE id = :id
            ";
            $stmt = $this->db->prepare($query);

            $name = htmlspecialchars($name);
            $price = (float) $price;
            $description = htmlspecialchars($description);
            $category_id = (int) $category_id;
            $id = (int) $id;

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        } else {
            $query = "
                    UPDATE {$this->table_name}
                    SET name = :name, price = :price, description = :description, category_id = :category_id, image = :image
                    WHERE id = :id
            ";

            $stmt = $this->db->prepare($query);

            $name = htmlspecialchars($name);
            $price = (float) $price;
            $description = htmlspecialchars($description);
            $category_id = (int) $category_id;
            $id = (int) $id;

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function XoaSanPham($id)
    {
        $query = "
                    DELETE FROM {$this->table_name} 
                    WHERE id = :id
        ";

        $stmt = $this->db->prepare($query);

        $id = (int) $id;
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>