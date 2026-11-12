import apiRequest from './apiRequest.js';
import hashSHA1 from '../helper/hashSHA1.js';

// Khi tạo tài khoản hoặc reset mật khẩu:
const passwordHash = await hashSHA1(plainPassword);
// Gửi { ..., passwordHash } lên API
export const login = async (formData) => {

    const result = await apiRequest(
        `/TaiKhoan/login.php`,
        {
            method: 'POST',
            body: formData
        }
    );

    return result;
};

export const register = async (formData) => {

    const result = await apiRequest(
        `/TaiKhoan/register.php`,
        {
            method: 'POST',
            body: formData
        }
    );

    return result;

}