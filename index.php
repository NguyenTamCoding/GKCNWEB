<?php
require_once 'GiangVien_db.php';

$giangVienDb = new GiangVien_db();
$listGiangVien = $giangVienDb->getAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Giảng viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>Quản lý Giảng viên</h2>
            </div>
            <div class="col text-end">
                <a href="Add_GiangVien.php" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Thêm giảng viên mới
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="py-3 ps-3">IDGV</th>
                                <th class="py-3">Họ và tên</th>
                                <th class="py-3">Hình ảnh</th>
                                <th class="py-3">Tổng số lớp</th>
                                <th class="py-3 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($listGiangVien)): ?>
                                <?php foreach ($listGiangVien as $gv): ?>
                                    <tr>
                                        <td class="ps-3 fw-semibold"><?php echo $gv->getIdgv(); ?></td>
                                        <td><?php echo $gv->getFullname(); ?></td>
                                        <td>
                                            <img src="images/<?php echo $gv->getImage(); ?>" alt="Ảnh" class="rounded shadow-sm" width="45" height="45" style="object-fit: cover;">
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo $gv->getSumClass(); ?> lớp</span></td>
                                        <td class="text-center">
                                            <a href="Update_GiangVien.php?id=<?php echo $gv->getIdgv(); ?>" class="btn btn-sm btn-warning text-white me-2">
                                                <i class="bi bi-pencil-square"></i> Sửa
                                            </a>
                                            <a href="Delete_GiangVien.php?id=<?php echo $gv->getIdgv(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa giảng viên này không?');">
                                                <i class="bi bi-trash"></i> Xóa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Không có dữ liệu giảng viên</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>