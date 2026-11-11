<?php
require_once 'GiangVien_db.php';

$giangVienDb = new GiangVien_db();
$message = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$gv = $giangVienDb->getById($id);
if (!$gv) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newId = trim($_POST['idgv'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $sumClass = trim($_POST['sumClass'] ?? '');
    $imageNameToSave = $gv->getImage();

    if (empty($newId) || !is_numeric($newId) || strlen($newId) != 5) {
        $message = "Mã giảng viên phải là số gồm đúng 5 chữ số!";
    } elseif ($newId != $id && $giangVienDb->checkIdExists($newId)) {
        $message = "Mã giảng viên mới này đã tồn tại ở giảng viên khác!";
    } elseif ($giangVienDb->checkFullnameExists($fullname, $id)) {
        $message = "Tên giảng viên này đã tồn tại ở giảng viên khác!";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];

            $maxSize = 5 * 1024 * 1024;

            if ($fileSize <= $maxSize) {
                $uploadFileDir = 'images/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0777, true);
                }

                $newFileName = time() . '_' . basename($fileName);
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    if (!empty($imageNameToSave) && file_exists($uploadFileDir . $imageNameToSave)) {
                        unlink($uploadFileDir . $imageNameToSave);
                    }
                    $imageNameToSave = $newFileName;
                } else {
                    $message = "Lỗi khi di chuyển file tải lên!";
                }
            } else {
                $message = "Kích thước file vượt quá giới hạn 5MB!";
            }
        }

        if (empty($message)) {
            $success = $giangVienDb->update($id, $newId, $fullname, $imageNameToSave, $sumClass);
            if ($success) {
                header("Location: index.php");
                exit();
            } else {
                $message = "Lỗi khi cập nhật dữ liệu vào cơ sở dữ liệu!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Giảng viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-warning text-dark mb-4 text-center"><i class="bi bi-pencil-square me-2"></i>Sửa thông tin Giảng viên</h3>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger py-2" role="alert"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data" id="updateForm">
                            <div class="mb-3">
                                <label for="idgv" class="form-label fw-semibold">Mã Giảng viên (5 chữ số):</label>
                                <input type="text" id="idgv" name="idgv" class="form-control" value="<?php echo $gv->getIdgv(); ?>" maxlength="5" required>
                            </div>

                            <div class="mb-3">
                                <label for="fullname" class="form-label fw-semibold">Họ và Tên:</label>
                                <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo $gv->getFullname(); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Hình ảnh (Tối đa 5MB):</label>
                                <input type="file" id="image" name="image" class="form-control">
                                <div class="mt-2">
                                    <img id="preview" src="images/<?php echo $gv->getImage(); ?>" alt="Ảnh hiện tại" class="preview-img" style="display: block;">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="sumClass" class="form-label fw-semibold">Tổng số lớp:</label>
                                <input type="text" id="sumClass" name="sumClass" class="form-control" value="<?php echo $gv->getSumClass(); ?>" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning text-white">Sửa thông tin</button>
                                <a href="index.php" class="btn btn-outline-secondary">Huỷ sửa</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const imageInput = document.getElementById('image');
        const previewImg = document.getElementById('preview');

        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>