import apiRequest from './apiRequest.js';

// 1. Lấy danh sách bác sĩ
export const getAllBacSi = async (params = {}) => {
    const cleanParams = Object.fromEntries(
        Object.entries(params).filter(([_, v]) => v !== '' && v !== null && v !== undefined)
    );
    const queryString = new URLSearchParams(cleanParams).toString();
    const url = queryString ? `/QuanLy/bacsi.php?${queryString}` : '/QuanLy/bacsi.php';

    return await apiRequest(url, { method: 'GET' });
};

// 2. Thêm bác sĩ mới
export const addBacSi = async (payload) => {
    return await apiRequest('/QuanLy/manager_add_doctor.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
};

// 3. Cập nhật thông tin bác sĩ
export const updateBacSi = async (id, payload) => {
    return await apiRequest('/QuanLy/manager_update_doctor.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, ...payload })
    });
};

// 4. Kích hoạt / Vô hiệu hóa tài khoản bác sĩ (dùng chung toggle_status.php)
export const toggleBacSiStatus = async (taiKhoanId, status) => {
    return await apiRequest(`/TaiKhoan/toggle_status.php?id=${taiKhoanId}&status=${status}`, {
        method: 'POST'
    });
};

// 5. Xóa vĩnh viễn bác sĩ
export const forceDeleteBacSi = async (doctorId) => {
    return await apiRequest(`/QuanLy/manager_delete_doctor.php?id=${doctorId}`, {
        method: 'POST'
    });
};