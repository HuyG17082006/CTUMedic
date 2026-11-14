import apiRequest from './apiRequest.js';

// 1. Lấy danh sách bệnh nhân
export const getAllBenhNhan = async (params = {}) => {
    const cleanParams = Object.fromEntries(
        Object.entries(params).filter(([_, v]) => v !== '' && v !== null && v !== undefined)
    );
    const queryString = new URLSearchParams(cleanParams).toString();
    const url = queryString ? `/QuanLy/benhnhan.php?${queryString}` : '/QuanLy/benhnhan.php';

    return await apiRequest(url, { method: 'GET' });
};

// 2. Cập nhật thông tin bệnh nhân
export const updateBenhNhan = async (id, payload) => {
    return await apiRequest('/QuanLy/manager_update_patient.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, ...payload })
    });
};

// 3. Kích hoạt / Vô hiệu hóa tài khoản
// (Bạn kiểm tra lại tên file thật trong thư mục backend/api/TaiKhoan/ hoặc backend/api/QuanLy/)
export const toggleBenhNhanStatus = async (taiKhoanId, status) => {
    return await apiRequest(`/TaiKhoan/toggle_status.php?id=${taiKhoanId}&status=${status}`, {
        method: 'POST'
    });
};

// 4. Xóa vĩnh viễn bệnh nhân
export const forceDeleteBenhNhan = async (patientId) => {
    return await apiRequest(`/QuanLy/manager_delete_patient.php?id=${patientId}`, {
        method: 'POST'
    });
};