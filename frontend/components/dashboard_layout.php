<?php

$role = $role ?? 'patient';

$roleLabels = [
    'patient' => 'Bệnh nhân',
    'doctor'  => 'Bác sĩ',
    'manager' => 'Quản lý',
];

$roleLabel = $roleLabels[$role] ?? 'Người dùng';

$pageTitle = $pageTitle ?? 'CTUMedic';
$pageDescription = $pageDescription ?? 'Chào mừng đến với hệ thống CTUMedic';

$hoTen = $_SESSION['hoTen'] ?? $roleLabel;

?>

<div class="dashboard-layout">

    <?php require PATH_COMPONENTS . '/sidebar.php'; ?>

    <main class="dashboard-main">

        <header class="dashboard-header">

            <div class="header-main">

                <div class="header-title">
                    <h1><?= htmlspecialchars($pageTitle) ?></h1>

                    <p class="header-description">
                        <?= htmlspecialchars($pageDescription) ?>
                    </p>
                </div>

            </div>

            <div class="header-profile">

                <div class="profile-info">

                    <span class="profile-name">
                        <?= htmlspecialchars($hoTen) ?>
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

        <section class="dashboard-content">

            <?php if (!empty($contentFile) && is_file($contentFile)): ?>

                <?php require $contentFile; ?>

            <?php else: ?>

                <div class="empty-placeholder">
                    <h2>Không tìm thấy nội dung trang</h2>
                    <p>Vui lòng kiểm tra lại đường dẫn đến file tab.</p>
                </div>

            <?php endif; ?>

        </section>

        <footer class="dashboard-footer">
            <span>© <?= date('Y') ?> CTUMedic</span>
            <span>Hệ thống quản lý khám chữa bệnh</span>
        </footer>

    </main>

</div>