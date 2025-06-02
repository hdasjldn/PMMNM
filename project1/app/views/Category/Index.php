<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Danh Sách Thể Loại</h3>
            <a href="/project1/Category/Add" class="btn btn-light btn-sm">Thêm Thể Loại</a>
        </div>
        <div class="card-body">
            <?php if ($dsTheLoai != null): ?>
                <div class="row g-3">
                    <?php foreach ($dsTheLoai as $i): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">ID: <?php echo htmlspecialchars($i->id); ?></h5>
                                </div>
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo htmlspecialchars($i->name); ?></h4>
                                    <p class="card-text text-muted"><?php echo htmlspecialchars($i->description); ?></p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="/project1/Category/Edit/<?php echo htmlspecialchars($i->id); ?>" class="btn btn-outline-primary btn-sm">Sửa</a>
                                    <a href="/project1/Category/Delete/<?php echo htmlspecialchars($i->id); ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa thể loại này?');">Xóa</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">
                    Chưa có thể loại nào được thêm.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.card-title {
    font-size: 1.25rem;
    font-weight: 500;
}
.card-text {
    font-size: 0.9rem;
    line-height: 1.5;
}
</style>