<?php

$tableType = $tableType ?? 'appointments';
$role = $role ?? 'patient';

$columnConfigs = [
    'patient' => [
        'maLichKham' => 'Mã lịch khám',
        'bacSi'      => 'Bác sĩ',
        'chuyenKhoa' => 'Chuyên khoa',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
        'thaoTac'    => 'Thao tác',
    ],

    'doctor' => [
        'maLichKham' => 'Mã lịch khám',
        'benhNhan'   => 'Bệnh nhân',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
        'thaoTac'    => 'Thao tác',
    ],

    'manager' => [
        'maLichKham' => 'Mã lịch khám',
        'benhNhan'   => 'Bệnh nhân',
        'bacSi'      => 'Bác sĩ',
        'chuyenKhoa' => 'Chuyên khoa',
        'ngayKham'   => 'Ngày khám',
        'gioKham'    => 'Giờ khám',
        'trangThai'  => 'Trạng thái',
        'thaoTac'    => 'Thao tác',
    ],
];

$columns = $columnConfigs[$role]
    ?? $columnConfigs['patient'];

?>

<div class="appointment-table-wrapper">
    <table
        class="appointment-table"
        data-table-type="<?= htmlspecialchars($tableType) ?>"
        data-role="<?= htmlspecialchars($role) ?>">

        <thead>
            <tr>
                <?php foreach ($columns as $key => $label): ?>
                    <th
                        scope="col"
                        data-column="<?= htmlspecialchars($key) ?>">
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

<script
    type="module"
    src="/dashboard/CTUMedic/frontend/assets/js/shared/lich/lich.js">
</script>