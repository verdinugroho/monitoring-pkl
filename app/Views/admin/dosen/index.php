<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-2">
    <!-- Header -->
    <div class="page-header">
        <div class="page-title">

            <div>
                <h4>Kelola Akun Dosen</h4>
                <p class="text-muted small mb-0">Manajemen data profil, program studi, status login, dan review bimbingan dosen pembimbing.</p>
            </div>
        </div>
        <a href="/admin/dosen/create" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-person-plus-fill"></i> Tambah Dosen
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="/admin/dosen" class="row g-3">
                <div class="col-lg-5 col-md-6 col-12">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama, NIDN, atau email..." value="<?= esc($search) ?>">
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-12">
                    <select name="prodi" class="form-select">
                        <option value="">-- Semua Program Studi --</option>
                        <option value="Informatika" <?= $prodi === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                        <option value="Sipil" <?= $prodi === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                        <option value="Industri" <?= $prodi === 'Industri' ? 'selected' : '' ?>>Industri</option>
                        <option value="Mesin" <?= $prodi === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-2 col-12 d-grid">
                    <button type="submit" class="btn btn-secondary">Filter & Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($dosenList)): ?>
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    <p class="mb-0">Tidak ditemukan data dosen yang cocok.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive" style="min-height: 350px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama / NIDN</th>
                                <th>Program Studi</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dosenList as $user): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold text-dark"><?= esc($user['nama']) ?></div>
                                        <div class="text-muted small">NIDN: <?= esc($user['nidn'] ?? '-') ?></div>
                                    </td>
                                    <td><?= esc($user['prodi'] ?? '-') ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <?php if (($user['status_akun'] ?? 'aktif') === 'aktif'): ?>
                                            <span class="badge bg-success-subtle text-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-light btn-sm border" type="button" data-bs-toggle="dropdown" data-bs-strategy="fixed" data-bs-auto-close="true">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius:12px;">
                                                <li><a class="dropdown-item" href="/admin/dosen/detail/<?= $user['id'] ?>"><i class="bi bi-eye-fill text-info me-2"></i>Detail Bimbingan</a></li>
                                                <li><a class="dropdown-item" href="/admin/dosen/edit/<?= $user['id'] ?>"><i class="bi bi-pencil-fill text-primary me-2"></i>Edit Data</a></li>
                                                <li><a class="dropdown-item" href="/admin/dosen/toggle/<?= $user['id'] ?>"><i class="bi bi-power me-2 <?= ($user['status_akun'] ?? 'aktif') === 'aktif' ? 'text-danger' : 'text-success' ?>"></i><?= ($user['status_akun'] ?? 'aktif') === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?></a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="confirmReset(<?= $user['id'] ?>)"><i class="bi bi-key-fill text-warning me-2"></i>Reset Password</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="confirmDelete(<?= $user['id'] ?>)"><i class="bi bi-trash-fill me-2"></i>Hapus Akun</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center p-4 border-top">
                        <div class="text-muted small">
                            Menampilkan <?= count($dosenList) ?> dari total <?= $total ?> dosen
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&prodi=<?= urlencode($prodi) ?>&page=<?= $page - 1 ?>">Sebelumnya</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                                        <a class="page-link" href="?search=<?= urlencode($search) ?>&prodi=<?= urlencode($prodi) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&prodi=<?= urlencode($prodi) ?>&page=<?= $page + 1 ?>">Berikutnya</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Akun Dosen?',
        text: "Seluruh relasi bimbingan dosen ini di database akan terpengaruh!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '12px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/dosen/delete/" + id;
        }
    });
}

function confirmReset(id) {
    Swal.fire({
        title: 'Reset Password?',
        text: "Password dosen ini akan direset kembali ke default (password123)!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#F59E0B',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal',
        borderRadius: '12px'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/dosen/reset-password/" + id;
        }
    });
}
</script>
<?= $this->endSection() ?>
