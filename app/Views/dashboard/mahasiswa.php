<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary fw-medium">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard Mahasiswa</li>
        </ol>
    </nav>

    <!-- Success/Error Flash Alert -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success rounded-4 border-0 shadow-sm alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-center">
                <span class="fs-4 me-2"></span>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger rounded-4 border-0 shadow-sm alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-center">
                <span class="fs-4 me-2">️</span>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Header Card -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 20px;">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="badge bg-primary px-3 py-2 rounded-pill fs-7">PKL <?= esc(ucfirst($internship['status'])) ?></span>
                    <span class="text-muted fs-7"><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($internship['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($internship['tanggal_selesai'])) ?></span>
                </div>
                <h1 class="fw-bold mb-1 fs-3 text-dark"><?= esc($internship['perusahaan']) ?></h1>
                <p class="text-muted mb-0">Divisi/Bidang: <strong class="text-dark"><?= esc($internship['bidang']) ?></strong></p>
                <p class="text-muted mb-0">Dosen Pembimbing: <strong class="text-primary"><?= esc($dosenNama) ?></strong></p>
            </div>
            <div class="col-md-4 mt-3 mt-md-0 text-md-end">
                <a href="/logbook" class="btn btn-primary px-4 py-2 shadow-sm rounded-pill fw-semibold"><i class="bi bi-journal-plus me-1"></i> Logbook Harian</a>
                <a href="/documents" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold mt-2 mt-sm-0"><i class="bi bi-cloud-arrow-up me-1"></i> Upload Berkas</a>
            </div>
        </div>
    </div>

    <!-- KPIs Row -->
    <div class="row g-4 mb-4">
        <!-- Progress PKL Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Progress PKL</div>
                        <h3 class="fw-bold text-dark mb-0"><?= $progress ?>%</h3>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-4">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                </div>
                <div class="progress mb-2" style="height: 10px; border-radius: 999px;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="text-muted fs-7">Terisi <strong><?= $jumlahLogbook ?></strong> dari total <strong><?= $totalHari ?></strong> hari PKL.</div>
            </div>
        </div>

        <!-- Logbook Counts Card -->
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Logbook Harian</div>
                        <h3 class="fw-bold text-dark mb-0"><?= $jumlahLogbook ?> Hari</h3>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-4">
                        <i class="bi bi-journal-text fs-4"></i>
                    </div>
                </div>
                <div class="text-muted fs-7 mt-auto">Rata-rata pengisian rutin per minggu terlihat pada grafik aktivitas Anda.</div>
            </div>
        </div>

        <!-- Nilai Akhir Card -->
        <div class="col-md-12 col-lg-4">
            <div class="card border-0 bg-white h-100 shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="text-muted small fw-medium mb-1">Nilai Akhir</div>
                        <h3 class="fw-bold text-dark mb-0"><?= $assessment ? esc($assessment['nilai_akhir']) : 'Belum Dinilai' ?></h3>
                    </div>
                    <div class="p-3 bg-warning-subtle text-warning rounded-4">
                        <i class="bi bi-star-fill fs-4"></i>
                    </div>
                </div>
                <?php if ($assessment): ?>
                    <div class="text-muted fs-7 mt-auto">Nilai diberikan oleh Dosen Pembimbing Anda.</div>
                <?php else: ?>
                    <div class="text-muted fs-7 mt-auto">Menunggu dosen pembimbing menginputkan nilai akhir.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Chart.js Activity Graph -->
        <div class="col-lg-8">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-activity text-primary me-2"></i>Aktivitas Logbook Harian</h5>
                    <span class="text-muted small">Per Minggu</span>
                </div>
                <div style="height: 320px; position: relative;">
                    <canvas id="logbookChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Supervisor Comments Feed -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-chat-text text-primary me-2"></i>Komentar Dosen</h5>
                
                <?php if (empty($comments)): ?>
                    <div class="text-center py-5">
                        <span class="fs-1 text-muted opacity-50"></span>
                        <p class="text-muted mt-2 small">Belum ada komentar dari Dosen Pembimbing.</p>
                    </div>
                <?php else: ?>
                    <div class="comments-timeline" style="max-height: 380px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($comments as $comment): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <!-- Referensi logbook -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-2" style="font-size:.7rem;">
                                        <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($comment['logbook_tanggal'])) ?>
                                    </span>
                                    <span class="text-muted" style="font-size:.65rem;">Logbook</span>
                                </div>
                                <!-- Cuplikan aktivitas logbook -->
                                <div class="bg-light rounded-2 px-2 py-1 mb-2 border-start border-2 border-secondary" style="font-size:.75rem; color:#64748b;">
                                    <i class="bi bi-journal-text me-1"></i>
                                    <span class="text-wrap-limit" style="-webkit-line-clamp:2;">
                                        <?= esc(mb_strimwidth($comment['logbook_aktivitas'], 0, 80, '...')) ?>
                                    </span>
                                </div>
                                <!-- Nama dosen & waktu komentar -->
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small"><i class="bi bi-person me-1"></i><?= esc($comment['dosen_nama']) ?></strong>
                                    <span class="text-muted fs-8"><?= date('d/M H:i', strtotime($comment['created_at'])) ?></span>
                                </div>
                                <!-- Isi komentar -->
                                <p class="text-secondary small mb-0 bg-light p-2 rounded-3 border-start border-primary border-3">
                                    "<?= esc($comment['komentar']) ?>"
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('logbookChart').getContext('2d');
        const labels = <?= $chartLabels ?>;
        const counts = <?= $chartCounts ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Logbook Terisi',
                    data: counts,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)',
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 8,
                    barThickness: 24
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
