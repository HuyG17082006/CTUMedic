// ==============================
// QUẢN LÝ BÁC SĨ
// ==============================

const doctorTableBody = document.getElementById('doctorTableBody');

const searchDoctorInput = document.getElementById('searchDoctorInput');

const btnSearchDoctor = document.getElementById('btnSearchDoctor');


// Chuyển dữ liệu thành HTML an toàn
function escapeHTML(value) {
    return String(value ?? '').replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;'
    })[char]);
}


// Hiển thị thông báo trong bảng
function renderDoctorMessage(message, isError = false) {
    doctorTableBody.innerHTML = `
        <tr>
            <td colspan="5"
                class="doctor-table-message ${isError ? 'error' : ''}">
                ${escapeHTML(message)}
            </td>
        </tr>
    `;
}


// Lấy danh sách bác sĩ
async function loadDoctors(keyword = '') {

    renderDoctorMessage('Đang tải dữ liệu...');

    try {

        const response = await fetch(
            `/dashboard/CTUMedic/backend/api/QuanLy/benhnhan.php?search=${encodeURIComponent(keyword)}`,
            {
                method: 'GET',
                credentials: 'include'
            }
        );

        if (!response.ok) {
            throw new Error('Không thể kết nối máy chủ.');
        }

        const result = await response.json();

        if (result.status !== 'success') {
            renderDoctorMessage(
                result.message || 'Không thể tải danh sách bác sĩ.',
                true
            );
            return;
        }

        const doctors = result.data;

        if (!Array.isArray(doctors) || doctors.length === 0) {
            renderDoctorMessage('Không tìm thấy bác sĩ nào trong hệ thống.');
            return;
        }

        renderDoctors(doctors);

    } catch (error) {

        console.error('Lỗi tải danh sách bác sĩ:', error);

        renderDoctorMessage(
            'Đã xảy ra lỗi kết nối máy chủ!',
            true
        );
    }
}


// Render danh sách bác sĩ
function renderDoctors(doctors) {

    doctorTableBody.innerHTML = doctors.map((doctor, index) => `
        <tr>
            <td>${index + 1}</td>

            <td class="doctor-name">
                BS. ${escapeHTML(doctor.hoTen || 'Chưa cập nhật')}
            </td>

            <td>
                ${escapeHTML(doctor.username || '')}
            </td>

            <td class="doctor-specialty">
                ${escapeHTML(doctor.chuyenKhoa || 'Chưa phân khoa')}
            </td>

            <td>
                ${escapeHTML(doctor.email || '')}
            </td>
        </tr>
    `).join('');
}


// Khởi tạo sự kiện
function initDoctorManagement() {

    if (!doctorTableBody || !searchDoctorInput || !btnSearchDoctor) {
        return;
    }

    // Tải danh sách ban đầu
    loadDoctors();

    // Tìm kiếm bằng nút
    btnSearchDoctor.addEventListener('click', () => {
        loadDoctors(searchDoctorInput.value.trim());
    });

    // Tìm kiếm bằng Enter
    searchDoctorInput.addEventListener('keydown', event => {

        if (event.key === 'Enter') {
            loadDoctors(searchDoctorInput.value.trim());
        }

    });

}


// Khởi chạy
initDoctorManagement();