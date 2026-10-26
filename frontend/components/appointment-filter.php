<?php

/**
 * Component: Appointment Filter
 *
 * Dùng chung cho Bệnh nhân, Bác sĩ và Quản lý.
 *
 * TODO: JavaScript sẽ gọi API để tải chuyên khoa, bác sĩ
 * và áp dụng bộ lọc. Component này chỉ dựng giao diện.
 *
 * TODO: XÓA DỮ LIỆU DEMO nếu bổ sung dữ liệu mẫu vào file này.
 */
// Phân loại trang : lịch sử khám - lịch khám
$tableType = $tableType ?? 'appointment';

$filterId = $filterId ?? 'appointment-filter';
$showStatus = $showStatus ?? true;
$isDoctor = $_SESSION['role'] === 'doctor';
?>

<section
    class="appointment-filter"
    id="<?= htmlspecialchars($filterId) ?>">
    <div class="appointment-filter__header">
        <div>
            <h3 class="appointment-filter__title">Bộ lọc lịch khám</h3>
            <p class="appointment-filter__description">
                Lọc lịch khám theo các tiêu chí bên dưới.
            </p>
        </div>

        <button
            type="button"
            class="appointment-filter__reset"
            data-filter-reset>
            Xóa bộ lọc
        </button>
    </div>

    <div class="appointment-filter__fields">

        <!-- Chuyên khoa: JavaScript sẽ tải dữ liệu từ API -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-specialty">
                Chuyên khoa
            </label>

            <select
                id="<?= htmlspecialchars($filterId) ?>-specialty"
                name="specialty"
                data-filter="specialty"
                class="specialty-select-box"
                <?= $isDoctor ? 'disabled' : '' ?>>
                <option value="">
                    <?= $isDoctor ? 'Không áp dụng' : 'Tất cả chuyên khoa' ?>
                </option>
            </select>
        </div>

        <!-- Bác sĩ: mặc định bị khóa cho đến khi chọn chuyên khoa -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-doctor">
                Bác sĩ
            </label>

            <select
                id="<?= htmlspecialchars($filterId) ?>-doctor"
                name="doctor"
                data-filter="doctor"
                class="doctor-select-box"
                disabled>

                <option value="">
                    <?= $isDoctor ? 'Không áp dụng' : 'Chọn chuyên khoa trước' ?>
                </option>
            </select>
        </div>

        <!-- Ngày khám -->
        <div class="appointment-filter__field">
            <label for="<?= htmlspecialchars($filterId) ?>-date">
                Ngày khám
            </label>

            <input
                type="date"
                id="<?= htmlspecialchars($filterId) ?>-date"
                name="date"
                data-filter="date"
                class="date-input-box">
        </div>

        <!-- Trạng thái -->
        <?php if ($showStatus): ?>
            <div class="appointment-filter__field">
                <label for="<?= htmlspecialchars($filterId) ?>-status">
                    Trạng thái
                </label>

                <select
                    id="<?= htmlspecialchars($filterId) ?>-status"
                    name="status"
                    data-filter="status"
                    class="status-select-box">
                    <option value="">Tất cả trạng thái</option>
                    <option value="PENDING">Chờ xác nhận</option>
                    <option value="CONFIRMED">Đã xác nhận</option>
                    <option value="REJECTED">Bị từ chối</option>
                    <option value="CANCELLED">Đã hủy</option>
                    <option value="COMPLETED">Đã hoàn thành</option>
                </select>
            </div>
        <?php endif; ?>

    </div>

    <div class="appointment-filter__footer">
        <span class="appointment-filter__hint">
            Chọn tiêu chí để lọc lịch khám.
        </span>

        <button
            type="button"
            class="appointment-filter__submit"
            data-filter-apply>
            <span aria-hidden="true">⌕</span>
            Lọc lịch khám
        </button>
    </div>
</section>


