<?php
require_once 'Database.php';
require_once 'GiangVien.php';

class GiangVien_db {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $sql = "SELECT * FROM GiangVien";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gv = new GiangVien($row['IDGV'], $row['Fullname'], $row['Image'], $row['SumClass']);
            $result[] = $gv;
        }
        return $result;
    }

    public function getById($id) {
        $sql = "SELECT * FROM GiangVien WHERE IDGV = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new GiangVien($row['IDGV'], $row['Fullname'], $row['Image'], $row['SumClass']);
        }
        return null;
    }

    // Kiểm tra trùng Mã Giảng Viên (IDGV)
    public function checkIdExists($idgv) {
        $sql = "SELECT COUNT(*) FROM GiangVien WHERE IDGV = :idgv";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['idgv' => $idgv]);
        return $stmt->fetchColumn() > 0;
    }

    // Kiểm tra trùng Tên Giảng Viên
    public function checkFullnameExists($fullname, $excludeId = null) {
        if ($excludeId) {
            $sql = "SELECT COUNT(*) FROM GiangVien WHERE Fullname = :fullname AND IDGV != :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['fullname' => $fullname, 'id' => $excludeId]);
        } else {
            $sql = "SELECT COUNT(*) FROM GiangVien WHERE Fullname = :fullname";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['fullname' => $fullname]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function insert($idgv, $fullname, $image, $sumClass) {
        $sql = "INSERT INTO GiangVien (IDGV, Fullname, Image, SumClass) VALUES (:idgv, :fullname, :image, :sumClass)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'idgv' => $idgv,
            'fullname' => $fullname,
            'image' => $image,
            'sumClass' => $sumClass
        ]);
    }

    public function update($oldId, $newId, $fullname, $image, $sumClass) {
        $sql = "UPDATE GiangVien SET IDGV = :newId, Fullname = :fullname, Image = :image, SumClass = :sumClass WHERE IDGV = :oldId";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'newId' => $newId,
            'oldId' => $oldId,
            'fullname' => $fullname,
            'image' => $image,
            'sumClass' => $sumClass
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM GiangVien WHERE IDGV = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>