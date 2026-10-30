<div class="thong-tin-tai-khoan">
    <form id="profileDisplayForm">
        <div class="form-section">
            <h3>Thông tin tài khoản</h3>

            <div class="form-group">
                <label for="username">Tên đăng nhập <span>*</span></label>
                <input type="text" id="username" name="username"
                    placeholder="Nhập tên đăng nhập"
                    maxlength="50" autocomplete="username" required readonly>
            </div>
            
            <div class="form-row" style="display: none;">
                <div class="form-group">
                    <label for="password">Mật khẩu <span>*</span></label>
                    <input type="password" id="password" name="password" autocomplete="new-password" readonly>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu <span>*</span></label>
                    <input type="password" id="confirmPassword" name="confirmPassword" autocomplete="new-password" readonly>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Thông tin cá nhân</h3>

            <div class="form-group">
                <label for="hoTen">Họ và tên <span>*</span></label>
                <input type="text" id="hoTen" name="hoTen"
                    placeholder="Nhập họ và tên"
                    maxlength="100" autocomplete="name" required readonly>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ngaySinh">Ngày sinh</label>
                    <input type="date" id="ngaySinh" name="ngaySinh" readonly>
                </div>

                <div class="form-group">
                    <label for="SDT">Số điện thoại <span>*</span></label>
                    <input type="tel" id="SDT" name="SDT"
                        placeholder="Nhập số điện thoại"
                        maxlength="10" pattern="0[0-9]{9}"
                        autocomplete="tel"
                        title="Số điện thoại gồm 10 chữ số và bắt đầu bằng số 0"
                        required readonly>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="CCCD">CCCD/CMND</label>
                    <input type="text" id="CCCD" name="CCCD"
                        placeholder="Nhập số CCCD/CMND"
                        maxlength="12" pattern="[0-9]{9}|[0-9]{12}"
                        title="CCCD gồm 12 chữ số hoặc CMND cũ gồm 9 chữ số" readonly>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        placeholder="Nhập địa chỉ email"
                        maxlength="255" autocomplete="email" readonly>
                </div>
            </div>
        </div>

        <!-- Khu vực nút bấm chuẩn theo hệ thống -->
        <div class="appointment-modal__actions" style="border-top: none; margin-top: 20px; padding-top: 0;">
            <button type="button" class="btn-secondary" id="btnExit">Thoát</button>
            <button type="button" class="btn-secondary" id="btnOpenChangeInfo">Sửa thông tin</button>
            <button type="button" class="btn-primary" id="btnOpenChangePassword">Thay đổi mật khẩu</button>
        </div>
    </form>
</div>

<!-- POPUP MODAL SỬA THÔNG TIN (EMAIL & SĐT) -->
<div id="editModal" class="appointment-modal" hidden>
    <div class="appointment-modal__backdrop"></div>
    <div class="appointment-modal__dialog" style="max-width: 500px;">
        <div class="appointment-modal__header">
            <div>
                <h2>Chỉnh sửa thông tin cá nhân</h2>
                <p>Cập nhật số điện thoại và địa chỉ email của bạn</p>
            </div>
            <button type="button" class="appointment-modal__close" id="btnCloseModal">&times;</button>
        </div>
        
        <form id="editProfileForm">
            <div style="padding: 22px 24px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="modal_email" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Email mới <span>*</span></label>
                    <input type="email" id="modal_email" name="email" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" required>
                </div>

                <div class="form-group">
                    <label for="modal_sdt" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Số điện thoại mới <span>*</span></label>
                    <input type="tel" id="modal_sdt" name="sdt" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" maxlength="10" pattern="0[0-9]{9}" required>
                </div>
            </div>

            <div class="appointment-modal__actions" style="padding: 16px 24px; background: #f8fafc;">
                <button type="button" class="btn-secondary" id="btnCancelModal">Hủy</button>
                <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- POPUP MODAL ĐỔI MẬT KHẨU -->
<div id="passwordModal" class="appointment-modal" hidden>
    <div class="appointment-modal__backdrop"></div>
    <div class="appointment-modal__dialog" style="max-width: 500px;">
        <div class="appointment-modal__header">
            <div>
                <h2>Đổi mật khẩu tài khoản</h2>
                <p>Bảo mật tài khoản bằng mật khẩu mạnh</p>
            </div>
            <button type="button" class="appointment-modal__close" id="btnClosePasswordModal">&times;</button>
        </div>
        
        <form id="changePasswordForm">
            <div style="padding: 22px 24px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="old_password" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Mật khẩu cũ <span>*</span></label>
                    <input type="password" id="old_password" name="old_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Nhập mật khẩu cũ" autocomplete="current-password" required>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="new_password" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Mật khẩu mới <span>*</span></label>
                    <input type="password" id="new_password" name="new_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Nhập mật khẩu mới" autocomplete="new-password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password" style="display: block; margin-bottom: 6px; font-weight: 500; font-size: 13px;">Xác nhận mật khẩu mới <span>*</span></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password" required>
                </div>
            </div>

            <div class="appointment-modal__actions" style="padding: 16px 24px; background: #f8fafc;">
                <button type="button" class="btn-secondary" id="btnCancelPasswordModal">Hủy</button>
                <button type="submit" class="btn-primary">Xác nhận đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- JAVASCRIPT XỬ LÝ LOGIC -->
