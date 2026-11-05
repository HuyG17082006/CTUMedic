<div class="appointment-container">
    <!-- Thanh tìm kiếm và bộ lọc -->
    <div class="appointment-filter-card" style="margin-bottom: 20px; background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="flex: 2; min-width: 260px;">
                <input type="text" id="searchPatientInput" placeholder="Tìm kiếm theo họ tên, SĐT, CCCD, email..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none;">
            </div>

            <div style="flex: 1; min-width: 150px;">
                <select id="filterAgeGroup" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; background: #fff;">
                    <option value="">-- Độ tuổi --</option>
                    <option value="all">Tất cả độ tuổi</option>
                    <option value="under_18">Trẻ em (&lt; 18 tuổi)</option>
                    <option value="18_59">Người lớn (18 - 59 tuổi)</option>
                    <option value="over_60">Người cao tuổi (&ge; 60 tuổi)</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <select id="sortBy" style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none; background: #fff;">
                    <option value="id_desc">Mới nhất</option>
                    <option value="id_asc">Cũ nhất</option>
                    <option value="name_asc">Tên (A -> Z)</option>
                    <option value="name_desc">Tên (Z -> A)</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="button" class="btn-primary" id="btnSearchPatient" style="padding: 10px 18px; font-size: 14px; cursor: pointer; border-radius: 8px;">Tìm kiếm</button>
                <button type="button" id="btnResetFilter" style="padding: 10px 14px; font-size: 14px; cursor: pointer; border: 1px solid #d1d5db; background: #f9fafb; border-radius: 8px; color: #374151;">Đặt lại</button>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bệnh nhân -->
    <div class="appointment-table-card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6;">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Danh sách bệnh nhân trong hệ thống</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f9fafb; color: #4b5563; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 16px; font-weight: 600;">STT</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Họ và tên</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Tên đăng nhập</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Ngày sinh</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Số điện thoại</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Email</th>
                        <th style="padding: 12px 16px; font-weight: 600;">CCCD</th>
                        <th style="padding: 12px 16px; font-weight: 600; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <tr>
                        <td colspan="8" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Sửa Bệnh Nhân -->
<div id="editPatientModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 500px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; font-size: 18px; color: #111827;">Chỉnh sửa thông tin bệnh nhân</h3>
        <form id="editPatientForm">
            <input type="hidden" id="editPatientId">
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Họ và tên</label>
                <input type="text" id="editHoTen" required style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Ngày sinh</label>
                <input type="date" id="editNgaySinh" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Số điện thoại</label>
                <input type="text" id="editSDT" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 12px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">Email</label>
                <input type="email" id="editEmail" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 13px; font-weight: 500; margin-bottom: 4px;">CCCD</label>
                <input type="text" id="editCCCD" style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeEditModal()" style="padding: 8px 16px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer;">Hủy</button>
                <button type="submit" style="padding: 8px 16px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer;">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Xác Nhận Xóa Bệnh Nhân (Custom Popup HTML/CSS Thuần) -->
<div id="confirmDeletePatientModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1050; align-items: center; justify-content: center;">
    <div style="background: #ffffff; width: 100%; max-width: 400px; border-radius: 12px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); text-align: center;">
        <div style="width: 48px; height: 48px; background: #fee2e2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 24px; font-weight: bold;">!</div>
        <h3 style="margin: 0 0 8px; font-size: 18px; color: #111827;">Xác nhận xóa bệnh nhân?</h3>
        <p id="deletePatientConfirmMessage" style="font-size: 14px; color: #4b5563; margin: 0 0 20px;"></p>
        
        <input type="hidden" id="deletePatientId">
        
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" onclick="closeDeletePatientModal()" style="padding: 9px 18px; border: 1px solid #d1d5db; background: #fff; border-radius: 6px; cursor: pointer; font-size: 14px; color: #374151;">Hủy bỏ</button>
            <button type="button" onclick="confirmExecuteDeletePatient()" style="padding: 9px 18px; background: #ef4444; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500;">Đồng ý xóa</button>
        </div>
    </div>
</div>

