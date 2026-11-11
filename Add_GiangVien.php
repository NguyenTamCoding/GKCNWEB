<?php
require_once 'GiangVien_db.php';

$message = '';
$giangVienDb = new GiangVien_db();

// Tự động sinh mã 5 chữ số ngẫu nhiên không trùng lặp khi load form
do {
    $randomId = rand(10000, 99999);
} while ($giangVienDb->checkIdExists($randomId));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idgv = trim($_POST['idgv'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $sumClass = trim($_POST['sumClass'] ?? '');
    
    if (empty($idgv) || !is_numeric($idgv) || strlen($idgv) != 5) {
        $message = "Mã giảng viên phải là số gồm đúng 5 chữ số!";
    } elseif ($giangVienDb->checkIdExists($idgv)) {
        $message = "Mã giảng viên này đã tồn tại, vui lòng chọn mã khác!";
    } elseif ($giangVienDb->checkFullnameExists($fullname)) {
        $message = "Tên giảng viên này đã tồn tại trong hệ thống!";
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

                $imageNameToSave = time() . '_' . basename($fileName);
                $dest_path = $uploadFileDir . $imageNameToSave;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $success = $giangVienDb->insert($idgv, $fullname, $imageNameToSave, $sumClass);

                    if ($success) {
                        header("Location: index.php");
                        exit();
                    } else {
                        $message = "Lỗi khi lưu dữ liệu vào cơ sở dữ liệu!";
                    }
                } else {
                    $message = "Lỗi khi di chuyển file tải lên!";
                }
            } else {
                $message = "Kích thước file vượt quá giới hạn 5MB!";
            }
        } else {
            $message = "Vui lòng chọn file hình ảnh!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Giảng viên</title>
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
                        <h3 class="fw-bold text-success mb-4 text-center"><i class="bi bi-person-plus-fill me-2"></i>Thêm Giảng viên Mới</h3>

                        <?php if (!empty($message)): ?>
                            <div class="alert alert-danger py-2" role="alert"><?php echo $message; ?></div>
                        <?php endif; ?>

                        <form action="" method="POST" enctype="multipart/form-data" id="addForm">
                            <div class="mb-3">
                                <label for="idgv" class="form-label fw-semibold">Mã Giảng viên (5 chữ số):</label>
                                <input type="text" id="idgv" name="idgv" class="form-control check-input" value="<?php echo $randomId; ?>" maxlength="5" required>
                            </div>

                            <div class="mb-3">
                                <label for="fullname" class="form-label fw-semibold">Họ và Tên:</label>
                                <input type="text" id="fullname" name="fullname" class="form-control check-input" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label fw-semibold">Hình ảnh (Tối đa 5MB):</label>
                                <input type="file" id="image" name="image" class="form-control" required>
                                <div class="mt-2">
                                    <img id="preview" src="" alt="Xem trước" class="preview-img">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="sumClass" class="form-label fw-semibold">Tổng số lớp:</label>
                                <input type="text" id="sumClass" name="sumClass" class="form-control check-input" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" id="submitBtn" class="btn btn-success" disabled>Thêm giảng viên</button>
                                <a href="index.php" class="btn btn-outline-secondary">Huỷ thêm</a>
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
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
            }
            checkFormInputs();
        });

        const inputs = document.querySelectorAll('.check-input');
        const submitBtn = document.getElementById('submitBtn');

        function checkFormInputs() {
            let allFilled = true;
            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    allFilled = false;
                }
            });

            if (imageInput.files.length === 0) {
                allFilled = false;
            }

            if (allFilled) {
                submitBtn.removeAttribute('disabled');
            } else {
                submitBtn.setAttribute('disabled', 'true');
            }
        }

        inputs.forEach(input => {
            input.addEventListener('input', checkFormInputs);
        });
        
        // Kích hoạt kiểm tra ngay khi load form vì đã có sẵn random ID
        checkFormInputs();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>