<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary fw-medium">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mahasiswa Bimbingan</li>
        </ol>
    </nav>

    <!-- Header Card -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h4 class="fw-bold mb-1 text-dark">Daftar Mahasiswa Bimbingan PKL</h4>
                <p class="text-muted small mb-0">Lihat progress, review logbook harian, dan berikan penilaian akhir bagi mahasiswa bimbingan Anda.</p>
            </div>
            <div class="col-md-6">
                <form method="get" action="/bimbingan" class="d-flex gap-2 justify-content-md-end">
                    <div class="input-group" style="max-width: 320px;">
                        <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari nama / perusahaan..." value="<?= esc($search) ?>">
                        <button class="btn btn-outline-secondary rounded-end-pill bg-white border-start-0 text-muted" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <?php if (!empty($search)): ?>
                        <a href="/bimbingan" class="btn btn-light rounded-pill"><i class="bi bi-x-lg"></i></a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- Student Cards Grid -->
    <?php if (empty($bimbingan)): ?>
        <div class="card border-0 bg-white shadow-sm p-5 text-center" style="border-radius: 18px;">
            <span class="fs-1 opacity-50"></span>
            <h5 class="fw-bold text-secondary mt-3">Tidak Ada Mahasiswa Bimbingan</h5>
            <p class="text-muted small mb-0">Belum ada mahasiswa yang terdaftar di bawah bimbingan Anda.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($bimbingan as $student): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 bg-white shadow-sm h-100 p-4 d-flex flex-column" style="border-radius: 18px;">
                        <!-- Header Card -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><?= esc($student['mahasiswa_nama']) ?></h5>
                                <span class="text-muted fs-8"><i class="bi bi-envelope me-1"></i><?= esc($student['mahasiswa_email']) ?></span>
                            </div>
                            <?php if ($student['status'] === 'aktif'): ?>
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1 fs-8">Aktif</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1 fs-8">Selesai</span>
                            <?php endif; ?>
                        </div>

                        <!-- Internship Info -->
                        <div class="bg-light p-3 rounded-4 mb-4 fs-7 text-secondary">
                            <div class="mb-1"><i class="bi bi-building text-primary me-2"></i><strong><?= esc($student['perusahaan']) ?></strong></div>
                            <div class="mb-1"><i class="bi bi-briefcase text-primary me-2"></i><?= esc($student['bidang']) ?></div>
                            <div class="mt-2 pt-2 border-top border-secondary-subtle fs-8"><i class="bi bi-calendar3 text-primary me-2"></i><?= date('d M Y', strtotime($student['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($student['tanggal_selesai'])) ?></div>
                        </div>

                        <!-- Progress Section -->
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted fs-8">Progress Logbook</span>
                                <strong class="text-dark fs-7"><?= $student['progress'] ?>%</strong>
                            </div>
                            <div class="progress mb-3" style="height: 8px; border-radius: 999px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $student['progress'] ?>%"></div>
                            </div>
                            <div class="text-muted fs-8 mb-4">Terisi <strong><?= $student['jumlah_logbook'] ?></strong> dari total <strong><?= $student['total_hari'] ?></strong> hari PKL.</div>
                            
                            <a href="/bimbingan/detail/<?= $student['id'] ?>" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold fs-7">
                                Detail Bimbingan <i class="bi bi-arrow-right-short ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
