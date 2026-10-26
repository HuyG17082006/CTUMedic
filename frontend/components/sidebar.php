
<?php
$role = $_GET['page']; 
$tab = $_GET['tab'] ?? 'tongquan';

$menus = [
    'patient' => [
        'label' => 'PATIENT PORTAL',
        'items' => [
            'datlich'  => ['icon' => '+', 'label' => 'Đặt lịch khám'],
            'lichkham' => ['icon' => '▦', 'label' => 'Lịch khám'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
        ],
    ],

    'doctor' => [
        'label' => 'DOCTOR PORTAL',
        'items' => [
            'lichkham' => ['icon' => '▦', 'label' => 'Lịch khám'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
        ],
    ],

    'manager' => [
        'label' => 'MANAGEMENT',
        'items' => [
            'lichkham' => ['icon' => '▦', 'label' => 'Quản lý lịch khám'],
            'lichsu'   => ['icon' => '◷', 'label' => 'Lịch sử khám'],
            'benhnhan' => ['icon' => '♙', 'label' => 'Quản lý bệnh nhân'],
            'bacsi'    => ['icon' => '✚', 'label' => 'Quản lý bác sĩ'],
        ],
    ],
];

$menu = $menus[$role] ?? $menus['patient'];
?>

<aside class="app-sidebar">

    <!-- Brand -->
    <a class="sidebar-brand"
       href="index.php?page=<?= urlencode($role) ?>&tab=tongquan">
        <span class="brand-icon">+</span>
        <span>CTU<span class="brand-highlight">Medic</span></span>
    </a>

    <!-- Menu label -->
    <p class="sidebar-caption">
        <?= htmlspecialchars($menu['label']) ?>
    </p>

    <!-- Navigation -->
    <nav class="sidebar-nav">
        <?php foreach ($menu['items'] as $key => $item): ?>
            <a
                href="index.php?page=<?= urlencode($role) ?>&tab=<?= urlencode($key) ?>"
                class="sidebar-link <?= $tab === $key ? 'active' : '' ?>"
                <?= $tab === $key ? 'aria-current="page"' : '' ?>
            >
                <span class="sidebar-icon">
                    <?= htmlspecialchars($item['icon']) ?>
                </span>

                <span><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Bottom actions -->
    <div class="sidebar-bottom">

        <!-- Account settings -->
        <a
            href="index.php?page=<?= urlencode($role) ?>&tab=taikhoan"
            class="sidebar-link <?= $tab === 'taikhoan' ? 'active' : '' ?>"
            <?= $tab === 'taikhoan' ? 'aria-current="page"' : '' ?>
        >
            <span class="sidebar-icon">⚙</span>
            <span>Thông tin tài khoản</span>
        </a>

        <!-- Logout -->
        <a href="index.php?page=logout" class="sidebar-logout">
            <span class="sidebar-icon">↪</span>
            <span>Đăng xuất</span>
        </a>

    </div>

</aside>
