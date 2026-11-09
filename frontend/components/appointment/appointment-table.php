
<?php

$tableType = $tableType ?? 'appointments';
$role = $role ?? 'patient';

$columnsByRole = [
    'patient' => [
        'appointments' => [
            'maLichKham' => 'Mã lịch khám',
            'bacSi'      => 'Bác sĩ',
            'chuyenKhoa' => 'Chuyên khoa',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],

        'history' => [
            'maLichKham' => 'Mã lịch khám',
            'bacSi'      => 'Bác sĩ',
            'chuyenKhoa' => 'Chuyên khoa',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],
    ],

    'doctor' => [
        'appointments' => [
            'maLichKham' => 'Mã lịch khám',
            'benhNhan'   => 'Bệnh nhân',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],

        'history' => [
            'maLichKham' => 'Mã lịch khám',
            'benhNhan'   => 'Bệnh nhân',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],
    ],

    'manager' => [
        'appointments' => [
            'maLichKham' => 'Mã lịch khám',
            'benhNhan'   => 'Bệnh nhân',
            'bacSi'      => 'Bác sĩ',
            'chuyenKhoa' => 'Chuyên khoa',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],

        'history' => [
            'maLichKham' => 'Mã lịch khám',
            'benhNhan'   => 'Bệnh nhân',
            'bacSi'      => 'Bác sĩ',
            'chuyenKhoa' => 'Chuyên khoa',
            'ngayKham'   => 'Ngày khám',
            'gioKham'    => 'Giờ khám',
            'trangThai'  => 'Trạng thái',
            'thaoTac'    => 'Thao tác',
        ],
    ],
];

$columns = $columnsByRole[$role][$tableType] ?? [];
?>

<div class="appointment-table-wrapper">
    <table
        class="appointment-table"
        data-table-type="<?= htmlspecialchars($tableType) ?>"
        data-role="<?= htmlspecialchars($role) ?>">

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
                    colspan="<?= max(count($columns), 1) ?>"
                    class="appointment-table__empty">
                    Chưa có dữ liệu lịch khám.
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="module" src="/dashboard/CTUMedic/frontend/assets/js/shared/lich/lich.js"></script>
