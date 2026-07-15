<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="page-header">
        <div class="page-title">

            <div>
                <h4>Edit Akun Dosen</h4>
                <p class="text-muted small mb-0">Ubah informasi biodata, prodi, email, atau password untuk <?= esc($user['nama']) ?>.</p>
            </div>
        </div>
        <a href="/admin/dosen" class="btn btn-light border d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger" style="border-radius: 12px;">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="/admin/dosen/update/<?= $user['id'] ?>" class="row g-3">
                <?= csrf_field() ?>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="nama">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?= old('nama', $user['nama']) ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="nidn">Nomor Induk Dosen Nasional (NIDN)</label>
                    <input type="text" name="nidn" id="nidn" class="form-control" value="<?= old('nidn', $user['nidn']) ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="prodi">Program Studi</label>
                    <select name="prodi" id="prodi" class="form-select" required>
                        <option value="">-- Pilih Program Studi --</option>
                        <option value="Informatika" <?= old('prodi', $user['prodi'] ?? '') === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                        <option value="Sipil" <?= old('prodi', $user['prodi'] ?? '') === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                        <option value="Industri" <?= old('prodi', $user['prodi'] ?? '') === 'Industri' ? 'selected' : '' ?>>Industri</option>
                        <option value="Mesin" <?= old('prodi', $user['prodi'] ?? '') === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= old('email', $user['email']) ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="password">Password Baru (Opsional)</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
