
<?php

$role = $role ?? 'patient';
$tab = $tab ?? 'tongquan';

$roleLabels = [
    'patient' => 'Bệnh nhân',
    'doctor'  => 'Bác sĩ',
    'manager' => 'Quản lý',
];

$tabLabels = [
    'tongquan' => 'Tổng quan',
    'datlich'  => 'Đặt lịch khám',
    'lichkham' => 'Lịch khám',
    'lichsu'   => 'Lịch sử khám',
    'benhnhan' => 'Quản lý bệnh nhân',
    'bacsi'    => 'Quản lý bác sĩ',
    'taikhoan' => 'Thông tin tài khoản',
];

$roleLabel = $roleLabels[$role] ?? 'Người dùng';
$tabLabel = $tabLabels[$tab] ?? 'Tổng quan';

$pageTitle = $pageTitle ?? $tabLabel;
$pageDescription = $pageDescription ?? 'Chào mừng đến với hệ thống CTUMedic';

$userName = $_SESSION['user_name'] ?? $roleLabel;
?>

<div class="dashboard-layout">

    <?php require PATH_COMPONENTS . '/sidebar.php'; ?>

    <main class="dashboard-main">

        <!-- Header hiển thị thông tin trang -->
        <header class="dashboard-header">

            <div class="header-main">

                <div class="header-title">
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>
                    <p class="header-description">
                        <?= htmlspecialchars($pageDescription) ?>
                    </p>
                </div>

            </div>

            <!-- Thông tin tài khoản -->
            <div class="header-profile">

                <div class="profile-info">
                    <span class="profile-name">
                        <?= htmlspecialchars($userName) ?>
                    </span>

                    <span class="profile-role">
                        <?= htmlspecialchars($roleLabel) ?>
                    </span>
                </div>

                <a
                    class="profile-settings"
                    href="index.php?page=<?= urlencode($role) ?>&tab=taikhoan"
                    aria-label="Thông tin tài khoản"
                    title="Thông tin tài khoản"
                >
                    <span aria-hidden="true">⚙</span>
                </a>

            </div>

        </header>

        <!-- Nội dung của tab hiện tại -->
        <section class="dashboard-content">
            <?php
            if (isset($contentFile) && is_file($contentFile)) {
                require $contentFile;
            } else {
                echo '<div class="empty-placeholder">';
                echo '<h2>Không tìm thấy nội dung trang</h2>';
                echo '<p>Vui lòng kiểm tra lại đường dẫn đến file tab.</p>';
                echo '</div>';
            }
            ?>
        </section>

        <footer class="dashboard-footer">
            <span>© <?= date('Y') ?> CTUMedic</span>
            <span>Hệ thống quản lý khám chữa bệnh</span>
        </footer>

    </main>
    
</div>
