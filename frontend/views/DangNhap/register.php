<div class="register-page auth-page">
    <div class="register-card">
        <div class="register-header">
            <h2>Tạo tài khoản</h2>
            <p>Đăng ký tài khoản bệnh nhân CTUMedic</p>
        </div>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo "<script>alert('" . addslashes($_SESSION['error_message']) . "');</script>";
            unset($_SESSION['error_message']); // Xóa sau khi hiển thị tránh bị lặp lại
        }
        if (isset($_SESSION['success_message'])) {
            echo "<script>alert('" . addslashes($_SESSION['success_message']) . "');</script>";
            unset($_SESSION['success_message']);
        }
        ?>
        <form id="registerForm" action="index.php?api=register" method="POST">
            <div class="form-section">
                <h3>Thông tin tài khoản</h3>

                <div class="form-group">
                    <label for="username">Tên đăng nhập <span>*</span></label>
                    <input type="text" id="username" name="username"
                        placeholder="Nhập tên đăng nhập"
                        maxlength="50" autocomplete="username" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Mật khẩu <span>*</span></label>
                        <input type="password" id="password" name="password"
                            placeholder="Nhập mật khẩu"
                            autocomplete="new-password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Xác nhận mật khẩu <span>*</span></label>
                        <input type="password" id="confirmPassword"
                            name="confirmPassword"
                            placeholder="Nhập lại mật khẩu"
                            autocomplete="new-password" required>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Thông tin cá nhân</h3>

                <div class="form-group">
                    <label for="hoTen">Họ và tên <span>*</span></label>
                    <input type="text" id="hoTen" name="hoTen"
                        placeholder="Nhập họ và tên"
                        maxlength="100" autocomplete="name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="ngaySinh">Ngày sinh</label>
                        <input type="date" id="ngaySinh" name="ngaySinh">
                    </div>

                    <div class="form-group">
                        <label for="SDT">Số điện thoại <span>*</span></label>
                        <input type="tel" id="SDT" name="SDT"
                            placeholder="Nhập số điện thoại"
                            maxlength="10" pattern="0[0-9]{9}"
                            autocomplete="tel"
                            title="Số điện thoại gồm 10 chữ số và bắt đầu bằng số 0"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="CCCD">CCCD/CMND</label>
                        <input type="text" id="CCCD" name="CCCD"
                            placeholder="Nhập số CCCD/CMND"
                            maxlength="12" pattern="[0-9]{9}|[0-9]{12}"
                            title="CCCD gồm 12 chữ số hoặc CMND cũ gồm 9 chữ số">
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            placeholder="Nhập địa chỉ email"
                            maxlength="255" autocomplete="email">
                    </div>
                </div>
            </div>

            <input type="submit" class="btn btn-primary register-button" value="Đăng ký">
            <p class="register-footer">
                Đã có tài khoản?
                <a href="index.php?page=login">Đăng nhập</a>
            </p>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registerForm');

        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Dừng submit mặc định để xử lý băm mật khẩu

            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');

            // Kiểm tra xác nhận mật khẩu có khớp không
            if (passwordInput.value !== confirmPasswordInput.value) {
                alert('Mật khẩu xác nhận không khớp.');
                confirmPasswordInput.focus();
                return;
            }

            try {
                // Băm mật khẩu trước khi gửi lên server
                const hashedPassword = await hashPassword(passwordInput.value);

                // Thay thế giá trị gốc bằng chuỗi đã băm
                passwordInput.value = hashedPassword;
                confirmPasswordInput.value = hashedPassword;

                // Gửi form (Lúc này các điều kiện required, pattern của HTML5 đã được trình duyệt tự kiểm tra xong)
                form.submit();

            } catch (error) {
                console.error('Lỗi khi băm mật khẩu:', error);
                alert('Đã xảy ra lỗi, vui lòng thử lại.');
            }
        });
    });

    // Hàm băm SHA-256 sử dụng Web Crypto API
    async function hashPassword(plainText) {
        const encoder = new TextEncoder();
        const data = encoder.encode(plainText);
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }
</script>