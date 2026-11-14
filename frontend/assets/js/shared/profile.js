import { getMyProfile, updateMyProfile, changeMyPassword } from '../api/profile.js';

const ROLE_LABELS = {
    manager: 'Quản lý',
    doctor: 'Bác sĩ',
    patient: 'Bệnh nhân',
};

document.addEventListener('DOMContentLoaded', async () => {
    const updateProfileForm = document.getElementById('updateProfileForm');
    const changePasswordForm = document.getElementById('changePasswordForm');

    async function loadProfileData() {
        try {
            const res = await getMyProfile();
            if (!res || res.status !== 'success') {
                alert(res?.message || 'Không thể tải thông tin tài khoản.');
                return;
            }

            const { account, profile } = res;

            // 1. Điền thông tin tài khoản đăng nhập
            const usernameEl = document.getElementById('profileUsername');
            const roleEl = document.getElementById('profileRole');
            const statusBadgeEl = document.getElementById('profileStatusBadge');

            if (usernameEl) usernameEl.value = account.username || '';
            if (roleEl) roleEl.value = ROLE_LABELS[account.role] || account.role || '';

            if (statusBadgeEl) {
                const isActive = Number(account.isActive) === 1;
                statusBadgeEl.innerHTML = isActive
                    ? '<span style="display:inline-block;padding:4px 12px;border-radius:9999px;font-size:13px;font-weight:600;background:#dcfce7;color:#15803d;">Đang hoạt động</span>'
                    : '<span style="display:inline-block;padding:4px 12px;border-radius:9999px;font-size:13px;font-weight:600;background:#fee2e2;color:#b91c1c;">Đã khóa</span>';
            }

            // 2. Hiển thị hồ sơ cá nhân nếu là Bệnh nhân hoặc Bác sĩ
            const personalCard = document.getElementById('personalProfileCard');
            const patientFields = document.getElementById('patientProfileFields');
            const doctorFields = document.getElementById('doctorProfileFields');

            if (account.role === 'patient') {
                if (personalCard) personalCard.style.display = 'block';
                if (patientFields) patientFields.style.display = 'block';
                if (doctorFields) doctorFields.style.display = 'none';

                document.getElementById('pfPatientHoTen').value = profile?.hoTen || '';
                document.getElementById('pfPatientNgaySinh').value = profile?.ngaySinh || '';
                document.getElementById('pfPatientSDT').value = profile?.SDT || '';
                document.getElementById('pfPatientEmail').value = profile?.email || '';
                document.getElementById('pfPatientCCCD').value = profile?.CCCD || '';
            } else if (account.role === 'doctor') {
                if (personalCard) personalCard.style.display = 'block';
                if (patientFields) patientFields.style.display = 'none';
                if (doctorFields) doctorFields.style.display = 'block';

                document.getElementById('pfDoctorHoTen').value = profile?.hoTen || '';
                document.getElementById('pfDoctorEmail').value = profile?.email || '';
                if (profile?.chuyenKhoa) {
                    document.getElementById('pfDoctorChuyenKhoa').value = profile.chuyenKhoa;
                }
            } else {
                if (personalCard) personalCard.style.display = 'none';
            }

            if (updateProfileForm) {
                updateProfileForm.dataset.role = account.role;
            }
        } catch (err) {
            console.error('Lỗi tải thông tin tài khoản:', err);
            alert(err?.message || 'Có lỗi xảy ra khi tải thông tin tài khoản.');
        }
    }

    // Xử lý lưu thông tin cá nhân (Bệnh nhân / Bác sĩ)
    if (updateProfileForm) {
        updateProfileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const role = updateProfileForm.dataset.role;
            let payload = {};

            if (role === 'patient') {
                payload = {
                    hoTen: document.getElementById('pfPatientHoTen').value.trim(),
                    ngaySinh: document.getElementById('pfPatientNgaySinh').value.trim(),
                    SDT: document.getElementById('pfPatientSDT').value.trim(),
                    email: document.getElementById('pfPatientEmail').value.trim(),
                    CCCD: document.getElementById('pfPatientCCCD').value.trim(),
                };
            } else if (role === 'doctor') {
                payload = {
                    hoTen: document.getElementById('pfDoctorHoTen').value.trim(),
                    email: document.getElementById('pfDoctorEmail').value.trim(),
                    chuyenKhoa: document.getElementById('pfDoctorChuyenKhoa').value.trim(),
                };
            } else {
                return;
            }

            try {
                const res = await updateMyProfile(payload);
                if (res?.status === 'success') {
                    alert(res.message || 'Cập nhật thông tin cá nhân thành công!');
                    await loadProfileData();
                } else {
                    alert(res?.message || 'Cập nhật thất bại.');
                }
            } catch (err) {
                alert(err?.message || 'Có lỗi xảy ra khi cập nhật thông tin.');
            }
        });
    }

    // Xử lý đổi mật khẩu (băm SHA-1 trước khi gửi)
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const oldPassword = document.getElementById('oldPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!oldPassword || !newPassword || !confirmPassword) {
                alert('Vui lòng nhập đầy đủ các trường mật khẩu.');
                return;
            }

            if (newPassword.length < 6) {
                alert('Mật khẩu mới phải có ít nhất 6 ký tự.');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('Mật khẩu xác nhận không khớp.');
                return;
            }

            try {
                const res = await changeMyPassword({
                    oldPassword,
                    newPassword,
                    confirmPassword,
                });

                if (res?.status === 'success') {
                    alert(res.message || 'Đổi mật khẩu thành công!');
                    changePasswordForm.reset();
                } else {
                    alert(res?.message || 'Đổi mật khẩu thất bại.');
                }
            } catch (err) {
                alert(err?.message || 'Có lỗi xảy ra khi đổi mật khẩu.');
            }
        });
    }

    await loadProfileData();
});