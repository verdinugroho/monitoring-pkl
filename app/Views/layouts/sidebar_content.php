<?php
$currentPath = ltrim(service('request')->getUri()->getPath(), '/');
if (strpos($currentPath, 'index.php') === 0) {
    $currentPath = ltrim(substr($currentPath, 9), '/');
}
$segments = explode('/', $currentPath);
$currentModule = $segments[0] ?? 'dashboard';
$currentSub = $segments[1] ?? '';
$role = session()->get('role') ?? 'mahasiswa';

$isActive = function(string $module, string $sub = '') use ($currentModule, $currentSub): string {
    if ($sub !== '') {
        return ($currentModule === $module && $currentSub === $sub) ? 'active' : '';
    }
    return $currentModule === $module ? 'active' : '';
};
?>

<div class="sidebar-brand">
    
    <div>
        <h4>Monitoring PKL</h4>
        <small>Sistem Informasi</small>
    </div>
</div>

<nav class="sidebar-nav">
    <?php if ($role === 'admin'): ?>
        <div class="sidebar-label">Dashboard</div>
        <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= $isActive('admin', 'dashboard') ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="sidebar-label">Manajemen</div>
        <a href="<?= base_url('admin/mahasiswa') ?>" class="sidebar-link <?= $isActive('admin', 'mahasiswa') ?>">
            <i class="bi bi-people-fill"></i> Kelola Mahasiswa
        </a>
        <a href="<?= base_url('admin/dosen') ?>" class="sidebar-link <?= $isActive('admin', 'dosen') ?>">
            <i class="bi bi-person-workspace"></i> Kelola Dosen
        </a>
        <a href="<?= base_url('admin/penilaian') ?>" class="sidebar-link <?= $isActive('admin', 'penilaian') ?>">
            <i class="bi bi-star-fill"></i> Rekap Penilaian
        </a>

    <?php elseif ($role === 'dosen'): ?>
        <div class="sidebar-label">Menu Utama</div>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= $isActive('dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= base_url('bimbingan') ?>" class="sidebar-link <?= $isActive('bimbingan') && $currentSub !== 'penilaian' ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Bimbingan PKL
        </a>
        <a href="<?= base_url('bimbingan/penilaian') ?>" class="sidebar-link <?= $isActive('bimbingan', 'penilaian') ?>">
            <i class="bi bi-star-fill"></i> Penilaian PKL
        </a>

    <?php else: ?>
        <div class="sidebar-label">Menu Utama</div>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-link <?= $isActive('dashboard') ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= base_url('logbook') ?>" class="sidebar-link <?= $isActive('logbook') ?>">
            <i class="bi bi-journal-text"></i> Logbook Harian
        </a>
        <a href="<?= base_url('documents') ?>" class="sidebar-link <?= $isActive('documents') ?>">
            <i class="bi bi-cloud-arrow-up-fill"></i> Dokumen & Laporan
        </a>
    <?php endif; ?>

    <div class="sidebar-label">Akun</div>
    <a href="<?= base_url('logout') ?>" class="sidebar-link">
        <i class="bi bi-box-arrow-right"></i> Keluar
    </a>
</nav>
