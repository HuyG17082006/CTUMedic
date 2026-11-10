let globalDoctors = [];
let currentPage = 1;

// Hàm băm SHA-1 bằng Crypto API của JS
async function sha1(str) {
    const buffer = new TextEncoder().encode(str);
    const digest = await crypto.subtle.digest('SHA-1', buffer);
    return Array.from(new Uint8Array(digest))
        .map(x => x.toString(16).padStart(2, '0'))
        .join('');
}

// 1. Tải danh sách bác sĩ
async function loadDoctors(page = 1) {
    currentPage = page;
    const tbody = document.getElementById('doctorTableBody');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

    const search = document.getElementById('searchDoctorInput')?.value.trim() || '';
    const queryParams = new URLSearchParams({
        api: 'manager_get_doctors',
        search: search,
        pageNumber: currentPage
    });

    try {
        const response = await fetch(`index.php?${queryParams.toString()}`, { method: 'GET', credentials: 'include' });
        const result = await response.json();

        if (result.status === 'success') {
            globalDoctors = result.list || [];
            
            if (globalDoctors.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #6b7280;">Không tìm thấy bác sĩ nào.</td></tr>`;
                renderPagination(0, 1);
                return;
            }

            const offset = (result.pagination.pageNumber - 1) * result.pagination.limit;
            renderDoctorTable(globalDoctors, offset);
            renderPagination(result.pagination.totalPages, result.pagination.pageNumber);
        } else {
            tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="6" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi kết nối máy chủ!</td></tr>`;
    }
}

// 2. Render dữ liệu vào bảng
function renderDoctorTable(list, offset) {
    const tbody = document.getElementById('doctorTableBody');
    let html = '';

    list.forEach((d, index) => {
        const safeName = (d.hoTen || '').replace(/'/g, "\\'");
        const isActive = parseInt(d.isActive) === 1;
        const rowClass = isActive ? '' : 'class="row-disabled"';

        html += `
            <tr ${rowClass}>
                <td>${offset + index + 1}</td>
                <td style="font-weight: 500;">
                    ${d.hoTen || 'Chưa cập nhật'} 
                    ${!isActive ? '<small style="color:#ef4444; font-weight:normal;">(Đã vô hiệu hóa)</small>' : ''}
                </td>
                <td>${d.username || ''}</td>
                <td>${d.chuyenKhoa || ''}</td>
                <td>${d.email || ''}</td>
                <td class="action-column">
                    <div class="btn-action-group">
                        ${isActive ? `
                            <!-- Trạng thái Hoạt động: Sửa & Vô hiệu hóa -->
                            <button type="button" class="btn-action btn-edit" onclick="openEditDoctorModal('${d.id}')">
                                <svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                                <span>Sửa</span>
                            </button>
                            <button type="button" class="btn-action btn-disable" onclick="toggleDoctorStatus('${d.taiKhoanId}', 0, '${safeName}')">
                                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm7.31-4.69L7.69 3.69C9.05 2.63 10.75 2 12 2c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/></svg>
                                <span>Vô hiệu hóa</span>
                            </button>
                        ` : `
                            <!-- Trạng thái Khóa: Kích hoạt & Xóa hẳn -->
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

// 3. Render Phân Trang
function renderPagination(totalPages, page) {
    const wrapper = document.getElementById('paginationWrapper');
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

// 4. Kích hoạt hoặc Vô hiệu hóa Bác sĩ
async function toggleDoctorStatus(taiKhoanId, status, name) {
    const actionText = status === 1 ? 'kích hoạt' : 'vô hiệu hóa';
    if (!confirm(`Bạn có chắc chắn muốn ${actionText} tài khoản của bác sĩ "${name}"?`)) return;

    try {
        const response = await fetch(`index.php?api=manager_toggle_status&id=${taiKhoanId}&status=${status}`, {
            method: 'POST',
            credentials: 'include'
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || `Đã ${actionText} tài khoản bác sĩ thành công!`);
            loadDoctors(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert('Không thể kết nối máy chủ!');
    }
}

// 5. Xóa hẳn Bác sĩ khỏi CSDL (Xóa lichKham -> bacSi -> taiKhoan)
async function forceDeleteDoctor(doctorId, name) {
    if (!confirm(`CẢNH BÁO: Thao tác này sẽ xóa VĨNH VIỄN tất cả lịch khám và thông tin của bác sĩ "${name}" khỏi hệ thống!\n\nBạn có chắc chắn muốn xóa không?`)) return;

    try {
        const response = await fetch(`index.php?api=manager_force_delete_doctor&id=${doctorId}`, {
            method: 'POST',
            credentials: 'include'
        });
        const result = await response.json();

        if (result.status === 'success') {
            alert(result.message || 'Đã xóa vĩnh viễn bác sĩ!');
            loadDoctors(currentPage);
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (err) {
        alert('Đã xảy ra lỗi khi xóa vĩnh viễn!');
    }
}

// 6. Điều khiển Modal Thêm & Sửa
function openAddDoctorModal() { document.getElementById('addDoctorModal').style.display = 'flex'; }
function closeAddDoctorModal() { 
    document.getElementById('addDoctorModal').style.display = 'none'; 
    document.getElementById('addDoctorForm').reset();
}

function openEditDoctorModal(doctorId) {
    const d = globalDoctors.find(item => item.id == doctorId);
    if (!d) return;

    document.getElementById('editDoctorId').value = d.id;
    document.getElementById('editHoTen').value = d.hoTen || '';
    document.getElementById('editChuyenKhoa').value = d.chuyenKhoa || '';
    document.getElementById('editEmail').value = d.email || '';

    document.getElementById('editDoctorModal').style.display = 'flex';
}
function closeEditDoctorModal() { document.getElementById('editDoctorModal').style.display = 'none'; }

// 7. Gắn sự kiện khi DOM tải xong
document.addEventListener("DOMContentLoaded", function() {
    loadDoctors(1);

    // Sự kiện Tìm kiếm & Đặt lại
    document.getElementById('btnSearchDoctor')?.addEventListener('click', () => loadDoctors(1));
    document.getElementById('searchDoctorInput')?.addEventListener('keypress', (e) => { 
        if (e.key === 'Enter') loadDoctors(1); 
    });
    document.getElementById('btnResetDoctorFilter')?.addEventListener('click', () => {
        if (document.getElementById('searchDoctorInput')) document.getElementById('searchDoctorInput').value = '';
        loadDoctors(1);
    });

    // Form Thêm Bác sĩ
    document.getElementById('addDoctorForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const rawPassword = document.getElementById('addPassword').value;
        const passwordHash = await sha1(rawPassword);

        const payload = {
            username: document.getElementById('addUsername').value.trim(),
            password: passwordHash,
            hoTen: document.getElementById('addHoTen').value.trim(),
            chuyenKhoa: document.getElementById('addChuyenKhoa').value.trim(),
            email: document.getElementById('addEmail').value.trim()
        };

        try {
            const res = await fetch('index.php?api=manager_add_doctor', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify(payload)
            });
            const result = await res.json();

            if (result.status === 'success') {
                alert('Thêm bác sĩ thành công!');
                closeAddDoctorModal();
                loadDoctors(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert('Không thể kết nối máy chủ!');
        }
    });

    // Form Sửa Bác sĩ
    document.getElementById('editDoctorForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const payload = {
            id: document.getElementById('editDoctorId').value,
            hoTen: document.getElementById('editHoTen').value.trim(),
            chuyenKhoa: document.getElementById('editChuyenKhoa').value.trim(),
            email: document.getElementById('editEmail').value.trim()
        };

        try {
            const res = await fetch('index.php?api=manager_update_doctor', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                credentials: 'include',
                body: JSON.stringify(payload)
            });
            const result = await res.json();

            if (result.status === 'success') {
                alert('Cập nhật thông tin bác sĩ thành công!');
                closeEditDoctorModal();
                loadDoctors(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (err) {
            alert('Không thể cập nhật thông tin!');
        }
    });
});