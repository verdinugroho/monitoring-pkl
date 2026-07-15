<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h3 class="fw-bold mb-1">Edit Mahasiswa</h3>
                        <p class="text-muted mb-0">Perbarui informasi mahasiswa yang dipilih.</p>
                    </div>
                    <a href="/mahasiswa" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger rounded-4">
                        <div class="fw-semibold mb-2">Periksa kembali data berikut:</div>
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="/mahasiswa/update/<?= $mahasiswa['id'] ?? 0 ?>">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim" class="form-control" required value="<?= esc($mahasiswa['nim'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required value="<?= esc($mahasiswa['nama'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" required value="<?= esc($mahasiswa['prodi'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Angkatan</label>
                            <input type="number" name="angkatan" class="form-control" required value="<?= esc($mahasiswa['angkatan'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" required value="<?= esc($mahasiswa['telepon'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required value="<?= esc($mahasiswa['alamat'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="/mahasiswa" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>