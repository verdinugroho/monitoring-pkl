<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="page-title">

                <h2 class="fw-bold mb-0">Detail Mahasiswa</h2>
            </div>
            <p class="text-muted mb-0">Informasi lengkap terkait mahasiswa yang dipilih.</p>
        </div>
        <a href="/mahasiswa" class="btn btn-outline-secondary">Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-4">
                    <div class="mx-auto mb-3" style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#38bdf8,#6366f1);display:grid;place-items:center;color:white;font-size:1.7rem;">
                        
                    </div>
                    <h4 class="fw-bold mb-1"><?= esc($mahasiswa['nama'] ?? '-') ?></h4>
                    <p class="text-muted mb-0">NIM: <?= esc($mahasiswa['nim'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['nama'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">NIM</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['nim'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Program Studi</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['prodi'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Angkatan</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['angkatan'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Telepon</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['telepon'] ?? '-') ?></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Alamat</label>
                            <div class="fw-semibold"><?= esc($mahasiswa['alamat'] ?? '-') ?></div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <a href="/mahasiswa/edit/<?= esc($mahasiswa['id'] ?? 0) ?>" class="btn btn-primary">Edit Data</a>
                        <a href="/mahasiswa/delete/<?= esc($mahasiswa['id'] ?? 0) ?>" class="btn btn-outline-danger" onclick="return confirm('Hapus data mahasiswa ini?')">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
