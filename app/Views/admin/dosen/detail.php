<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="page-header">
        <div class="page-title">

            <div>
                <h4>Detail Bimbingan Dosen</h4>
                <p class="text-muted small mb-0">Informasi profil dosen lengkap dan daftar mahasiswa yang dibimbing.</p>
            </div>
        </div>
        <a href="/admin/dosen" class="btn btn-light border d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <!-- Dosen Profile Card -->
        <div class="col-lg-4 col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="topbar-avatar mx-auto mb-3" style="width: 72px; height: 72px; font-size: 1.5rem;">
                        <?= strtoupper(mb_substr($user['nama'], 0, 2)) ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= esc($user['nama']) ?></h5>
                    <p class="text-muted small mb-2">NIDN. <?= esc($user['nidn'] ?? '-') ?></p>
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
        </div>

        <!-- Student Bimbingan list -->
        <div class="col-lg-8 col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-people-fill me-2 text-primary"></i>Mahasiswa Bimbingan PKL</h6>
                    <?php if (empty($bimbingan)): ?>
                        <div class="empty-state py-5">
                            <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                            <p class="mb-0 mt-2">Dosen ini belum ditunjuk untuk membimbing mahasiswa PKL.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mahasiswa</th>
                                        <th>Perusahaan / Lokasi</th>
                                        <th>Bidang</th>
                                        <th>Status PKL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bimbingan as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= esc($item['mahasiswa_nama']) ?></div>
                                                <div class="text-muted small">NIM: <?= esc($item['mahasiswa_nim']) ?></div>
                                            </td>
                                            <td><?= esc($item['perusahaan']) ?></td>
                                            <td><?= esc($item['bidang']) ?></td>
                                            <td>
                                                <?php if ($item['status'] === 'aktif'): ?>
                                                    <span class="badge bg-warning-subtle text-warning">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success-subtle text-success">Selesai</span>
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