<script type="module">
    (() => {
        // 1. Lấy các phần tử HTML
        const specialtySelectBox = document.querySelector('.specialty-select-box');
        const doctorSelectBox = document.querySelector('.doctor-select-box');
        const dateInputBox = document.querySelector('.date-input-box');
        const statusSelectBox = document.querySelector('.status-select-box');
        const filterBtnSubmit = document.querySelector('.appointment-filter__submit');
        const appointmentTable = document.querySelector('[data-appointment-list]');

        if (!specialtySelectBox) return;
        
        // 2. Danh sách chuyên khoa
        const chuyenKhoa = [
            "Nội khoa",
            "Tim mạch",
            "Da liễu"
        ];

        chuyenKhoa.forEach((nameTag) => {
            const option = document.createElement('option');
            option.value = nameTag;
            option.textContent = nameTag;
            specialtySelectBox.appendChild(option);
        });

        // 3. Khôi phục chuyên khoa từ URL
        const url = new URL(window.location.href);
        const currentSpecialty = url.searchParams.get('specialty') || '';

        specialtySelectBox.value = currentSpecialty;

        // 4. Hàm lấy danh sách bác sĩ từ backend
        const getDoctorList = async (specialty) => {
            if (!doctorSelectBox) return;

            doctorSelectBox.innerHTML =
                '<option value="">Chọn bác sĩ</option>';
            doctorSelectBox.disabled = !specialty;

            if (!specialty) return;

            try {
                const request = await fetch(
                    `<?= API_URL ?>/BacSi/lay_danh_sach.php?specialty=${encodeURIComponent(specialty)}`
                );

                if (!request.ok) {
                    throw new Error('Lỗi khi lấy danh sách bác sĩ');
                }

                const result = await request.json();

                // TODO: Render danh sách bác sĩ theo cấu trúc JSON backend trả về
                result.list.forEach((doctor) => {
                    const option = document.createElement('option');

                    option.value = doctor.id;
                    option.innerText = doctor.hoTen;

                    doctorSelectBox.append(option);
                })

                // Giữ lại doctorId cũ nếu đã có trước đó
                doctorSelectBox.value = new URL(window.location.href).searchParams.get('doctorId') || ''

            } catch (error) {
                console.error('Lỗi khi lấy danh sách bác sĩ:', error);
            }
        };

        // 5. Gọi API khi trang tải, giữ đúng chuyên khoa trên URL
        getDoctorList(currentSpecialty);

        // 6. Xử lý khi người dùng đổi chuyên khoa
        specialtySelectBox.addEventListener('change', function() {
            const selectedSpecialty = this.value;
            const currentUrl = new URL(window.location.href);

            if (selectedSpecialty) {
                currentUrl.searchParams.set('specialty', selectedSpecialty);
            } else {
                currentUrl.searchParams.delete('specialty');
            }

            // Xóa bác sĩ đã chọn vì chuyên khoa đã thay đổi
            currentUrl.searchParams.delete('doctorId');

            // Tránh nhầm với page=manager của hệ thống định tuyến
            currentUrl.searchParams.set('pageNumber', '1');

            window.history.replaceState({}, '', currentUrl);

            // Tải danh sách bác sĩ tương ứng với chuyên khoa mới
            getDoctorList(selectedSpecialty)

        });

        // 7. Xử lí khi người dùng đổi bác sĩ 
        doctorSelectBox.addEventListener('change', function() {
            const selectedDoctor = this.value;
            const currentUrl = new URL(window.location.href);

            if (selectedDoctor) {
                currentUrl.searchParams.set('doctorId', selectedDoctor || 1);
            } else {
                currentUrl.searchParams.delete('doctorId');
            }

            // Tránh nhầm với page=manager của hệ thống định tuyến
            currentUrl.searchParams.set('pageNumber', '1');

            window.history.replaceState({}, '', currentUrl);
        });

        // 8. Xử lí date input
        dateInputBox.value = url.searchParams.get('date') || '';

        dateInputBox.addEventListener('change', function() {
            const inputDate = this.value;
            const currentUrl = new URL(window.location.href);

            if (inputDate) {
                currentUrl.searchParams.set('date', inputDate || '');
            } else {
                currentUrl.searchParams.delete('date');
            }

            currentUrl.searchParams.set('pageNumber', '1');

            window.history.replaceState({}, '', currentUrl);
        });

        // 9. Xử lí status select
        statusSelectBox.value = url.searchParams.get('status') || '';

        statusSelectBox.addEventListener('change', function() {
            const statusSelected = this.value;
            const currentUrl = new URL(window.location.href);

            if (statusSelected) {
                currentUrl.searchParams.set('status', statusSelected);
            } else {
                currentUrl.searchParams.delete('status');
            }

            currentUrl.searchParams.set('pageNumber', '1');

            window.history.replaceState({}, '', currentUrl);
        })

        

    })();
</script>