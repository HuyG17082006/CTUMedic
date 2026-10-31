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
    const tableType = "<?= $tableType; ?>";
</script>
<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/shared/lich.js"></script>