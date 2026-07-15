<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dokumentasi & Laporan</li>
        </ol>
    </nav>

    <!-- Success/Error Alerts -->
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

    <div class="row g-4">
        <!-- Upload Form Column -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-cloud-arrow-up text-primary me-2"></i>Unggah Berkas Baru</h5>
                <p class="text-muted small">Upload berkas dokumentasi foto, laporan mingguan, atau laporan akhir PKL Anda.</p>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-2 fs-7 mb-3">
                        <ul class="mb-0 ps-3">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form id="uploadForm" method="post" action="/documents/upload" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Jenis Berkas</label>
                        <select name="jenis_file" class="form-select rounded-3" required>
                            <option value="">Pilih Jenis Berkas</option>
                            <option value="foto" <?= old('jenis_file') === 'foto' ? 'selected' : '' ?>>Foto Kegiatan</option>
                            <option value="mingguan" <?= old('jenis_file') === 'mingguan' ? 'selected' : '' ?>>Laporan Mingguan</option>
                            <option value="akhir" <?= old('jenis_file') === 'akhir' ? 'selected' : '' ?>>Laporan Akhir PKL</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-dark">Pilih Berkas</label>
                        <input type="file" name="document_file" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf" required>
                        <div class="form-text text-muted fs-8 mt-1">Format: <strong>JPG, JPEG, PNG, PDF</strong>. Ukuran maks <strong>5 MB</strong>.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-semibold shadow-sm" id="btnSubmit">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                        Unggah Berkas
                    </button>
                </form>
            </div>
        </div>

        <!-- Files List Column -->
        <div class="col-lg-8">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-folder-symlink text-primary me-2"></i>Daftar Berkas Terunggah</h5>

                <?php if (empty($documents)): ?>
                    <div class="text-center py-5 text-muted">
                        <span class="fs-1 opacity-45"></span>
                        <h6 class="fw-bold text-secondary mt-3">Belum Ada Berkas</h6>
                        <p class="small text-muted">Berkas yang Anda unggah akan terdaftar di sini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-3 py-2">Nama Berkas</th>
                                    <th class="py-2">Jenis</th>
                                    <th class="py-2">Tanggal Unggah</th>
                                    <th class="pe-3 py-2 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $doc): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <?php 
                                                $ext = pathinfo($doc['nama_file'], PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                                $icon = $isImage ? 'bi-image text-success' : 'bi-file-pdf text-danger';
                                                ?>
                                                <i class="bi <?= $icon ?> fs-4"></i>
                                                <span class="text-dark small text-truncate" style="max-width: 250px;" title="<?= esc($doc['nama_file']) ?>"><?= esc($doc['nama_file']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($doc['jenis_file'] === 'foto'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1 fs-8">Foto</span>
                                            <?php elseif ($doc['jenis_file'] === 'mingguan'): ?>
                                                <span class="badge rounded-pill bg-primary-subtle text-primary px-2 py-1 fs-8">Lap. Mingguan</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1 fs-8">Lap. Akhir</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-secondary small">
                                            <?= date('d M Y H:i', strtotime($doc['created_at'])) ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-inline-flex gap-1">
                                                <a href="/uploads/documents/<?= $doc['nama_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle border-0 p-2" title="Unduh / Lihat"><i class="bi bi-eye"></i></a>
                                                <button onclick="confirmDelete(<?= $doc['id'] ?>)" class="btn btn-sm btn-outline-danger rounded-circle border-0 p-2" title="Hapus"><i class="bi bi-trash"></i></button>
                                            </div>
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

<!-- SweetAlert2 Confirms Delete -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Berkas?',
            text: "Berkas yang dihapus tidak dapat dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/documents/delete/" + id;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('uploadForm');
        const submitBtn = document.getElementById('btnSubmit');
        const spinner = document.getElementById('btnSpinner');

        if (form) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
            });
        }
    });
</script>
<?= $this->endSection() ?>
