<?php
$tableId = $tableId ?? 'appointment-table';
$tableType = $tableType ?? 'appointments';
$role = $role ?? 'patient';

$columnsByRole = [
    'patient' => [
        'maLichKham' => 'Mã lịch khám',
        'bacSi'      => 'Bác sĩ',
        'chuyenKhoa' => 'Chuyên khoa',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
    ],

    'doctor' => [
        'maLichKham' => 'Mã lịch khám',
        'benhNhan'   => 'Bệnh nhân',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
    ],

    'manager' => [
        'maLichKham' => 'Mã lịch khám',
        'benhNhan'   => 'Bệnh nhân',
        'bacSi'      => 'Bác sĩ',
        'chuyenKhoa' => 'Chuyên khoa',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
    ],
];

$columns = $columnsByRole[$role] ?? [];
?>

<div class="appointment-table-wrapper">
    <table
        class="appointment-table"
        id="<?= htmlspecialchars($tableId) ?>"
        data-table-type="<?= htmlspecialchars($tableType) ?>">
        <thead>
            <tr>
                <?php foreach ($columns as $key => $label): ?>
                    <th scope="col" data-column="<?= htmlspecialchars($key) ?>">
                        <?= htmlspecialchars($label) ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>

        <tbody data-appointment-list>
            <tr>
                <td
                    colspan="<?= count($columns) ?>"
                    class="appointment-table__empty">
                    Chưa có dữ liệu lịch khám.
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    (() => {
        const appointmentTable = document.querySelector('[data-appointment-list]');

        const getAppointmentList = async () => {
            const currentUrl = new URL(window.location.href);

            const urlSearchQuery = currentUrl.searchParams;

            const pageNumber = urlSearchQuery.get('pageNumber') || 1;
            const specialty = urlSearchQuery.get('specialty') || '';
            const doctorId = urlSearchQuery.get('doctorId') || '';
            const date = urlSearchQuery.get('date') || '';
            const status = urlSearchQuery.get('status') || '';
            const tableType = '<?= $tableType ?>' || 'appointments';

            const params = new URLSearchParams({
                pageNumber,
                specialty,
                doctorId,
                date,
                status,
                tableType
            });

            try {
                const response = await fetch(
                    `<?= API_URL ?>/LichKham/lay_lich_kham.php?${params.toString()}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                const result = await response.json();
                console.log(result)
                if (!response.ok) {
                    throw new Error(result.message || 'Không thể lấy danh sách lịch khám.');
                }

                console.log(result);

                if (result.pagination.totalRecords) {
                    renderAppointmentList(result.list)
                }

            } catch (error) {
                console.error('Lỗi lấy danh sách lịch khám:', error);
                return null;
            }

        }

        getAppointmentList();

        const columns = {
            maLichKham: item => `LK${String(item.id).padStart(4, '0')}`,
            benhNhan: item => item.hoTenBenhNhan,
            bacSi: item => item.hoTenBacSi,
            chuyenKhoa: item => item.chuyenKhoa,
            ngayKham: item => item.ngayKham,
            gioKham: item => item.gioKham,
            trangThai: item => item.trangThai
        };

        const renderAppointmentList = (list) => {
            appointmentTable.replaceChildren();
            console.log(list)
            if (!Array.isArray(list) || list.length === 0) {
                const row = document.createElement('tr');
                const cell = document.createElement('td');

                cell.colSpan = appointmentTable.closest('table')
                    .querySelectorAll('thead th').length;
                cell.className = 'appointment-table__empty';
                cell.textContent = 'Chưa có dữ liệu lịch khám.';

                row.appendChild(cell);
                appointmentTable.appendChild(row);
                return;
            }

            list.forEach(item => {
                const row = document.createElement('tr');

                Object.keys(columns).forEach(key => {
                    const cell = document.createElement('td');
                    cell.textContent = columns[key](item) ?? '—';
                    row.appendChild(cell);
                });

                row.dataset.appointmentId = item.id;
                appointmentTable.appendChild(row);
            });
            console.log(1)
        };

    })();
</script>