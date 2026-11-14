import hashSHA1 from '../helper/hashSHA1.js';

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const identifierInput = document.getElementById('loginIdentifier');
        const passwordInput = document.getElementById('loginPassword');
        const submitBtn = loginForm.querySelector('.login-submit');

        const loginIdentifier = identifierInput?.value.trim() || '';
        const plainPassword = passwordInput?.value || '';

        if (!loginIdentifier || !plainPassword) {
            alert('Vui lòng nhập đầy đủ tên đăng nhập/CCCD và mật khẩu!');
            return;
        }

        const originalBtnHTML = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang đăng nhập...';
        }

        try {
            const passwordHash = await hashSHA1(plainPassword);

            const formData = new FormData();
            formData.append('loginIdentifier', loginIdentifier);
            formData.append('password', passwordHash);

            const response = await fetch('/dashboard/CTUMedic/backend/api/TaiKhoan/login.php', {
                method: 'POST',
                body: formData,
            });

            const res = await response.json();

            if (res.status === 'success') {
                const role = (res.role || '').toLowerCase();
                if (role === 'manager' || role === 'doctor' || role === 'patient') {
                    window.location.href = `/dashboard/CTUMedic/index.php?page=${role}`;
                } else {
                    alert('Vai trò tài khoản không hợp lệ: ' + role);
                }
            } else {
                alert(res.message || 'Sai tên đăng nhập, CCCD hoặc mật khẩu!');
            }
        } catch (err) {
            console.error('Lỗi đăng nhập:', err);
            alert('Có lỗi xảy ra khi kết nối máy chủ.');
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHTML;
            }
        }
    });
});