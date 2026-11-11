<?php
require_once 'GiangVien_db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $giangVienDb = new GiangVien_db();
    $gv = $giangVienDb->getById($id);

    if ($gv) {
        $name = $gv->getFullname();
        $imageFile = $gv->getImage();

        if ($giangVienDb->delete($id)) {
            if (!empty($imageFile) && file_exists('images/' . $imageFile)) {
                unlink('images/' . $imageFile);
            }
            echo "<script>alert('Đã xóa giảng viên " . $name . " thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Xóa giảng viên thất bại!'); window.location.href='index.php';</script>";
        }
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>