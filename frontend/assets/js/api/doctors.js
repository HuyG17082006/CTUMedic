import apiRequest from './api_request.js';

// 1. Lấy danh sách bác sĩ
export const getDoctorList = async (params = {}) => {
    const cleanParams = Object.fromEntries(
        Object.entries(params).filter(([_, v]) => v !== '' && v !== null && v !== undefined)
    );
    const queryString = new URLSearchParams(cleanParams).toString();
    const url = queryString ? `/management/doctors.php?${queryString}` : '/management/doctors.php';

    return await apiRequest(url, { method: 'GET' });
};

export const getFilterDoctorList = async (specialty) => {
    if (!specialty)
        return;
    const {list} = await apiRequest(`/doctors/get_doctor_list.php?specialty=${specialty}`, {
        method: 'GET'
    })

    return list
}

// 2. Thêm bác sĩ mới
export const createDoctor = async (payload) => {
    return await apiRequest('/management/manager_add_doctor.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    });
};

// 3. Cập nhật thông tin bác sĩ
export const updateDoctor = async (id, payload) => {
    return await apiRequest('/management/manager_update_doctor.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, ...payload })
    });
};

// 4. Kích hoạt / Vô hiệu hóa tài khoản bác sĩ (dùng chung toggle_status.php)
export const updateDoctorStatus = async (taiKhoanId, status) => {
    return await apiRequest(`/accounts/toggle_status.php?id=${taiKhoanId}&status=${status}`, {
        method: 'POST'
    });
};

// 5. Xóa vĩnh viễn bác sĩ
export const deleteDoctor = async (doctorId) => {
    return await apiRequest(`/management/manager_delete_doctor.php?id=${doctorId}`, {
        method: 'POST'
    });
};