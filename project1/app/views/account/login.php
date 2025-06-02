<?php include 'app/views/shares/header.php'; ?>
<section class="py-5" style="background-color: var(--light-color);">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 56px);">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg" style="border-radius: 1rem; background-color: white;">
                    <div class="card-body p-4 p-md-5 text-center">
                        <h2 class="fw-bold mb-3 text-uppercase text-dark">Đăng nhập</h2>
                        <p class="text-muted mb-4">Vui lòng nhập thông tin đăng nhập</p>
                        <?php if (!empty($ErrorMessage)): ?>
                            <div class="notification error mb-4">
                                <i class="fas fa-times-circle me-2"></i><?php echo htmlspecialchars($ErrorMessage); ?>
                            </div>
                        <?php endif; ?>
                        <form action="/project1/account/checklogin" method="post">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? bin2hex(random_bytes(32))); ?>">
                            <div class="form-outline mb-4">
                                <input type="text" name="username" id="username" class="form-control form-control-lg rounded-pill" placeholder="Tên đăng nhập" required />
                            </div>
                            <div class="form-outline mb-4">
                                <input type="password" name="password" id="password" class="form-control form-control-lg rounded-pill" placeholder="Mật khẩu" required />
                            </div>
                            <div class="mb-4">
                                <a href="#!" class="text-primary small">Quên mật khẩu?</a>
                            </div>
                            <button class="btn btn-primary btn-lg px-5 rounded-pill" type="submit">Đăng nhập</button>
                            <p class="mt-4 mb-0">
                                Chưa có tài khoản? 
                                <a href="/project1/account/register" class="text-primary fw-bold">Đăng ký</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?>