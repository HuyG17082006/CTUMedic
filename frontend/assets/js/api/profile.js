import apiRequest from './api_request.js';
import hashSHA1 from '../helper/hash_sha1.js';

export async function getCurrentUserProfile() {
    return await apiRequest('/accounts/profile.php', { method: 'GET' });
}

export async function updateCurrentUserProfile(payload) {
    return await apiRequest('/accounts/update_profile.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
    });
}

export async function changePassword({ oldPassword, newPassword, confirmPassword }) {
    const oldPasswordHash = await hashSHA1(oldPassword);
    const newPasswordHash = await hashSHA1(newPassword);
    const confirmPasswordHash = await hashSHA1(confirmPassword);

    return await apiRequest('/accounts/change_password.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ oldPasswordHash, newPasswordHash, confirmPasswordHash }),
    });
}