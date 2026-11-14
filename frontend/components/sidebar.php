
<?php
$tab = $tab ?? 'lichkham';

$role = $role ?? ($_GET['page'] ?? 'patient');

$menus = [
    'patient' => [
        'label' => 'PATIENT PORTAL',
        'items' => [
            'datlich'  => ['icon' => '+', 'label' => 'Đặt lịch khám'],
            'lichkham' => ['icon' => '▦', 'label' => 'Lịch khám của tôi'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
        ],
    ],

    'doctor' => [
        'label' => 'DOCTOR PORTAL',
        'items' => [
            'lichkham' => ['icon' => '▦', 'label' => 'Lịch khám của tôi'],
            'quanly'   => ['icon' => '▤', 'label' => 'Quản lý lịch làm việc'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
        ],
    ],

    'manager' => [
        'label' => 'MANAGEMENT',
        'items' => [
            'lichkham' => ['icon' => '▦', 'label' => 'Quản lý lịch khám'],
            'quanly'   => ['icon' => '▤', 'label' => 'Quản lý lịch làm việc'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
            'benhnhan' => ['icon' => '♙', 'label' => 'Quản lý bệnh nhân'],
            'bacsi'    => ['icon' => '✚', 'label' => 'Quản lý bác sĩ'],
        ],
    ],
];

$menu = $menus[$role] ?? $menus['patient'];

$baseUrl = 'index.php?page=' . urlencode($role);

$isActive = static fn(string $key): bool => $tab === $key;

?>

<aside class="app-sidebar">

    <a
        class="sidebar-brand"
        href="<?= $baseUrl ?>&tab=lichkham">

        <span class="brand-icon">+</span>
        <span>CTU<span class="brand-highlight">Medic</span></span>

    </a>

    <p class="sidebar-caption">
        <?= htmlspecialchars($menu['label']) ?>
    </p>

    <nav class="sidebar-nav">

        <?php foreach ($menu['items'] as $key => $item): ?>

            <a
                href="<?= $baseUrl ?>&tab=<?= urlencode($key) ?>"
                class="sidebar-link <?= $isActive($key) ? 'active' : '' ?>"
                <?= $isActive($key) ? 'aria-current="page"' : '' ?>>

                <span class="sidebar-icon">
                    <?= htmlspecialchars($item['icon']) ?>
                </span>

                <span>
                    <?= htmlspecialchars($item['label']) ?>
                </span>

            </a>

        <?php endforeach; ?>

    </nav>

    <div class="sidebar-bottom">

        <a
            href="<?= $baseUrl ?>&tab=taikhoan"
            class="sidebar-link <?= $isActive('taikhoan') ? 'active' : '' ?>"
            <?= $isActive('taikhoan') ? 'aria-current="page"' : '' ?>>

            <span class="sidebar-icon">⚙</span>
            <span>Thông tin tài khoản</span>

        </a>

        <?php require PATH_COMPONENTS . '/logout.php'; ?>

    </div>

</aside>
