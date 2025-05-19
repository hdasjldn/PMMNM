<?php
class CategoryModel
{
    private $db;
    private $table_name = 'category'; // Changed to lowercase for consistency with PHP conventions

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function DanhSachTheLoai()
    {
        $query = "
                    SELECT c.*
                    FROM " . $this->table_name . " c
                ";

        $stmt = $this->db->prepare($query);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function ThemTheLoai($name, $des)
    {
        $query = "
                    INSERT INTO {$this->table_name} (name, description) 
                    VALUES (:name, :des)
                "; // Removed unnecessary semicolon and matched column names to schema

        $stmt = $this->db->prepare($query);

        $name = htmlspecialchars($name); // Removed strip_tags as htmlspecialchars is sufficient
        $des = htmlspecialchars($des);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':des', $des);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}