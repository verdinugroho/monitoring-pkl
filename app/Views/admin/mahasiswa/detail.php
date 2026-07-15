<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="page-header">
        <div class="page-title">
            <div>
                <h4>Detail Monitoring Mahasiswa</h4>
                <p class="text-muted small mb-0">Informasi profil lengkap, konfigurasi PKL, pembimbing, dan logbook harian.</p>
            </div>
        </div>
        <a href="/admin/mahasiswa" class="btn btn-light border d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Student Profil card -->
        <div class="col-lg-4 col-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <div class="topbar-avatar mx-auto mb-3" style="width: 72px; height: 72px; font-size: 1.5rem;">
                        <?= strtoupper(mb_substr($user['nama'], 0, 2)) ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= esc($user['nama']) ?></h5>
                    <p class="text-muted small mb-2">NIM. <?= esc($user['nim'] ?? '-') ?></p>
                    <span class="badge bg-primary-subtle text-primary mb-3"><?= esc($user['prodi'] ?? '-') ?></span>
                    <hr>
                    <div class="text-start">
                        <label class="text-muted small">Alamat Email</label>
                        <p class="fw-semibold mb-2"><?= esc($user['email']) ?></p>
                        <label class="text-muted small">Status Akun</label>
                        <p class="mb-0">
                            <?php if (($user['status_akun'] ?? 'aktif') === 'aktif'): ?>
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Dosen & PKL details -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Informasi PKL</h6>
                    <?php if (!$internship): ?>
                        <div class="empty-state py-3">
                            <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                            <p class="mb-0 small">Belum mengonfigurasi tempat dan dosen PKL.</p>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="text-muted small">Perusahaan / Lokasi</label>
                            <p class="fw-semibold mb-2"><?= esc($internship['perusahaan']) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Bidang PKL</label>
                            <p class="fw-semibold mb-2"><?= esc($internship['bidang']) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Dosen Pembimbing</label>
                            <p class="fw-semibold mb-2"><?= $dosen ? esc($dosen['nama']) : 'Belum ditentukan' ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Durasi Kegiatan</label>
                            <p class="fw-semibold mb-2"><?= date('d M Y', strtotime($internship['tanggal_mulai'])) ?> s/d <?= date('d M Y', strtotime($internship['tanggal_selesai'])) ?></p>
                        </div>
                        <div>
                            <label class="text-muted small">Status PKL</label>
                            <p class="mb-0">
                                <?php if ($internship['status'] === 'aktif'): ?>
                                    <span class="badge bg-warning-subtle text-warning">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-success-subtle text-success">Selesai</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Logbooks tracking list -->
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-journal-text me-2 text-primary"></i>Riwayat Logbook Harian</h6>
                    <?php if (empty($logbooks)): ?>
                        <div class="empty-state py-5">
                            <i class="bi bi-journal-x"></i>
                            <p class="mb-0">Belum ada pengisian logbook harian oleh mahasiswa.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Aktivitas Harian</th>
                                        <th>Status Review</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($logbooks as $logbook): ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($logbook['tanggal'])) ?></td>
                                            <td><?= esc($logbook['jam_mulai']) ?> - <?= esc($logbook['jam_selesai']) ?></td>
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
    </div>
</div>
<?= $this->endSection() ?>
