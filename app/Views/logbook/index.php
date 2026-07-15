<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary fw-medium">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Logbook Harian</li>
        </ol>
    </nav>

    <!-- Success/Error Flash Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-center">
                <span class="fs-4 me-2"></span>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 alert-dismissible fade show p-3" role="alert">
            <div class="d-flex align-items-center">
                <span class="fs-4 me-2">️</span>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Control Header (Search & Add Button) -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="row align-items-center g-3">
            <div class="col-md-6">
                <h4 class="fw-bold mb-1 text-dark">Logbook Harian PKL</h4>
                <p class="text-muted small mb-0">Catat dan kelola setiap aktivitas harian Anda selama menjalani magang.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                    <form method="get" action="/logbook" class="d-flex gap-2 align-items-center w-100 w-sm-auto mb-2 mb-sm-0">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari aktivitas..." value="<?= esc($search) ?>">
                            <button class="btn btn-outline-secondary rounded-end-pill bg-white border-start-0 text-muted" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <?php if (!empty($search)): ?>
                            <a href="/logbook" class="btn btn-light rounded-pill"><i class="bi bi-x-lg"></i></a>
                        <?php endif; ?>
                    </form>
                    <a href="/logbook/create" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold shadow-sm"><i class="bi bi-plus-lg me-1"></i> Tambah Logbook</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 bg-white shadow-sm mb-4" style="border-radius: 18px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3" style="width: 120px;">Tanggal</th>
                        <th class="py-3" style="width: 110px;">Jam</th>
                        <th class="py-3" style="min-width: 200px;">Aktivitas</th>
                        <th class="py-3" style="min-width: 200px;">Hasil Pekerjaan</th>
                        <th class="py-3" style="width: 140px;">Dokumentasi</th>
                        <th class="py-3" style="width: 150px;">Status</th>
                        <th class="pe-4 py-3 text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($logbooks)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <span class="fs-1 text-muted opacity-50"></span>
                                    <h5 class="fw-bold text-secondary mt-3">Tidak Ada Data Logbook</h5>
                                    <p class="text-muted small">Mulai tambahkan kegiatan harian PKL Anda.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logbooks as $logbook): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark">
                                    <?= date('d M Y', strtotime($logbook['tanggal'])) ?>
                                </td>
                                <td class="text-secondary small">
                                    <?= date('H:i', strtotime($logbook['jam_mulai'])) ?> - <?= date('H:i', strtotime($logbook['jam_selesai'])) ?>
                                </td>
                                <td>
                                    <span class="text-dark small text-wrap-limit"><?= esc($logbook['aktivitas']) ?></span>
                                    <?php if (!empty($logbook['kendala'])): ?>
                                        <div class="mt-1 small text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>Kendala: <?= esc($logbook['kendala']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-secondary small text-wrap-limit"><?= esc($logbook['hasil']) ?></span>
                                </td>
                                <td>
                                    <?php if ($logbook['dokumentasi']): ?>
                                        <?php $ext = pathinfo($logbook['dokumentasi'], PATHINFO_EXTENSION); ?>
                                        <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                                            <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank">
                                                <img src="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" class="img-thumbnail rounded-3 shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            </a>
                                        <?php else: ?>
                                            <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank" class="btn btn-xs btn-outline-danger rounded-3 py-1 px-2 fs-8">
                                                <i class="bi bi-file-pdf"></i> PDF
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($logbook['status_review'] === 'Disetujui'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2"><i class="bi bi-check-circle-fill me-1"></i> Disetujui</span>
                                    <?php elseif ($logbook['status_review'] === 'Direvisi'): ?>
                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-2"><i class="bi bi-pencil-square me-1"></i> Direvisi</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2"><i class="bi bi-clock me-1"></i> Menunggu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-1">
                                        <?php if ($logbook['status_review'] === 'Menunggu Review'): ?>
                                            <a href="/logbook/edit/<?= $logbook['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle border-0 p-2" title="Edit Logbook"><i class="bi bi-pencil-fill"></i></a>
                                            <button onclick="confirmDelete(<?= $logbook['id'] ?>)" class="btn btn-sm btn-outline-danger rounded-circle border-0 p-2" title="Hapus Logbook"><i class="bi bi-trash-fill"></i></button>
                                        <?php else: ?>
                                            <a href="/logbook/edit/<?= $logbook['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-circle border-0 p-2" title="Detail & Komentar"><i class="bi bi-chat-left-dots-fill text-primary"></i></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($pager && $pager->getPageCount() > 1): ?>
            <div class="d-flex justify-content-center py-4 bg-light">
                <?= $pager->links('logbooks', 'bootstrap_pagination') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- SweetAlert2 Confirms Delete -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Logbook?',
            text: "Data logbook yang dihapus tidak dapat dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            border: '0',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/logbook/delete/" + id;
            }
        });
    }
</script>
<?= $this->endSection() ?>
