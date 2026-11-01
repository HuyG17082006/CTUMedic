<div class="appointment-container">
    <!-- Thanh tìm kiếm và bộ lọc -->
    <div class="appointment-filter-card" style="margin-bottom: 20px; background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 280px;">
                <input type="text" id="searchPatientInput" placeholder="Tìm kiếm theo họ tên, số điện thoại, CCCD hoặc tài khoản..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none;">
            </div>
            <div>
                <button type="button" class="btn-primary" id="btnSearchPatient" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">Tìm kiếm</button>
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
                    </tr>
                </thead>
                <tbody id="patientTableBody">
                    <tr>
                        <td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu danh sách bệnh nhân...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function loadPatients(keyword = '') {
        const tbody = document.getElementById('patientTableBody');
        tbody.innerHTML = `<tr><td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

        try {
            const response = await fetch(`index.php?api=manager_get_patients&search=${encodeURIComponent(keyword)}`, {
                method: 'GET',
                credentials: 'include'
            });

            const result = await response.json();

            if (result.status === 'success') {
                const patients = result.data;
                if (patients.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="7" style="padding: 24px; text-align: center; color: #6b7280;">Không tìm thấy bệnh nhân nào trong hệ thống.</td></tr>`;
                    return;
                }

                let html = '';
                patients.forEach((p, index) => {
                    html += `
                        <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 14px 16px; color: #111827;">${index + 1}</td>
                            <td style="padding: 14px 16px; font-weight: 500; color: #111827;">${p.hoTen || 'Chưa cập nhật'}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.username || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.ngaySinh || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.SDT || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.email || ''}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${p.CCCD || ''}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `<tr><td colspan="7" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error(error);
            tbody.innerHTML = `<tr><td colspan="7" style="padding: 24px; text-align: center; color: #dc2626;">Đã xảy ra lỗi kết nối máy chủ!</td></tr>`;
        }
    }

    // Tự động tải danh sách khi vừa vào trang
    document.addEventListener("DOMContentLoaded", function() {
        loadPatients();

        // Xử lý sự kiện tìm kiếm khi bấm nút hoặc nhấn Enter
        const searchInput = document.getElementById('searchPatientInput');
        const searchBtn = document.getElementById('btnSearchPatient');

        searchBtn.addEventListener('click', () => {
            loadPatients(searchInput.value.trim());
        });

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                loadPatients(searchInput.value.trim());
            }
        });
    });
</script>