<script>
    let globalPatients = [];

    async function loadPatients() {
        const tbody = document.getElementById('patientTableBody');
        tbody.innerHTML = `<tr><td colspan="8" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

        const search = document.getElementById('searchPatientInput').value.trim();
        const ageGroup = document.getElementById('filterAgeGroup').value;
        const sort = document.getElementById('sortBy').value;

        const queryParams = new URLSearchParams({
            api: 'manager_get_patients',
            search: search,
            age_group: ageGroup,
            sort: sort
        });

        try {
            const response = await fetch(`index.php?${queryParams.toString()}`, { method: 'GET', credentials: 'include' });
            const result = await response.json();

            if (result.status === 'success') {
                globalPatients = result.data;
                if (globalPatients.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" style="padding: 24px; text-align: center; color: #6b7280;">Không tìm thấy bệnh nhân nào.</td></tr>`;
                    return;
                }

                let html = '';
                globalPatients.forEach((p, index) => {
                    const safeName = (p.hoTen || '').replace(/'/g, "\\'");
                    html += `
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 14px 16px;">${index + 1}</td>
                            <td style="padding: 14px 16px; font-weight: 500;">${p.hoTen || 'Chưa cập nhật'}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.username || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.ngaySinh || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.SDT || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.email || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.CCCD || ''}</td>
                            <td style="padding: 14px 16px; text-align: center;">
                                <button type="button" onclick="openEditModal(${p.id})" 
                                    style="padding: 4px 10px; background: #eab308; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px; margin-right: 4px;">Sửa</button>
                                <button type="button" onclick="deletePatient(${p.id}, '${safeName}')" 
                                    style="padding: 4px 10px; background: #ef4444; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `<tr><td colspan="8" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
            }
        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="8" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi kết nối máy chủ!</td></tr>`;
        }
    }

    // Modal Sửa Bệnh Nhân
    function openEditModal(patientId) {
        const p = globalPatients.find(item => item.id == patientId);
        if (!p) return;

        document.getElementById('editPatientId').value = p.id;
        document.getElementById('editHoTen').value = p.hoTen || '';
        document.getElementById('editNgaySinh').value = p.ngaySinh || '';
        document.getElementById('editSDT').value = p.SDT || '';
        document.getElementById('editEmail').value = p.email || '';
        document.getElementById('editCCCD').value = p.CCCD || '';

        document.getElementById('editPatientModal').style.display = 'flex';
    }

    function closeEditModal() {
        document.getElementById('editPatientModal').style.display = 'none';
    }

    // Modal Xác Nhận Xóa Bệnh Nhân (Mới)
    function deletePatient(id, name) {
        document.getElementById('deletePatientId').value = id;
        document.getElementById('deletePatientConfirmMessage').innerText = `Bạn có chắc chắn muốn vô hiệu hóa tài khoản bệnh nhân "${name}" không?`;
        document.getElementById('confirmDeletePatientModal').style.display = 'flex';
    }

    function closeDeletePatientModal() {
        document.getElementById('confirmDeletePatientModal').style.display = 'none';
    }

    async function confirmExecuteDeletePatient() {
        const id = document.getElementById('deletePatientId').value;
        closeDeletePatientModal();

        try {
            const response = await fetch(`index.php?api=manager_delete_patient&id=${id}`, {
                method: 'GET',
                credentials: 'include'
            });

            const result = await response.json();

            if (result.status === 'success') {
                alert(result.message || 'Đã vô hiệu hóa tài khoản thành công!');
                loadPatients();
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (error) {
            console.error(error);
            alert('Đã xảy ra lỗi kết nối máy chủ!');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadPatients();

        document.getElementById('btnSearchPatient').addEventListener('click', loadPatients);
        document.getElementById('searchPatientInput').addEventListener('keypress', (e) => { if (e.key === 'Enter') loadPatients(); });
        document.getElementById('filterAgeGroup').addEventListener('change', loadPatients);
        document.getElementById('sortBy').addEventListener('change', loadPatients);
        
        document.getElementById('btnResetFilter').addEventListener('click', () => {
            document.getElementById('searchPatientInput').value = '';
            document.getElementById('filterAgeGroup').value = '';
            document.getElementById('sortBy').value = 'id_desc';
            loadPatients();
        });

        // Submit form sửa bệnh nhân
        document.getElementById('editPatientForm').addEventListener('submit', async function(e) {
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
                    alert('Cập nhật thông tin thành công!');
                    closeEditModal();
                    loadPatients();
                } else {
                    alert('Lỗi: ' + result.message);
                }
            } catch (err) {
                alert('Không thể cập nhật thông tin!');
            }
        });
    });
</script>