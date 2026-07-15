<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <div class="page-title">

            <h2 class="fw-bold mb-0">Data Mahasiswa</h2>
        </div>
        <p class="text-muted mb-0">Kelola data mahasiswa secara modern dan terstruktur.</p>
    </div>
    <a href="/mahasiswa/create" class="btn btn-primary">+ Tambah Mahasiswa</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Mahasiswa</h6>
                        <h3 class="fw-bold mb-0"><?= esc($totalMahasiswa ?? 0) ?></h3>
                    </div>
                    <span class="fs-3">‍</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Aktif Saat Ini</h6>
                        <h3 class="fw-bold mb-0"><?= esc($aktifMahasiswa ?? 0) ?></h3>
                    </div>
                    <span class="fs-3"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pencarian</h6>
                        <h3 class="fw-bold mb-0">Cepat</h3>
                    </div>
                    <span class="fs-3"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="get" action="/mahasiswa" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Cari mahasiswa</label>
                <input type="text" name="search" class="form-control" value="<?= esc($search ?? '') ?>" placeholder="NIM, nama, atau prodi">
            </div>
            <div class="col-md-4">
                <label class="form-label">Program Studi</label>
                <select name="prodi" class="form-select">
                    <option value="">Semua</option>
                    <option value="Informatika" <?= ($prodi ?? '') === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                    <option value="Sipil" <?= ($prodi ?? '') === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                    <option value="Industri" <?= ($prodi ?? '') === 'Industri' ? 'selected' : '' ?>>Industri</option>
                    <option value="Mesin" <?= ($prodi ?? '') === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
                <a href="/mahasiswa" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success d-flex align-items-center gap-2 rounded-4">
        <span></span>
        <div><?= session()->getFlashdata('success') ?></div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2 rounded-4">
        <span>️</span>
        <div><?= esc(session()->getFlashdata('error')) ?></div>
    </div>
<?php endif; ?>

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

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th>Telepon</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mahasiswa)): ?>
                        <?php foreach ($mahasiswa as $row): ?>
                            <tr>
                                <td><?= esc($row['id']) ?></td>
                                <td><?= esc($row['nim']) ?></td>
                                <td><?= esc($row['nama']) ?></td>
                                <td><?= esc($row['prodi']) ?></td>
                                <td><?= esc($row['angkatan']) ?></td>
                                <td><?= esc($row['telepon']) ?></td>
                                <td><span class="badge rounded-pill bg-success-subtle text-success">Aktif</span></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="/mahasiswa/detail/<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">Detail</a>
                                        <a href="/mahasiswa/edit/<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="/mahasiswa/delete/<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus data mahasiswa ini?')">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data mahasiswa.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>