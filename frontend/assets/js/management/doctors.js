import {
    getDoctorList,
    createDoctor,
    updateDoctor,
    updateDoctorStatus,
    deleteDoctor
} from '../api/doctors.js';

import { initPagination } from '../shared/pagination.js';

let globalDoctors = [];
let currentPage = 1;

const paginationElement =
    document.querySelector('[data-pagination]');

const pagination = initPagination({
    container: paginationElement,

    async onPageChange(page) {

        params.pageNumber = page;

        await loadDoctors();
    }
});


async function loadDoctors(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('doctorTableBody');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="7" class="table-empty-state">Đang tải dữ liệu...</td></tr>`;

    const params = {
        search: document.getElementById('searchDoctorInput')?.value.trim() || '',
        specialty: document.getElementById('filterSpecialty')?.value || '',
        status: document.getElementById('filterDoctorStatus')?.value || '',
        sort: document.getElementById('sortDoctorBy')?.value || 'id_desc',
        pageNumber: currentPage
    };

    try {
        const result = await getDoctorList(params);

        if (result.status === 'success') {
            globalDoctors = result.list || [];

            if (globalDoctors.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="table-empty-state">Không tìm thấy bác sĩ nào phù hợp.</td></tr>`;
                renderPagination(0, 1);
                return;
            }

            const pageNumber = result.pagination?.pageNumber || currentPage;
            const limit = result.pagination?.limit || 10;
            const totalPages = result.pagination?.totalPages || 1;
            const offset = (pageNumber - 1) * limit;

            renderDoctorTable(globalDoctors, offset);
            pagination.update(
                pageNumber,
                totalPages
            );
        } else {
            tbody.innerHTML = `<tr><td colspan="7" class="table-empty-state" style="color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="7" class="table-empty-state" style="color: #dc2626;">${error.message}</td></tr>`;
    }
}

