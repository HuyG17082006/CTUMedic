
<div class="register-page auth-page">
    <div class="register-card">
        <div class="register-header">
            <h2>Tạo tài khoản</h2>
            <p>Đăng ký tài khoản bệnh nhân CTUMedic</p>
        </div>

        <form id="registerForm">
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

            <button type="submit" class="btn btn-primary register-button">
                Đăng ký
            </button>

            <p class="register-footer">
                Đã có tài khoản?
                <a href="index.php?page=login">Đăng nhập</a>
            </p>
        </form>
    </div>
</div>
```