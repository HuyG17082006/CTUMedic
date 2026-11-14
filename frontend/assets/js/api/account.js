import apiRequest from './api_request.js';

export const loginUser = async (formData) => {

    const result = await apiRequest(
        `/accounts/login.php`,
        {
            method: 'POST',
            body: formData
        }
    );

    return result;
};

export const registerUser = async (formData) => {

    const result = await apiRequest(
        `/accounts/register.php`,
        {
            method: 'POST',
            body: formData
        }
    );

    return result;

};

export const logoutUser = async () => {
    return await apiRequest('/accounts/logout.php', {
        method: 'POST'
    });
};