import apiRequest from './apiRequest.js';

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