<script>
    let globalUserRole = '';
    const editModal = document.getElementById('editModal');
    const passwordModal = document.getElementById('passwordModal');

    // Hàm hỗ trợ băm SHA-1 ở client
    async function hashSHA1(str) {
        const encoder = new TextEncoder();
        const data = encoder.encode(str);
        const hashBuffer = await crypto.subtle.digest('SHA-1', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
    }

    // 1. Tải thông tin tài khoản
    async function fetchUserInformation(){
        try {
            const response = await fetch('index.php?api=profile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ message: 'get user information' })
            });

            const result = await response.json();

            if(result.status === 'success'){
                const user = result.user || result.information || result.body;
                globalUserRole = user.role || 'patient';

                document.getElementById('username').value = user.username || '';
                document.getElementById('hoTen').value = user.hoTen || '';
                document.getElementById('ngaySinh').value = user.ngaySinh || '';
                document.getElementById('CCCD').value = user.CCCD || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('SDT').value = user.SDT || '';

                document.getElementById('modal_email').value = user.email || '';
                document.getElementById('modal_sdt').value = user.SDT || '';
            } else {
                alert('ERROR: 404 - NOT FOUND');
            }
        } catch(error) {
            console.error(error);
            alert('Đã xảy ra lỗi kết nối!');
        }
    }

    fetchUserInformation();

    // 2. Xử lý nút Thoát
    document.getElementById('btnExit').addEventListener('click', () => {
        window.location.href = 'index.php?page=' + globalUserRole.toLowerCase();
    });

    // 3. Mở/Đóng Modal Sửa thông tin
    document.getElementById('btnOpenChangeInfo').addEventListener('click', () => { editModal.removeAttribute('hidden'); });
    document.getElementById('btnCloseModal').addEventListener('click', () => { editModal.setAttribute('hidden', ''); });
    document.getElementById('btnCancelModal').addEventListener('click', () => { editModal.setAttribute('hidden', ''); });

    // 4. Mở/Đóng Modal Đổi mật khẩu
    document.getElementById('btnOpenChangePassword').addEventListener('click', () => { passwordModal.removeAttribute('hidden'); });
    document.getElementById('btnClosePasswordModal').addEventListener('click', () => { passwordModal.setAttribute('hidden', ''); });
    document.getElementById('btnCancelPasswordModal').addEventListener('click', () => { passwordModal.setAttribute('hidden', ''); });

    // 5. Submit form cập nhật thông tin liên lạc (Email & SĐT)
    document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = document.getElementById('modal_email').value;
        const sdt = document.getElementById('modal_sdt').value;

        try {
            const response = await fetch('index.php?api=profile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'update_contact', email: email, sdt: sdt })
            });

            const result = await response.json();
            if(result.status === 'success') {
                alert('Cập nhật thông tin thành công!');
                editModal.setAttribute('hidden', '');
                fetchUserInformation();
            } else {
                alert(result.message || 'Cập nhật thất bại!');
            }
        } catch(err) {
            console.error(err);
            alert('Lỗi kết nối máy chủ!');
        }
    });

    // 6. Submit form Đổi mật khẩu (Băm SHA-1)
    document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const oldPasswordRaw = document.getElementById('old_password').value;
        const newPasswordRaw = document.getElementById('new_password').value;
        const confirmPasswordRaw = document.getElementById('confirm_password').value;

        if (newPasswordRaw !== confirmPasswordRaw) {
            alert('Mật khẩu mới và xác nhận mật khẩu không khớp nhau!');
            return;
        }

        try {
            const oldPasswordHash = await hashSHA1(oldPasswordRaw);
            const newPasswordHash = await hashSHA1(newPasswordRaw);

            const response = await fetch('index.php?api=profile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ 
                    action: 'change_password', 
                    old_password: oldPasswordHash, 
                    new_password: newPasswordHash 
                })
            });

            const result = await response.json();
            if(result.status === 'success') {
                alert('Đổi mật khẩu thành công!');
                passwordModal.setAttribute('hidden', '');
                document.getElementById('changePasswordForm').reset();
            } else {
                alert(result.message || 'Đổi mật khẩu thất bại!');
            }
        } catch(err) {
            console.error(err);
            alert('Lỗi kết nối máy chủ!');
        }
    });
</script>