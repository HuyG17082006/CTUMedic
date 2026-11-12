import apiRequest from './apiRequest.js';
import hashSHA1 from '../helper/hashSHA1.js';

export async function getMyProfile() {
    return await apiRequest('/TaiKhoan/profile.php', { method: 'GET' });
}

export async function updateMyProfile(payload) {
    return await apiRequest('/TaiKhoan/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
}

export async function changeMyPassword({ oldPassword, newPassword, confirmPassword }) {
    const oldPasswordHash = await hashSHA1(oldPassword);
    const newPasswordHash = await hashSHA1(newPassword);
    const confirmPasswordHash = await hashSHA1(confirmPassword);

    return await apiRequest('/TaiKhoan/change_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ oldPasswordHash, newPasswordHash, confirmPasswordHash }),
    });
}