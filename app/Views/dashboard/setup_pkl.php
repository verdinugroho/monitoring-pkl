<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center fade-in-up">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="text-center mb-4">
                    <div class="page-title mb-2">

                        <h2 class="fw-bold mb-0">Konfigurasi Data PKL</h2>
                    </div>
                    <p class="text-muted">Lengkapi data Praktik Kerja Lapangan (PKL) Anda untuk memulai pengisian logbook harian.</p>
                </div>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger rounded-4">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="/setup-pkl/store">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nama Perusahaan / Instansi</label>
                            <input type="text" name="perusahaan" class="form-control rounded-3" placeholder="Contoh: PT Teknologi Nusantara" required value="<?= old('perusahaan') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bidang Pekerjaan / Divisi</label>
                            <input type="text" name="bidang" class="form-control rounded-3" placeholder="Contoh: Software Engineering" required value="<?= old('bidang') ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Program Studi</label>
                        <select name="prodi" class="form-select rounded-3" required>
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Informatika" <?= old('prodi', $user['prodi'] ?? '') === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                            <option value="Sipil" <?= old('prodi', $user['prodi'] ?? '') === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                            <option value="Industri" <?= old('prodi', $user['prodi'] ?? '') === 'Industri' ? 'selected' : '' ?>>Industri</option>
                            <option value="Mesin" <?= old('prodi', $user['prodi'] ?? '') === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                        </select>
                        <div class="form-text text-muted">Pastikan program studi Anda sesuai.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Dosen Pembimbing</label>
                        <select name="dosen_id" class="form-select rounded-3" required>
                            <option value="">Pilih Dosen Pembimbing</option>
                            <?php foreach ($dosenList as $dosen): ?>
                                <option value="<?= $dosen['id'] ?>" <?= old('dosen_id') == $dosen['id'] ? 'selected' : '' ?>><?= esc($dosen['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">Dosen yang ditunjuk fakultas untuk membimbing PKL Anda.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control rounded-3" required value="<?= old('tanggal_mulai') ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control rounded-3" required value="<?= old('tanggal_selesai') ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold shadow-sm">
                        Simpan Data PKL & Lanjutkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
