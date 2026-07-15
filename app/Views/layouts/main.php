<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Monitoring PKL' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --c-primary: #2563EB;
            --c-primary-light: #3b82f6;
            --c-success: #22C55E;
            --c-warning: #F59E0B;
            --c-danger: #EF4444;
            --c-bg: #F8FAFC;
            --c-sidebar: #0f172a;
            --c-sidebar-hover: rgba(255,255,255,.08);
            --c-text: #1e293b;
            --c-text-muted: #64748b;
            --c-border: #e2e8f0;
            --radius: 12px;
            --radius-lg: 16px;
            --shadow-sm: 0 1px 3px rgba(15,23,42,.06);
            --shadow: 0 4px 16px rgba(15,23,42,.08);
            --shadow-lg: 0 8px 32px rgba(15,23,42,.12);
            --sidebar-w: 260px;
            --transition: all .25s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--c-bg);
            color: var(--c-text);
            margin: 0;
            overflow-x: hidden;
        }

        /* ─── SIDEBAR (Desktop) ──────────────────────────── */
        .app-sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0; left: 0;
            background: linear-gradient(180deg, var(--c-sidebar) 0%, #1e293b 100%);
            color: #e2e8f0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: var(--transition);
        }

        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .sidebar-brand-icon {
            width: 40px; height: 40px;
            border-radius: var(--radius);
            background: linear-gradient(135deg, var(--c-primary), #7c3aed);
            display: grid;
            place-items: center;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand h4 {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
            color: #fff;
            line-height: 1.3;
        }

        .sidebar-brand small {
            font-size: .7rem;
            color: #94a3b8;
            font-weight: 400;
        }

        .sidebar-nav { padding: 12px 0; flex: 1; }

        .sidebar-label {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: #64748b;
            padding: 16px 20px 6px;
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            margin: 2px 10px;
            border-radius: 10px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .sidebar-link:hover {
            background: var(--c-sidebar-hover);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: rgba(37,99,235,.2);
            color: #fff;
            border-left: 3px solid var(--c-primary);
        }

        .sidebar-link i { font-size: 1.1rem; width: 20px; text-align: center; }

        /* ─── MAIN CONTENT ───────────────────────────────── */
        .app-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        /* ─── TOPBAR ─────────────────────────────────────── */
        .app-topbar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--c-border);
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-left { display: flex; align-items: center; gap: 12px; }

        .topbar-hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--c-text);
            cursor: pointer;
            padding: 4px;
        }

        .topbar-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .82rem;
            color: var(--c-text-muted);
        }

        .topbar-breadcrumb a { color: var(--c-primary); text-decoration: none; }
        .topbar-breadcrumb span { color: var(--c-text-muted); }

        .topbar-right { display: flex; align-items: center; gap: 10px; }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--c-bg);
            border: 1px solid var(--c-border);
            cursor: pointer;
            text-decoration: none;
            color: var(--c-text);
            transition: var(--transition);
        }

        .topbar-user:hover { border-color: var(--c-primary); box-shadow: var(--shadow-sm); }

        .topbar-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--c-primary), #7c3aed);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: .8rem;
            font-weight: 600;
        }

        .topbar-user-info { line-height: 1.2; }
        .topbar-user-name { font-size: .82rem; font-weight: 600; }
        .topbar-user-role { font-size: .7rem; color: var(--c-text-muted); }

        /* ─── PAGE CONTENT ───────────────────────────────── */
        .app-page { flex: 1; padding: 24px; }

        /* ─── CARDS ──────────────────────────────────────── */
        .card {
            border-radius: var(--radius);
            border: 1px solid var(--c-border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow);
            transform: translateY(-2px);
        }

        /* ─── BUTTONS ────────────────────────────────────── */
        .btn { border-radius: 8px; font-weight: 500; font-size: .85rem; transition: var(--transition); }
        .btn:hover { transform: translateY(-1px); box-shadow: var(--shadow-sm); }

        .btn-primary { background: var(--c-primary); border-color: var(--c-primary); }
        .btn-primary:hover { background: var(--c-primary-light); border-color: var(--c-primary-light); }
        .btn-success { background: var(--c-success); border-color: var(--c-success); }
        .btn-warning { background: var(--c-warning); border-color: var(--c-warning); color: #fff; }
        .btn-warning:hover { color: #fff; }
        .btn-danger { background: var(--c-danger); border-color: var(--c-danger); }

        /* ─── BADGES ─────────────────────────────────────── */
        .badge { font-weight: 500; font-size: .75rem; padding: .4em .7em; border-radius: 6px; }

        /* ─── TABLES ─────────────────────────────────────── */
        .table { font-size: .85rem; }
        .table th { font-weight: 600; color: var(--c-text-muted); font-size: .78rem; text-transform: uppercase; letter-spacing: .03em; white-space: nowrap; }

        /* ─── FORMS ──────────────────────────────────────── */
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid var(--c-border);
            font-size: .85rem;
            padding: .55rem .85rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--c-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }

        .form-label { font-weight: 500; font-size: .82rem; color: var(--c-text); margin-bottom: .3rem; }

        /* ─── PAGE TITLE ─────────────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .page-title-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius);
            background: linear-gradient(135deg, var(--c-primary), #7c3aed);
            display: grid;
            place-items: center;
            color: #fff;
            font-size: .95rem;
        }

        .page-title h4 { font-size: 1.15rem; font-weight: 700; margin: 0; }

        /* ─── STAT CARD ──────────────────────────────────── */
        .stat-card {
            border-radius: var(--radius);
            padding: 20px;
            background: #fff;
            border: 1px solid var(--c-border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .stat-card:hover { box-shadow: var(--shadow); transform: translateY(-3px); }

        .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius);
            display: grid;
            place-items: center;
            font-size: 1.2rem;
        }

        .stat-value { font-size: 1.6rem; font-weight: 700; margin: 0; }
        .stat-label { font-size: .78rem; color: var(--c-text-muted); margin: 0; }

        /* ─── TOAST ──────────────────────────────────────── */
        .toast-container { position: fixed; top: 80px; right: 20px; z-index: 1080; }

        /* ─── ANIMATIONS ─────────────────────────────────── */
        .fade-in { animation: fadeIn .4s ease both; }
        .fade-in-up { animation: fadeInUp .5s ease both; }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── EMPTY STATE ────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
            color: var(--c-text-muted);
        }
        .empty-state i { font-size: 3rem; opacity: .3; margin-bottom: 12px; }
        .empty-state p { font-size: .9rem; }

        /* ─── SPINNER ────────────────────────────────────── */
        .loading-spinner {
            display: inline-block;
            width: 20px; height: 20px;
            border: 2px solid var(--c-border);
            border-top-color: var(--c-primary);
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ─── MOBILE RESPONSIVE ──────────────────────────── */
        @media (max-width: 991.98px) {
            .app-sidebar { display: none; }
            .app-content { margin-left: 0; }
            .topbar-hamburger { display: block; }
            .app-page { padding: 16px; }

            .page-header { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 767.98px) {
            .app-topbar { padding: 0 16px; height: 56px; }
            .app-page { padding: 12px; }
            .topbar-user-info { display: none; }
            .stat-card { padding: 16px; }
            .stat-value { font-size: 1.3rem; }
        }

        @media (max-width: 575.98px) {
            .app-page { padding: 10px; }
            .page-title h4 { font-size: 1rem; }
        }

        /* Utility */
        .text-wrap-limit {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .fs-8 { font-size: 0.75rem; }
    </style>
</head>
<body>

<?php
$currentPath = ltrim(service('request')->getUri()->getPath(), '/');
// Remove index.php from path if exists (CI4 development environment)
if (strpos($currentPath, 'index.php') === 0) {
    $currentPath = ltrim(substr($currentPath, 9), '/');
}
$currentModule = $currentPath === '' ? 'dashboard' : explode('/', $currentPath)[0];
$currentSub = isset(explode('/', $currentPath)[1]) ? explode('/', $currentPath)[1] : '';
$role = session()->get('role') ?? 'mahasiswa';
$roleLabel = match ($role) {
    'mahasiswa' => 'Mahasiswa',
    'dosen' => 'Dosen Pembimbing',
    'admin' => 'Administrator',
    default => 'User',
};

$userName = session()->get('nama') ?? 'User';
$initials = '';
$words = explode(' ', $userName);
foreach (array_slice($words, 0, 2) as $w) {
    $initials .= strtoupper(mb_substr($w, 0, 1));
}
?>

<!-- ─── SIDEBAR (Desktop visible, Mobile via Offcanvas) ─── -->
<aside class="app-sidebar d-none d-lg-flex" id="sidebarDesktop">
    <?= $this->include('layouts/sidebar_content') ?>
</aside>

<!-- ─── OFFCANVAS SIDEBAR (Mobile) ─── -->
<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="sidebarMobile" style="width:var(--sidebar-w);background:linear-gradient(180deg,var(--c-sidebar) 0%,#1e293b 100%);">
    <div class="offcanvas-body p-0">
        <?= $this->include('layouts/sidebar_content') ?>
    </div>
</div>

<!-- ─── MAIN CONTENT ─── -->
<div class="app-content">
    <!-- Topbar -->
    <header class="app-topbar">
        <div class="topbar-left">
            <button class="topbar-hamburger d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
            <div class="topbar-breadcrumb d-none d-md-flex">
                <!-- Breadcrumb removed -->
            </div>
        </div>
        <div class="topbar-right">
            <span class="badge bg-success-subtle text-success d-none d-sm-inline-block">
                <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i> Online
            </span>
            <div class="dropdown">
                <div class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="topbar-avatar"><?= esc($initials) ?></div>
                    <div class="topbar-user-info d-none d-sm-block">
                        <div class="topbar-user-name"><?= esc($userName) ?></div>
                        <div class="topbar-user-role"><?= esc($roleLabel) ?></div>
                    </div>
                    <i class="bi bi-chevron-down" style="font-size:.7rem;color:var(--c-text-muted)"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end" style="border-radius:var(--radius);border:1px solid var(--c-border);box-shadow:var(--shadow);">
                    <li>
                        <div class="dropdown-item-text">
                            <strong class="d-block" style="font-size:.85rem"><?= esc($userName) ?></strong>
                            <small class="text-muted"><?= esc(session()->get('email') ?? '') ?></small>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="app-page fade-in-up">
        <!-- Toast notifications -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="toast-container">
            <div class="toast show align-items-center text-bg-success border-0" role="alert" style="border-radius:var(--radius);">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
        <div class="toast-container">
            <div class="toast show align-items-center text-bg-danger border-0" role="alert" style="border-radius:var(--radius);">
                <div class="d-flex">
                    <div class="toast-body"><i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?></div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide toasts
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toast.show').forEach(function(t) {
        setTimeout(function() {
            var toast = bootstrap.Toast.getOrCreateInstance(t);
            toast.hide();
        }, 4000);
    });
});
</script>
</body>
</html>