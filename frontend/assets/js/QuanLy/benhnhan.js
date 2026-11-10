let globalPatients = [];
let currentPage = 1;

/**
 * Tải danh sách bệnh nhân từ backend
 */
async function loadPatients(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('patientTableBody');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; padding: 24px; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

    const search = document.getElementById('searchPatientInput')?.value.trim() || '';
    const ageGroup = document.getElementById('filterAgeGroup')?.value || '';
    const sort = document.getElementById('sortBy')?.value || 'id_desc';

    const queryParams = new URLSearchParams({
        api: 'manager_get_patients',
        search: search,
        age_group: ageGroup,
        sort: sort,
        pageNumber: currentPage
    });

    try {
        const response = await fetch(`index.php?${queryParams.toString()}`, { 
            method: 'GET', 
            credentials: 'include' 
        });
        const result = await response.json();

        if (result.status === 'success') {
            globalPatients = result.list || [];
            
            if (globalPatients.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; padding: 24px; color: #6b7280;">Không tìm thấy bệnh nhân nào.</td></tr>`;
                renderPagination(0, 1);
                return;
            }

            const offset = (result.pagination.pageNumber - 1) * result.pagination.limit;
            renderPatientTable(globalPatients, offset);
            renderPagination(result.pagination.totalPages, result.pagination.pageNumber);
        } else {
            tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #dc2626; padding: 24px;">Lỗi: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="8" style="text-align: center; color: #dc2626; padding: 24px;">Lỗi kết nối máy chủ!</td></tr>`;
    }
}

/**
 * Render dữ liệu vào bảng HTML
 */
function renderPatientTable(list, offset) {
    const tbody = document.getElementById('patientTableBody');
    let html = '';

    list.forEach((p, index) => {
        const safeName = (p.hoTen || '').replace(/'/g, "\\'");
        const isActive = parseInt(p.isActive) === 1;

        // Xám dòng nếu tài khoản bị vô hiệu hóa
        const rowClass = isActive ? '' : 'class="row-disabled"';

        html += `
            <tr ${rowClass}>
                <td>${offset + index + 1}</td>
                <td style="font-weight: 500;">
                    ${p.hoTen || 'Chưa cập nhật'} 
                    ${!isActive ? '<small style="color:#ef4444; font-weight:normal;">(Đã vô hiệu hóa)</small>' : ''}
                </td>
                <td>${p.username || ''}</td>
                <td>${p.ngaySinh || ''}</td>
                <td>${p.SDT || ''}</td>
                <td>${p.email || ''}</td>
                <td>${p.CCCD || ''}</td>
                <td class="action-column">
                    <div class="btn-action-group">
                        ${isActive ? `
                            <!-- Trạng thái Hoạt động: Hiện Nút Sửa & Vô hiệu hóa -->
                            <button type="button" class="btn-action btn-edit" onclick="openEditModal('${p.id}')">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                <span>Sửa</span>
                            </button>
                            <button type="button" class="btn-action btn-disable" onclick="togglePatientStatus('${p.taiKhoanId}', 0, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm7.31-4.69L7.69 3.69C9.05 2.63 10.75 2 12 2c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/></svg>
                                <span>Vô hiệu hóa</span>
                            </button>
                        ` : `
                            <!-- Trạng thái Khóa: Hiện Nút Kích hoạt & Xóa hẳn -->
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

/**
 * Render nút Phân trang
 */
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

/**
 * Kích hoạt hoặc Vô hiệu hóa tài khoản
 */
async function togglePatientStatus(taiKhoanId, status, name) {
    const actionText = status === 1 ? 'kích hoạt' : 'vô hiệu hóa';
    if (!confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản của bệnh nhân "${name}"?`)) return;

    try {
        const response = await fetch(`index.php?api=manager_toggle_status&id=${taiKhoanId}&status=${status}`, {
            method: 'POST',
            credentials: 'include'
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || `Đã ${actionText} tài khoản thành công!`);
            loadPatients(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert('Không thể kết nối máy chủ!');
    }
}

/**
 * Xóa vĩnh viễn bệnh nhân (Tự xóa lichKham -> benhNhan -> taiKhoan)
 */
async function forceDeletePatient(patientId, name) {
    if (!confirm(`CẢNH BÁO: Thao tác này sẽ xóa VĨNH VIỄN tất cả lịch khám và thông tin của bệnh nhân "${name}" khỏi hệ thống!\n\nBạn có chắc chắn muốn xóa không?`)) return;

    try {
        const response = await fetch(`index.php?api=manager_force_delete_patient&id=${patientId}`, {
            method: 'POST',
            credentials: 'include'
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || 'Đã xóa vĩnh viễn bệnh nhân!');
            loadPatients(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert('Đã xảy ra lỗi khi xóa vĩnh viễn!');
    }
}

/**
 * Mở Modal Sửa thông tin bệnh nhân
 */
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

/**
 * Đóng Modal Sửa
 */
function closeEditModal() {
    const modal = document.getElementById('editPatientModal');
    if (modal) modal.style.display = 'none';
}

/**
 * Khởi tạo các event listener khi nạp trang
 */
document.addEventListener("DOMContentLoaded", function() {
    loadPatients(1);

    // Sự kiện tìm kiếm & lọc
    document.getElementById('btnSearchPatient')?.addEventListener('click', () => loadPatients(1));
    document.getElementById('searchPatientInput')?.addEventListener('keypress', (e) => { 
        if (e.key === 'Enter') loadPatients(1); 
    });
    document.getElementById('filterAgeGroup')?.addEventListener('change', () => loadPatients(1));
    document.getElementById('sortBy')?.addEventListener('change', () => loadPatients(1));

    // Nút đặt lại bộ lọc
    document.getElementById('btnResetFilter')?.addEventListener('click', () => {
        if (document.getElementById('searchPatientInput')) document.getElementById('searchPatientInput').value = '';
        if (document.getElementById('filterAgeGroup')) document.getElementById('filterAgeGroup').value = '';
        if (document.getElementById('sortBy')) document.getElementById('sortBy').value = 'id_desc';
        loadPatients(1);
    });

    // Form submit chỉnh sửa bệnh nhân
    document.getElementById('editPatientForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const payload = {
            id: document.getElementById('editPatientId').value,
            hoTen: document.getElementById('editHoTen').value.trim(),
            ngaySinh: document.getElementById('editNgaySinh').value,
            SDT: document.getElementById('editSDT').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            CCCD: document.getElementById('editCCCD').value.trim()
        };

        try {
            const res = await fetch('index.php?api=manager_update_patient', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify(payload)
            });
            const result = await res.json();

            if (result.status === 'success') {
                alert('Cập nhật thông tin bệnh nhân thành công!');
                closeEditModal();
                loadPatients(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert('Không thể kết nối cập nhật thông tin!');
        }
    });
});