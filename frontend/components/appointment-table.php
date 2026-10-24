
<?php
$tableId = $tableId ?? 'appointment-table';
$tableType = $tableType ?? 'appointments';

$columns = $columns ?? [
    'code'       => 'Mã lịch',
    'patient'    => 'Bệnh nhân',
    'doctor'     => 'Bác sĩ',
    'specialty'  => 'Chuyên khoa',
    'date'       => 'Ngày khám',
    'time'       => 'Giờ khám',
    'reason'     => 'Lý do khám',
    'status'     => 'Trạng thái',
    'actions'    => 'Thao tác',
];
?>

<div class="appointment-table-wrapper">
    <table
        class="appointment-table"
        id="<?= htmlspecialchars($tableId) ?>"
        data-table-type="<?= htmlspecialchars($tableType) ?>"
    >
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
                    class="appointment-table__empty"
                >
                    Chưa có dữ liệu lịch khám.
                </td>
            </tr>
        </tbody>
    </table>
</div>
