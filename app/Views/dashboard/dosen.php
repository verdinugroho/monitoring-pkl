<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary fw-medium">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Dosen</li>
        </ol>
    </nav>

    <!-- Error/Success Flash Alert -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger rounded-4 border-0 shadow-sm alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-center">
                <span class="fs-4 me-2">️</span>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Header Card -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 20px;">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-1 fs-3 text-dark">Dashboard Pembimbing</h1>
                <p class="text-muted mb-0">Kelola dan pantau aktivitas PKL mahasiswa bimbingan Anda dengan mudah dan terstruktur.</p>
            </div>
            <div class="col-md-4 mt-3 mt-md-0 text-md-end">
                <a href="/bimbingan" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-semibold"><i class="bi bi-people me-1"></i> Mahasiswa Bimbingan</a>
            </div>
        </div>
    </div>

    <!-- KPIs Row -->
    <div class="row g-4 mb-4">
        <!-- Total Bimbingan Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Total Bimbingan</div>
                        <h3 class="fw-bold text-dark mb-0"><?= $totalBimbingan ?> Mahasiswa</h3>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-4">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                </div>
                <div class="text-muted fs-7 mt-auto">
                    <span class="text-success fw-medium"><?= $mahasiswaAktif ?></span> Aktif &middot; 
                    <span class="text-secondary fw-medium"><?= $mahasiswaSelesai ?></span> Selesai
                </div>
            </div>
        </div>

        <!-- Pending Review Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Belum Direview</div>
                        <h3 class="fw-bold text-warning mb-0"><?= $logbooksPending ?> Logbook</h3>
                    </div>
                    <div class="p-3 bg-warning-subtle text-warning rounded-4">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                </div>
                <div class="text-muted fs-7 mt-auto">Butuh persetujuan/catatan revisi dari Anda.</div>
            </div>
        </div>

        <!-- Logbooks Filled This Week Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Logbook Minggu Ini</div>
                        <h3 class="fw-bold text-success mb-0"><?= $logbooksThisWeek ?> Terisi</h3>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-4">
                        <i class="bi bi-journal-check fs-4"></i>
                    </div>
                </div>
                <div class="text-muted fs-7 mt-auto">Jumlah laporan yang diisi minggu ini.</div>
            </div>
        </div>

        <!-- Active Ratio Card -->
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Rasio Mahasiswa Aktif</div>
                        <h3 class="fw-bold text-dark mb-0"><?= $totalBimbingan > 0 ? round(($mahasiswaAktif / $totalBimbingan) * 100) : 0 ?>%</h3>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-4">
                        <i class="bi bi-percent fs-4"></i>
                    </div>
                </div>
                <div class="progress mb-2" style="height: 6px; border-radius: 999px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: <?= $totalBimbingan > 0 ? ($mahasiswaAktif / $totalBimbingan) * 100 : 0 ?>%"></div>
                </div>
                <div class="text-muted fs-8 mt-auto">Rasio mahasiswa yang sedang menjalani PKL.</div>
            </div>
        </div>
    </div>

    <!-- Chart Activity -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-activity text-primary me-2"></i>Aktivitas Logbook Mahasiswa (7 Hari Terakhir)</h5>
                    <span class="text-muted small">Grafik Keaktifan Bimbingan</span>
                </div>
                <div style="height: 350px; position: relative;">
                    <canvas id="dosenActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('dosenActivityChart').getContext('2d');
        const labels = <?= $chartLabels ?>;
        const counts = <?= $chartCounts ?>;
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Logbook Disubmit',
                    data: counts,
                    fill: true,
                    backgroundColor: 'rgba(37, 99, 235, 0.08)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 2,
                    tension: 0.35,
                    pointBackgroundColor: 'rgb(37, 99, 235)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
