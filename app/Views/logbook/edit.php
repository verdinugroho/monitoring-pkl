<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/logbook" class="text-decoration-none text-primary">Logbook</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $logbook['status_review'] === 'Menunggu Review' ? 'Edit Logbook' : 'Detail Logbook' ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-8">
            <div class="card border-0 bg-white shadow-sm" style="border-radius: 18px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 bg-primary-subtle text-primary rounded-4">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1 text-dark"><?= $logbook['status_review'] === 'Menunggu Review' ? 'Edit Logbook Harian' : 'Detail Logbook Harian' ?></h4>
                                <p class="text-muted small mb-0">Status: 
                                    <?php if ($logbook['status_review'] === 'Disetujui'): ?>
                                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1"><i class="bi bi-check-circle-fill me-1"></i> Disetujui</span>
                                    <?php elseif ($logbook['status_review'] === 'Direvisi'): ?>
                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-1"><i class="bi bi-pencil-square me-1"></i> Direvisi</span>
                                    <?php else: ?>
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1"><i class="bi bi-clock me-1"></i> Menunggu Review</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($logbook['status_review'] !== 'Menunggu Review'): ?>
                        <div class="alert alert-warning rounded-4 border-0 shadow-sm mb-4">
                            <div class="d-flex align-items-center">
                                <span class="fs-4 me-2"></span>
                                <div class="small">Logbook ini telah direview oleh Dosen Pembimbing Anda dan telah dikunci. Anda tidak dapat melakukan perubahan.</div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="editLogbookForm" method="post" action="/logbook/update/<?= $logbook['id'] ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <?php $isReadOnly = $logbook['status_review'] !== 'Menunggu Review' ? 'disabled' : ''; ?>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal" class="form-control rounded-3 py-2" required value="<?= old('tanggal') ?? $logbook['tanggal'] ?>" <?= $isReadOnly ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control rounded-3 py-2" required value="<?= old('jam_mulai') ?? substr($logbook['jam_mulai'], 0, 5) ?>" <?= $isReadOnly ?>>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control rounded-3 py-2" required value="<?= old('jam_selesai') ?? substr($logbook['jam_selesai'], 0, 5) ?>" <?= $isReadOnly ?>>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Deskripsi Aktivitas</label>
                            <textarea name="aktivitas" class="form-control rounded-3" rows="4" required <?= $isReadOnly ?>><?= old('aktivitas') ?? $logbook['aktivitas'] ?></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Hasil Pekerjaan</label>
                            <textarea name="hasil" class="form-control rounded-3" rows="3" required <?= $isReadOnly ?>><?= old('hasil') ?? $logbook['hasil'] ?></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Kendala & Hambatan <span class="text-muted small fw-normal">(Opsional)</span></label>
                            <textarea name="kendala" class="form-control rounded-3" rows="2" <?= $isReadOnly ?>><?= old('kendala') ?? $logbook['kendala'] ?></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Dokumentasi Saat Ini</label>
                            <div class="mb-2">
                                <?php if ($logbook['dokumentasi']): ?>
                                    <?php $ext = pathinfo($logbook['dokumentasi'], PATHINFO_EXTENSION); ?>
                                    <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                                        <div class="position-relative d-inline-block">
                                            <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank">
                                                <img src="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 200px; object-fit: contain;">
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center gap-2 bg-light p-3 rounded-3 border">
                                            <span class="fs-2 text-danger"><i class="bi bi-file-pdf-fill"></i></span>
                                            <div>
                                                <div class="fw-semibold small"><?= esc($logbook['dokumentasi']) ?></div>
                                                <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-3 fs-8 mt-1 rounded-pill"><i class="bi bi-eye"></i> Lihat Berkas</a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak ada dokumentasi terunggah.</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($logbook['status_review'] === 'Menunggu Review'): ?>
                                <label class="form-label fw-semibold text-dark mt-2">Ganti Dokumentasi <span class="text-muted small fw-normal">(Opsional)</span></label>
                                <input type="file" name="dokumentasi" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text text-muted">Format file: <strong>JPG, JPEG, PNG, PDF</strong>. Ukuran maks <strong>5 MB</strong>. Biarkan kosong jika tidak ingin mengubah dokumentasi.</div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-5 d-flex gap-3 justify-content-end">
                            <a href="/logbook" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold">Kembali</a>
                            <?php if ($logbook['status_review'] === 'Menunggu Review'): ?>
                                <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold shadow-sm" id="btnSubmit">
                                    <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                                    Simpan Perubahan
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Comments History Column -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px; min-height: 250px;">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-chat-dots text-primary me-2"></i>Riwayat Review & Komentar</h5>

                <?php if (empty($comments)): ?>
                    <div class="text-center py-5 my-auto text-muted">
                        <span class="fs-1 opacity-40"></span>
                        <p class="small mt-2">Belum ada komentar revisi atau persetujuan dari Dosen Pembimbing untuk logbook ini.</p>
                    </div>
                <?php else: ?>
                    <div class="comments-timeline" style="max-height: 450px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($comments as $comment): ?>
                            <div class="bg-light p-3 rounded-4 mb-3 border-start border-3 border-primary shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-dark small"><i class="bi bi-person me-1"></i><?= esc($comment['dosen_nama']) ?></span>
                                    <span class="text-muted fs-8"><?= date('d M Y H:i', strtotime($comment['created_at'])) ?></span>
                                </div>
                                <p class="small text-secondary mb-0 bg-white p-2 rounded-3 border">
                                    "<?= esc($comment['komentar']) ?>"
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('editLogbookForm');
        const submitBtn = document.getElementById('btnSubmit');
        const spinner = document.getElementById('btnSpinner');

        if (form && submitBtn && spinner) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
            });
        }
    });
</script>
<?= $this->endSection() ?>
