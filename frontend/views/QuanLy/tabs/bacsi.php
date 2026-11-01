<div class="appointment-container">
    <!-- Thanh tìm kiếm và bộ lọc -->
    <div class="appointment-filter-card" style="margin-bottom: 20px; background: #ffffff; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 280px;">
                <input type="text" id="searchDoctorInput" placeholder="Tìm kiếm theo tên bác sĩ, chuyên khoa hoặc email..." 
                    style="width: 100%; padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; outline: none;">
            </div>
            <div>
                <button type="button" class="btn-primary" id="btnSearchDoctor" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">Tìm kiếm</button>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách bác sĩ -->
    <div class="appointment-table-card" style="background: #ffffff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid #f3f4f6;">
            <h3 style="font-size: 18px; font-weight: 600; color: #111827; margin: 0;">Danh sách bác sĩ trong hệ thống</h3>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                <thead>
                    <tr style="background: #f9fafb; color: #4b5563; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 12px 16px; font-weight: 600;">STT</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Họ và tên</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Tên đăng nhập</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Chuyên khoa</th>
                        <th style="padding: 12px 16px; font-weight: 600;">Email</th>
                    </tr>
                </thead>
                <tbody id="doctorTableBody">
                    <tr>
                        <td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu danh sách bác sĩ...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    async function loadDoctors(keyword = '') {
        const tbody = document.getElementById('doctorTableBody');
        tbody.innerHTML = `<tr><td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Đang tải dữ liệu...</td></tr>`;

        try {
            const response = await fetch(`index.php?api=manager_get_doctors&search=${encodeURIComponent(keyword)}`, {
                method: 'GET',
                credentials: 'include'
            });

            const result = await response.json();

            if (result.status === 'success') {
                const doctors = result.data;
                if (doctors.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="5" style="padding: 24px; text-align: center; color: #6b7280;">Không tìm thấy bác sĩ nào trong hệ thống.</td></tr>`;
                    return;
                }

                let html = '';
                doctors.forEach((d, index) => {
                    html += `
                        <tr style="border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 14px 16px; color: #111827;">${index + 1}</td>
                            <td style="padding: 14px 16px; font-weight: 500; color: #111827;">BS. ${d.hoTen || 'Chưa cập nhật'}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${d.username || ''}</td>
                            <td style="padding: 14px 16px; color: #2563eb; font-weight: 500;">${d.chuyenKhoa || 'Chưa phân khoa'}</td>
                            <td style="padding: 14px 16px; color: #4b5563;">${d.email || ''}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            } else {
                tbody.innerHTML = `<tr><td colspan="5" style="padding: 24px; text-align: center; color: #dc2626;">Lỗi: ${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error(error);
            tbody.innerHTML = `<tr><td colspan="5" style="padding: 24px; text-align: center; color: #dc2626;">Đã xảy ra lỗi kết nối máy chủ!</td></tr>`;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadDoctors();

        const searchInput = document.getElementById('searchDoctorInput');
        const searchBtn = document.getElementById('btnSearchDoctor');

        searchBtn.addEventListener('click', () => {
            loadDoctors(searchInput.value.trim());
        });

        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                loadDoctors(searchInput.value.trim());
            }
        });
    });
</script>