<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <div class="page-header">
        <div class="page-title">
            <div>
                <h4>Tambah Akun Mahasiswa</h4>
                <p class="text-muted small mb-0">Membuat akun mahasiswa baru untuk monitoring PKL.</p>
            </div>
        </div>
        <a href="/admin/mahasiswa" class="btn btn-light border d-flex align-items-center gap-2">
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
            <form method="POST" action="/admin/mahasiswa/store" class="row g-3">
                <?= csrf_field() ?>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Ahmad Fauzi" value="<?= old('nama') ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="nim">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" name="nim" id="nim" class="form-control" placeholder="220401010042" value="<?= old('nim') ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="prodi">Program Studi</label>
                    <select name="prodi" id="prodi" class="form-select" required>
                        <option value="">-- Pilih Program Studi --</option>
                        <option value="Informatika" <?= old('prodi') === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                        <option value="Sipil" <?= old('prodi') === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                        <option value="Industri" <?= old('prodi') === 'Industri' ? 'selected' : '' ?>>Industri</option>
                        <option value="Mesin" <?= old('prodi') === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                    </select>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="mahasiswa@domain.com" value="<?= old('email') ?>" required>
                </div>

                <div class="col-lg-6 col-md-6 col-12">
                    <label class="form-label" for="password">Password Baru</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="reset" class="btn btn-light border px-4 me-2">Reset</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan Mahasiswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