function renderDoctorTable(list, offset) {
    const tbody = document.getElementById('doctorTableBody');
    let html = '';

    list.forEach((d, index) => {
        const safeName = (d.hoTen || '').replace(/'/g, "\\'");
        const isActive = Number(d.isActive) === 1;
        const rowClass = isActive ? '' : 'class="row-disabled"';

        const statusBadge = isActive
            ? `<span class="badge badge-active">Đang hoạt động</span>`
            : `<span class="badge badge-disabled">Đã vô hiệu hóa</span>`;

        html += `
            <tr ${rowClass}>
                <td>${offset + index + 1}</td>
                <td style="font-weight: 500;">${d.hoTen || ''}</td>
                <td>${d.username || ''}</td>
                <td>${d.email || ''}</td>
                <td>${d.chuyenKhoa || ''}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="btn-action-group">
                        ${isActive ? `
                            <button type="button" class="btn-action btn-edit" onclick="openEditDoctorModal('${d.id}')">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                <span>Sửa</span>
                            </button>
                            <button type="button" class="btn-action btn-disable" onclick="toggleDoctorStatus('${d.taiKhoanId}', 0, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm7.31-4.69L7.69 3.69C9.05 2.63 10.75 2 12 2c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/></svg>
                                <span>Vô hiệu hóa</span>
                            </button>
                        ` : `
                            <button type="button" class="btn-action btn-activate" onclick="toggleDoctorStatus('${d.taiKhoanId}', 1, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                <span>Kích hoạt</span>
                            </button>
                            <button type="button" class="btn-action btn-force-delete" onclick="forceDeleteDoctor('${d.id}', '${safeName}')">
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
    const wrapper = document.getElementById('doctorPaginationWrapper');
    if (!wrapper) return;

    if (totalPages <= 1) {
        wrapper.innerHTML = '';
        return;
    }

    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `<button type="button" class="page-btn ${i === page ? 'active' : ''}" onclick="loadDoctors(${i})">${i}</button>`;
    }
    wrapper.innerHTML = html;
}

function openAddDoctorModal() {
    document.getElementById('addDoctorForm')?.reset();
    const modal = document.getElementById('addDoctorModal');
    if (modal) modal.style.display = 'flex';
}

function closeAddDoctorModal() {
    const modal = document.getElementById('addDoctorModal');
    if (modal) modal.style.display = 'none';
}

function openEditDoctorModal(doctorId) {
    const d = globalDoctors.find(item => item.id == doctorId);
    if (!d) return;

    document.getElementById('editDoctorId').value = d.id;
    document.getElementById('editDoctorHoTen').value = d.hoTen || '';
    document.getElementById('editDoctorEmail').value = d.email || '';
    document.getElementById('editDoctorChuyenKhoa').value = d.chuyenKhoa || 'Nội tổng quát';

    const modal = document.getElementById('editDoctorModal');
    if (modal) modal.style.display = 'flex';
}

function closeEditDoctorModal() {
    const modal = document.getElementById('editDoctorModal');
    if (modal) modal.style.display = 'none';
}

async function toggleDoctorStatus(taiKhoanId, status, name) {
    const actionText = status === 1 ? 'kích hoạt' : 'vô hiệu hóa';
    if (!confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản của bác sĩ "${name}"?`)) return;

    try {
        const result = await updateDoctorStatus(taiKhoanId, status);
        if (result.status === 'success') {
            alert(result.message || `Đã ${actionText} tài khoản thành công!`);
            loadDoctors(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert(err.message);
    }
}

async function forceDeleteDoctor(doctorId, name) {
    if (!confirm(`CẢNH BÁO: Thao tác này sẽ xóa VĨNH VIỄN bác sĩ "${name}" khỏi hệ thống!\n\nBạn có chắc chắn muốn xóa không?`)) return;

    try {
        const result = await deleteDoctor(doctorId);
        if (result.status === 'success') {
            alert(result.message || 'Đã xóa vĩnh viễn bác sĩ!');
            loadDoctors(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert(err.message);
    }
}

window.loadDoctors = loadDoctors;
window.openAddDoctorModal = openAddDoctorModal;
window.closeAddDoctorModal = closeAddDoctorModal;
window.openEditDoctorModal = openEditDoctorModal;
window.closeEditDoctorModal = closeEditDoctorModal;
window.toggleDoctorStatus = toggleDoctorStatus;
window.forceDeleteDoctor = forceDeleteDoctor;

document.addEventListener('DOMContentLoaded', function () {
    loadDoctors(1);

    document.getElementById('btnSearchDoctor')?.addEventListener('click', () => loadDoctors(1));
    document.getElementById('searchDoctorInput')?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') loadDoctors(1);
    });
    document.getElementById('filterSpecialty')?.addEventListener('change', () => loadDoctors(1));
    document.getElementById('filterDoctorStatus')?.addEventListener('change', () => loadDoctors(1));
    document.getElementById('sortDoctorBy')?.addEventListener('change', () => loadDoctors(1));

    document.getElementById('btnResetDoctorFilter')?.addEventListener('click', () => {
        if (document.getElementById('searchDoctorInput')) document.getElementById('searchDoctorInput').value = '';
        if (document.getElementById('filterSpecialty')) document.getElementById('filterSpecialty').value = '';
        if (document.getElementById('filterDoctorStatus')) document.getElementById('filterDoctorStatus').value = '';
        if (document.getElementById('sortDoctorBy')) document.getElementById('sortDoctorBy').value = 'id_desc';
        loadDoctors(1);
    });

    document.getElementById('addDoctorModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeAddDoctorModal();
    });
    document.getElementById('editDoctorModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeEditDoctorModal();
    });

    // Submit Thêm bác sĩ
    document.getElementById('addDoctorForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const payload = {
            username: this.querySelector('#addDoctorUsername')?.value.trim() || '',
            password: this.querySelector('#addDoctorPassword')?.value.trim() || '',
            hoTen: this.querySelector('#addDoctorHoTen')?.value.trim() || '',
            email: this.querySelector('#addDoctorEmail')?.value.trim() || '',
            chuyenKhoa: this.querySelector('#addDoctorChuyenKhoa')?.value || ''
        };

        try {
            const result = await createDoctor(payload);
            if (result.status === 'success') {
                alert(result.message || 'Thêm bác sĩ thành công!');
                closeAddDoctorModal();
                loadDoctors(1);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert(err.message);
        }
    });

    // Submit Sửa bác sĩ
    document.getElementById('editDoctorForm')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = this.querySelector('#editDoctorId')?.value;
        const payload = {
            hoTen: this.querySelector('#editDoctorHoTen')?.value.trim() || '',
            email: this.querySelector('#editDoctorEmail')?.value.trim() || '',
            chuyenKhoa: this.querySelector('#editDoctorChuyenKhoa')?.value || ''
        };

        try {
            const result = await updateDoctor(id, payload);
            if (result.status === 'success') {
                alert(result.message || 'Cập nhật thông tin bác sĩ thành công!');
                closeEditDoctorModal();
                loadDoctors(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert(err.message);
        }
    });
});