
<div class="login-page auth-page">
    <div class="login-card">

        <!-- Bên trái: Thương hiệu và điều hướng -->
        <section class="login-brand">
            <a href="index.php?page=login" class="brand-logo">
                <span class="brand-icon">+</span>
                <span>CTU<span class="brand-highlight">Medic</span></span>
            </a>

            <div class="brand-content">
                <span class="brand-label">CHĂM SÓC SỨC KHỎE</span>

                <h1>Sức khỏe của bạn,<br>
                    <span>ưu tiên của chúng tôi.</span>
                </h1>

                <p>
                    Đặt lịch khám dễ dàng, kết nối với đội ngũ bác sĩ
                    và quản lý lịch khám của bạn tại CTUMedic.
                </p>
            </div>

            <div class="brand-footer">
                <span>Chưa có tài khoản?</span>
                <a href="index.php?page=register">
                    Đăng ký ngay <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>

        <!-- Bên phải: Biểu mẫu đăng nhập -->
        <section class="login-form-section">
            <div class="login-form-container">
                <div class="login-heading">
                    <h2>Chào mừng trở lại!</h2>
                    <p>Vui lòng đăng nhập để tiếp tục.</p>
                </div>

                <form id="loginForm">
                    <div class="login-form-group">
                        <label for="loginIdentifier">
                            Tên đăng nhập hoặc CCCD/CMND
                        </label>

                        <input
                            type="text"
                            id="loginIdentifier"
                            name="loginIdentifier"
                            placeholder="Nhập tên đăng nhập hoặc CCCD/CMND"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <div class="login-form-group">
                        <label for="loginPassword">Mật khẩu</label>

                        <input
                            type="password"
                            id="loginPassword"
                            name="password"
                            placeholder="Nhập mật khẩu"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="login-submit">
                        Đăng nhập
                        <span aria-hidden="true">→</span>
                    </button>
                </form>

                <div class="login-note">
                    <span class="note-icon">i</span>
                    <p>
                        Bệnh nhân, bác sĩ và quản lý sử dụng chung
                        trang đăng nhập này.
                    </p>
                </div>

                <div class="login-mobile-register">
                    Chưa có tài khoản?
                    <a href="index.php?page=register">Đăng ký ngay</a>
                </div>
            </div>

            <div class="login-copyright">
                © CTUMedic · Hệ thống quản lý và đặt lịch khám
            </div>
        </section>

    </div>
</div>
```