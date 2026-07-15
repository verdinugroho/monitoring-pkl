<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">
            <div>
                <h4>Rekap Penilaian PKL Mahasiswa</h4>
                <p class="text-muted small mb-0">Halaman rekapitulasi seluruh nilai akhir PKL mahasiswa yang diinput oleh dosen pembimbing.</p>
            </div>
        </div>
        <button onclick="window.print()" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-printer-fill"></i> Cetak Rekap
        </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/penilaian" class="row g-3">
                <div class="col-lg-9 col-md-8 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama mahasiswa, NIM, dosen pembimbing, atau perusahaan..." value="<?= esc($search) ?>">
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-12 d-grid">
                    <button type="submit" class="btn btn-primary">Cari Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($penilaianList)): ?>
                <div class="empty-state py-5 text-center">
                    <i class="bi bi-star-half" style="font-size: 3rem; opacity: 0.3;"></i>
                    <p class="mb-0 mt-2 text-muted">Belum ada data penilaian PKL.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" style="min-width: 200px;">Mahasiswa</th>
                                <th style="min-width: 150px;">Pembimbing</th>
                                <th style="min-width: 150px;">Perusahaan</th>
                                <th class="text-center" style="width: 70px;">Disp</th>
                                <th class="text-center" style="width: 70px;">Hadir</th>
                                <th class="text-center" style="width: 70px;">Kiner</th>
                                <th class="text-center" style="width: 70px;">Logb</th>
                                <th class="text-center" style="width: 70px;">Lapor</th>
                                <th class="text-center" style="min-width: 100px;">Nilai Akhir</th>
                                <th class="text-center" style="min-width: 100px;">Status PKL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($penilaianList as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark"><?= esc($item['mahasiswa_nama']) ?></div>
                                        <div class="text-muted small">NIM: <?= esc($item['mahasiswa_nim'] ?? '-') ?> | <?= esc($item['mahasiswa_prodi'] ?? '-') ?></div>
                                    </td>
                                    <td>
                                        <span class="text-dark small"><i class="bi bi-person-fill text-secondary me-1"></i><?= esc($item['dosen_nama']) ?></span>
                                    </td>
                                    <td>
                                        <div class="small fw-medium text-dark"><?= esc($item['perusahaan']) ?></div>
                                        <div class="text-muted small"><?= esc($item['bidang']) ?></div>
                                    </td>
                                    <td class="text-center small"><?= $item['nilai_akhir'] !== null ? esc($item['disiplin']) : '-' ?></td>
                                    <td class="text-center small"><?= $item['nilai_akhir'] !== null ? esc($item['kehadiran']) : '-' ?></td>
                                    <td class="text-center small"><?= $item['nilai_akhir'] !== null ? esc($item['kinerja']) : '-' ?></td>
                                    <td class="text-center small"><?= $item['nilai_akhir'] !== null ? esc($item['logbook']) : '-' ?></td>
                                    <td class="text-center small"><?= $item['nilai_akhir'] !== null ? esc($item['laporan']) : '-' ?></td>
                                    <td class="text-center">
                                        <?php if ($item['nilai_akhir'] !== null): ?>
                                            <span class="badge bg-primary px-3 py-2 fs-7 fw-bold"><?= number_format($item['nilai_akhir'], 2) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted small"><em>Belum Dinilai</em></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($item['pkl_status'] === 'selesai'): ?>
                                            <span class="badge bg-success-subtle text-success px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1"><i class="bi bi-clock-fill me-1"></i>Aktif</span>
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

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .app-sidebar, .app-topbar, .page-header button, .card-body form {
            display: none !important;
        }
        .app-content {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .container-fluid, .container-fluid * {
            visibility: visible;
        }
        .container-fluid {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
    }
</style>
<?= $this->endSection() ?>
