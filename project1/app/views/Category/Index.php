<?php include 'app/views/shares/header.php'; ?>

<h1>Danh sách Thể Loại</h1>
<div class="container">
    <div class="card">
        <div class="card-header row">
            <div class="col-10">Danh Sách Thể Loại</div>
            <div class="col-2">
                <a href="/project1/Category/Add" class="btn btn-success">Thêm Thể Loại </a>
            </div>
        </div>
        <div class="card-body row">
            <?php if ($dsTheLoai != null): ?>
                <?php foreach ($dsTheLoai as $i): ?>
                    <div class="card col-3 m-1">
                        <div class="card-header">
                            <h2><?php echo htmlspecialchars($i->id) ?></h2>
                        </div>
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($i->name); ?></h3>
                            <p><?php echo htmlspecialchars($i->description); ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>
    </div>

</div>

<?php include 'app/views/shares/footer.php' ?>