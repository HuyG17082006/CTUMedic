import {
    getPatientList,
    updatePatient,
    updatePatientStatus,
    deletePatient
} from '../api/patients.js';

let globalPatients = [];
let currentPage = 1;

import { initPagination } from '../shared/pagination.js';

const paginationElement =
    document.querySelector('[data-pagination]');

const pagination = initPagination({
    container: paginationElement,

    async onPageChange(page) {

        params.pageNumber = page;

        await loadPatients();
    }
});


async function loadPatients(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('patientTableBody');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="9" class="table-empty-state">Đang tải dữ liệu...</td></tr>`;

    const params = {
        search: document.getElementById('searchPatientInput')?.value.trim() || '',
        status: document.getElementById('filterStatus')?.value || '',
        age_group: document.getElementById('filterAgeGroup')?.value || '',
        sort: document.getElementById('sortBy')?.value || 'id_desc',
        pageNumber: currentPage
    };

    try {
        const result = await getPatientList(params);

        if (result.status === 'success') {
            globalPatients = result.list || [];

            if (globalPatients.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" class="table-empty-state">Không tìm thấy bệnh nhân nào phù hợp.</td></tr>`;
                renderPagination(0, 1);
                return;
            }

            const pageNumber = result.pagination?.pageNumber || currentPage;
            const limit = result.pagination?.limit || 10;
            const totalPages = result.pagination?.totalPages || 1;
            const offset = (pageNumber - 1) * limit;

            renderPatientTable(globalPatients, offset);
            pagination.update(totalPages, pageNumber);
        } else {
            tbody.innerHTML = `<tr><td colspan="9" class="table-empty-state" style="color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="9" class="table-empty-state" style="color: #dc2626;">${error.message}</td></tr>`;
    }
}

function renderPatientTable(list, offset) {
    const tbody = document.getElementById('patientTableBody');
    let html = '';

    list.forEach((p, index) => {
        const safeName = (p.hoTen || '').replace(/'/g, "\\'");
        const isActive = Number(p.isActive) === 1;
        const rowClass = isActive ? '' : 'class="row-disabled"';

        const statusBadge = isActive
            ? `<span class="badge badge-active">Đang hoạt động</span>`
            : `<span class="badge badge-disabled">Đã vô hiệu hóa</span>`;

        // Đúng 9 cột khớp 100% với <thead> trong HTML
        html += `
            <tr ${rowClass}>
                <td>${offset + index + 1}</td>
                <td style="font-weight: 500;">${p.hoTen || 'Chưa cập nhật'}</td>
                <td>${p.username || ''}</td>
                <td>${p.ngaySinh || ''}</td>
                <td>${p.SDT || ''}</td>
                <td>${p.email || ''}</td>
                <td>${p.CCCD || ''}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="btn-action-group">
                        ${isActive ? `
                            <button type="button" class="btn-action btn-edit" onclick="openEditModal('${p.id}')">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                <span>Sửa</span>
                            </button>
                            <button type="button" class="btn-action btn-disable" onclick="togglePatientStatus('${p.taiKhoanId}', 0, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm7.31-4.69L7.69 3.69C9.05 2.63 10.75 2 12 2c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/></svg>
                                <span>Vô hiệu hóa</span>
                            </button>
                        ` : `
                            <button type="button" class="btn-action btn-activate" onclick="togglePatientStatus('${p.taiKhoanId}', 1, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Kích hoạt</span>
                            </button>
                            <button type="button" class="btn-action btn-force-delete" onclick="forceDeletePatient('${p.id}', '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                                <span>Xóa hẳn</span>
                            </button>
                        `}
                    </div>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

function renderPagination(totalPages, page) {
    const wrapper = document.getElementById('paginationWrapper');
    if (!wrapper) return;

    if (totalPages <= 1) {
        wrapper.innerHTML = '';
        return;
    }

    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `<button type="button" class="page-btn ${i === page ? 'active' : ''}" onclick="loadPatients(${i})">${i}</button>`;
    }
    wrapper.innerHTML = html;
}

async function togglePatientStatus(taiKhoanId, status, name) {
    const actionText = status === 1 ? 'kích hoạt' : 'vô hiệu hóa';
    if (!confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản của bệnh nhân "${name}"?`)) return;

    try {
        const result = await updatePatientStatus(taiKhoanId, status);
        if (result.status === 'success') {
            alert(result.message || `Đã ${actionText} tài khoản thành công!`);
            loadPatients(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert(err.message);
    }
}

async function forceDeletePatient(patientId, name) {
    if (!confirm(`CẢNH BÁO: Thao tác này sẽ xóa VĨNH VIỄN thông tin của bệnh nhân "${name}" khỏi hệ thống!\n\nBạn có chắc chắn muốn xóa không?`)) return;

    try {
        const result = await deletePatient(patientId);
        if (result.status === 'success') {
            alert(result.message || 'Đã xóa vĩnh viễn bệnh nhân!');
            loadPatients(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert(err.message);
    }
}

function openEditModal(patientId) {
    const p = globalPatients.find(item => item.id == patientId);
    if (!p) return;

    document.getElementById('editPatientId').value = p.id;
    document.getElementById('editHoTen').value = p.hoTen || '';
    document.getElementById('editNgaySinh').value = p.ngaySinh || '';
    document.getElementById('editSDT').value = p.SDT || '';
    document.getElementById('editEmail').value = p.email || '';
    document.getElementById('editCCCD').value = p.CCCD || '';

    const modal = document.getElementById('editPatientModal');
    if (modal) modal.style.display = 'flex';
}

function closeEditModal() {
    const modal = document.getElementById('editPatientModal');
    if (modal) modal.style.display = 'none';
}

window.loadPatients = loadPatients;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.togglePatientStatus = togglePatientStatus;
window.forceDeletePatient = forceDeletePatient;

document.addEventListener('DOMContentLoaded', function () {
    loadPatients(1);

    document.getElementById('btnSearchPatient')?.addEventListener('click', () => loadPatients(1));
    document.getElementById('searchPatientInput')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') loadPatients(1);
    });
    document.getElementById('filterStatus')?.addEventListener('change', () => loadPatients(1));
    document.getElementById('filterAgeGroup')?.addEventListener('change', () => loadPatients(1));
    document.getElementById('sortBy')?.addEventListener('change', () => loadPatients(1));

    document.getElementById('btnResetFilter')?.addEventListener('click', () => {
        if (document.getElementById('searchPatientInput')) document.getElementById('searchPatientInput').value = '';
        if (document.getElementById('filterStatus')) document.getElementById('filterStatus').value = '';
        if (document.getElementById('filterAgeGroup')) document.getElementById('filterAgeGroup').value = '';
        if (document.getElementById('sortBy')) document.getElementById('sortBy').value = 'id_desc';
        loadPatients(1);
    });

    document.getElementById('editPatientModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeEditModal();
    });

    document.getElementById('editPatientForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const id = document.getElementById('editPatientId').value;
        const payload = {
            hoTen: document.getElementById('editHoTen').value.trim(),
            ngaySinh: document.getElementById('editNgaySinh').value,
            SDT: document.getElementById('editSDT').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            CCCD: document.getElementById('editCCCD').value.trim()
        };

        try {
            const result = await updatePatient(id, payload);
            if (result.status === 'success') {
                alert(result.message || 'Cập nhật thông tin bệnh nhân thành công!');
                closeEditModal();
                loadPatients(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert(err.message);
        }
    });
});