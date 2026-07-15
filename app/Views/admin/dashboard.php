<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <!-- Page Title Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <h4>Dashboard Admin</h4>
                <p class="text-muted small mb-0">Statistik monitoring dan aktivitas PKL mahasiswa keseluruhan.</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="row g-3 mb-4">
        <!-- Mahasiswa -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">Total Mahasiswa</p>
                        <h3 class="stat-value"><?= $totalMahasiswa ?></h3>
                    </div>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Dosen -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">Total Dosen</p>
                        <h3 class="stat-value"><?= $totalDosen ?></h3>
                    </div>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- PKL Aktif -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">PKL Aktif</p>
                        <h3 class="stat-value"><?= $pklAktif ?></h3>
                    </div>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- PKL Selesai -->
        <div class="col-xl-3 col-md-6 col-12">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="stat-label">PKL Selesai</p>
                        <h3 class="stat-value"><?= $pklSelesai ?></h3>
                    </div>
                    <div class="stat-icon bg-danger-subtle text-danger">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <!-- Status PKL Chart -->
        <div class="col-lg-4 col-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold mb-3"><i class="bi bi-pie-chart-fill me-2 text-primary"></i>Status PKL Mahasiswa</h6>
                    <div style="position: relative; height: 260px;">
                        <canvas id="statusPklChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Activities -->
        <div class="col-lg-8 col-12">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h6 class="card-title fw-bold mb-3"><i class="bi bi-activity me-2 text-primary"></i>Aktivitas Pengisian Logbook (Mingguan)</h6>
                    <div style="position: relative; height: 260px;">
                        <canvas id="weeklyActivityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logbooks Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="card-title fw-bold mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Aktivitas Logbook Terbaru</h6>
            <?php if (empty($recentLogbooks)): ?>
                <div class="empty-state">
                    <i class="bi bi-journal-x"></i>
                    <p class="mb-0">Belum ada pengisian logbook harian oleh mahasiswa.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Perusahaan</th>
                                <th>Tanggal</th>
                                <th>Aktivitas</th>
                                <th>Status Review</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLogbooks as $logbook): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold text-dark"><?= esc($logbook['mahasiswa_nama']) ?></span>
                                    </td>
                                    <td><?= esc($logbook['perusahaan']) ?></td>
                                    <td><?= date('d M Y', strtotime($logbook['tanggal'])) ?></td>
                                    <td class="text-wrap-limit" style="max-width: 300px;"><?= esc($logbook['aktivitas']) ?></td>
                                    <td>
                                        <?php if ($logbook['status_review'] === 'Disetujui'): ?>
                                            <span class="badge bg-success-subtle text-success">Disetujui</span>
                                        <?php elseif ($logbook['status_review'] === 'Direvisi'): ?>
                                            <span class="badge bg-danger-subtle text-danger">Direvisi</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning">Menunggu Review</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart initialization scripts -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Status PKL Doughnut/Pie Chart
    const statusCtx = document.getElementById('statusPklChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: <?= $statusPklLabels ?>,
            datasets: [{
                data: <?= $statusPklData ?>,
                backgroundColor: ['#2563EB', '#22C55E', '#cbd5e1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11 }
                    }
                }
            },
            cutout: '70%'
        }
    });

    // Weekly Activity Bar Chart
    const activityCtx = document.getElementById('weeklyActivityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: <?= $weeklyLabels ?>,
            datasets: [{
                label: 'Jumlah Logbook Diisi',
                data: <?= $weeklyCounts ?>,
                backgroundColor: 'rgba(37, 99, 235, 0.85)',
                borderColor: '#2563EB',
                borderWidth: 0,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
