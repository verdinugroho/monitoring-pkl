<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary fw-medium">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Penilaian PKL</li>
        </ol>
    </nav>

    <!-- Header Card -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h4 class="fw-bold mb-1 text-dark">Penilaian Praktik Kerja Lapangan</h4>
                <p class="text-muted small mb-0">Input nilai akhir dan rekap penilaian untuk setiap mahasiswa bimbingan PKL Anda.</p>
            </div>
            <div class="col-md-6">
                <form method="get" action="/bimbingan/penilaian" class="d-flex gap-2 justify-content-md-end">
                    <div class="input-group" style="max-width: 320px;">
                        <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari nama / perusahaan..." value="<?= esc($search) ?>">
                        <button class="btn btn-outline-secondary rounded-end-pill bg-white border-start-0 text-muted" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <?php if (!empty($search)): ?>
                        <a href="/bimbingan/penilaian" class="btn btn-light rounded-pill"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Student Assessment Table/Cards -->
    <div class="card border-0 bg-white shadow-sm" style="border-radius: 18px; overflow: hidden;">
        <div class="card-body p-0">
            <?php if (empty($bimbinganPenilaian)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-star-fill opacity-25" style="font-size: 3rem;"></i>
                    <p class="small mt-2">Tidak ada data mahasiswa bimbingan yang cocok.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Mahasiswa</th>
                                <th>Perusahaan</th>
                                <th class="text-center">Disiplin</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Kinerja</th>
                                <th class="text-center">Logbook</th>
                                <th class="text-center">Laporan</th>
                                <th class="text-center">Nilai Akhir</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bimbinganPenilaian as $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?= esc($item['mahasiswa_nama']) ?></div>
                                        <div class="text-muted small">NIM: <?= esc($item['mahasiswa_nim'] ?? '-') ?></div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark"><?= esc($item['perusahaan']) ?></div>
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
                                            <span class="badge bg-warning-subtle text-warning px-2 py-1 fs-8">Belum Dinilai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="/bimbingan/detail/<?= $item['internship_id'] ?>?tab=assess" class="btn btn-sm <?= $item['nilai_akhir'] !== null ? 'btn-outline-primary' : 'btn-primary' ?> rounded-pill px-3 fw-semibold">
                                            <i class="bi <?= $item['nilai_akhir'] !== null ? 'bi-pencil-square' : 'bi-star-fill' ?> me-1"></i>
                                            <?= $item['nilai_akhir'] !== null ? 'Edit Nilai' : 'Beri Nilai' ?>
                                        </a>
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
<?= $this->endSection() ?>
