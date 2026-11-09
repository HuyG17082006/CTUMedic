const tbody = document.getElementById('patientTableBody');
const searchInput = document.getElementById('searchPatientInput');
const searchBtn = document.getElementById('btnSearchPatient');

const TABLE_COLSPAN = 7;

// Tạo ô thông báo
function showPatientMessage(message, isError = false) {
    const row = document.createElement('tr');
    const cell = document.createElement('td');

    cell.colSpan = TABLE_COLSPAN;
    cell.className = isError
        ? 'patient-table-message patient-table-message--error'
        : 'patient-table-message';

    cell.textContent = message;

    row.appendChild(cell);
    tbody.replaceChildren(row);
}

// Tạo ô dữ liệu
function createCell(value, className = '') {
    const cell = document.createElement('td');

    cell.textContent = value ?? 'Chưa cập nhật';

    if (className) {
        cell.className = className;
    }

    return cell;
}

// Tạo ô trạng thái
function createStatusCell(isActive) {
    const cell = document.createElement('td');
    const status = document.createElement('span');

    status.classList.add('patient-status');

    if (Number(isActive) === 1) {
        status.classList.add('patient-status--active');
        status.textContent = 'Hoạt động';
    } else {
        status.classList.add('patient-status--locked');
        status.textContent = 'Đã khóa';
    }

    cell.appendChild(status);

    return cell;
}

// Tạo ô thao tác
function createActionCell(patientId) {
    const cell = document.createElement('td');
    const button = document.createElement('button');
    const icon = document.createElement('i');

    button.type = 'button';
    button.className = 'patient-action-btn';
    button.title = 'Xem chi tiết';
    button.dataset.patientId = patientId;

    icon.className = 'fa-solid fa-eye';

    button.appendChild(icon);
    cell.appendChild(button);

    return cell;
}

// Tạo một dòng bệnh nhân
function createPatientRow(patient, index) {
    const row = document.createElement('tr');

    row.appendChild(createCell(index + 1));
    row.appendChild(createCell(patient.hoTen));
    row.appendChild(createCell(patient.ngaySinh));
    row.appendChild(createCell(patient.SDT));
    row.appendChild(createCell(patient.email));
    row.appendChild(createStatusCell(patient.isActive));
    row.appendChild(createActionCell(patient.id));

    return row;
}

// Tải danh sách bệnh nhân
async function loadPatients(keyword = '') {
    showPatientMessage('Đang tải dữ liệu...');

    try {
        const response = await fetch(
            `/dashboard/CTUMedic/backend/api/QuanLy/benhnhan.php?search=${encodeURIComponent(keyword)}`,
        );

        const result = await response.json();

        if (result.status !== 'success' || !response.ok) {
            showPatientMessage(
                result.message || 'Không thể tải danh sách bệnh nhân.',
                true
            );
            return;
        }

        const patients = result.data;

        if (!Array.isArray(patients) || patients.length === 0) {
            showPatientMessage('Không tìm thấy bệnh nhân nào trong hệ thống.');
            return;
        }

        const fragment = document.createDocumentFragment();

        patients.forEach((patient, index) => {
            fragment.appendChild(createPatientRow(patient, index));
        });

        tbody.replaceChildren(fragment);

    } catch (error) {
        console.error('Lỗi lấy danh sách bệnh nhân:', error);
        console.log(error)
        showPatientMessage(
            'Đã xảy ra lỗi kết nối máy chủ!',
            true
        );
    }
}

// Khởi tạo sự kiện
document.addEventListener('DOMContentLoaded', () => {
    loadPatients();

    searchBtn.addEventListener('click', () => {
        loadPatients(searchInput.value.trim());
    });

    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            loadPatients(searchInput.value.trim());
        }
    });
});