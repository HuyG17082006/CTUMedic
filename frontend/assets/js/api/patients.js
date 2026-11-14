import apiRequest from './api_request.js';

// 1. Lấy danh sách bệnh nhân
export const getPatientList = async (params = {}) => {
    const cleanParams = Object.fromEntries(
        Object.entries(params).filter(([_, v]) => v !== '' && v !== null && v !== undefined)
    );
    const queryString = new URLSearchParams(cleanParams).toString();
    const url = queryString ? `/management/patients.php?${queryString}` : '/management/patients.php';

    return await apiRequest(url, { method: 'GET' });
};

// 2. Cập nhật thông tin bệnh nhân
export const updatePatient = async (id, payload) => {
    return await apiRequest('/management/manager_update_patient.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, ...payload })
    });
};

// 3. Kích hoạt / Vô hiệu hóa tài khoản
// (Bạn kiểm tra lại tên file thật trong thư mục backend/api/accounts/ hoặc backend/api/management/)
export const updatePatientStatus = async (taiKhoanId, status) => {
    return await apiRequest(`/accounts/toggle_status.php?id=${taiKhoanId}&status=${status}`, {
        method: 'POST'
    });
};

// 4. Xóa vĩnh viễn bệnh nhân
export const deletePatient = async (patientId) => {
    return await apiRequest(`/management/manager_delete_patient.php?id=${patientId}`, {
        method: 'POST'
    });
};