<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/bimbingan" class="text-decoration-none text-primary">Mahasiswa Bimbingan</a></li>
            <li class="breadcrumb-item"><a href="/bimbingan/detail/<?= $internship['id'] ?>" class="text-decoration-none text-primary">Detail Bimbingan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Review Logbook</li>
        </ol>
    </nav>

    <!-- Flash Notifications -->
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
        <!-- Logbook Details Column -->
        <div class="col-lg-7">
            <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Laporan Aktivitas Harian</h5>
                    <span class="badge rounded-pill bg-light text-secondary border px-3 py-1"><?= date('d M Y', strtotime($logbook['tanggal'])) ?></span>
                </div>

                <div class="row g-3 fs-7 mb-4">
                    <div class="col-md-6">
                        <span class="text-muted block">Nama Mahasiswa</span>
                        <strong class="text-dark d-block"><?= esc($student['nama']) ?></strong>
                    </div>
                    <div class="col-md-6">
                        <span class="text-muted block">Jam Kerja PKL</span>
                        <strong class="text-dark d-block"><?= date('H:i', strtotime($logbook['jam_mulai'])) ?> - <?= date('H:i', strtotime($logbook['jam_selesai'])) ?></strong>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold text-dark small mb-1">Aktivitas Kegiatan</label>
                    <div class="bg-light p-3 rounded-3 text-secondary small border-start border-primary border-3">
                        <?= nl2br(esc($logbook['aktivitas'])) ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold text-dark small mb-1">Hasil Pekerjaan</label>
                    <div class="bg-light p-3 rounded-3 text-secondary small border-start border-success border-3">
                        <?= nl2br(esc($logbook['hasil'])) ?>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="fw-semibold text-dark small mb-1">Kendala / Hambatan</label>
                    <div class="bg-light p-3 rounded-3 text-danger small border-start border-danger border-3">
                        <?= $logbook['kendala'] ? nl2br(esc($logbook['kendala'])) : '<span class="text-muted italic">Tidak ada kendala yang dilaporkan.</span>' ?>
                    </div>
                </div>

                <div>
                    <label class="fw-semibold text-dark small mb-2">Lampiran Dokumentasi</label>
                    <div>
                        <?php if ($logbook['dokumentasi']): ?>
                            <?php $ext = pathinfo($logbook['dokumentasi'], PATHINFO_EXTENSION); ?>
                            <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                                <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank">
                                    <img src="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" class="img-fluid img-thumbnail rounded-3 shadow-sm" style="max-height: 250px; object-fit: contain;">
                                </a>
                            <?php else: ?>
                                <div class="d-flex align-items-center gap-2 bg-light p-3 rounded-3 border">
                                    <span class="fs-2 text-danger"><i class="bi bi-file-pdf-fill"></i></span>
                                    <div>
                                        <div class="fw-semibold small"><?= esc($logbook['dokumentasi']) ?></div>
                                        <a href="/uploads/logbooks/<?= $logbook['dokumentasi'] ?>" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-3 fs-8 mt-1 rounded-pill"><i class="bi bi-eye"></i> Lihat Berkas PDF</a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted small">Tidak ada dokumentasi terlampir.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review & Comments Column -->
        <div class="col-lg-5">
            <!-- Review status panel -->
            <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Evaluasi & Persetujuan</h5>

                <form method="post" action="/bimbingan/logbook/status" class="mb-0">
                    <?= csrf_field() ?>
                    <input type="hidden" name="logbook_id" value="<?= $logbook['id'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Status Review</label>
                        <select name="status_review" class="form-select rounded-3" required>
                            <option value="Menunggu Review" <?= $logbook['status_review'] === 'Menunggu Review' ? 'selected' : '' ?>>Menunggu Review</option>
                            <option value="Direvisi" <?= $logbook['status_review'] === 'Direvisi' ? 'selected' : '' ?>>Direvisi (Perlu Perbaikan)</option>
                            <option value="Disetujui" <?= $logbook['status_review'] === 'Disetujui' ? 'selected' : '' ?>>Disetujui (ACC)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Catatan / Evaluasi Ringkas</label>
                        <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Misal: Laporan kegiatan sudah bagus / Mohon lengkapi dokumentasi..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2.5 rounded-pill fw-semibold shadow-sm">
                        Simpan Evaluasi
                    </button>
                </form>
            </div>

            <!-- Comment feed panel -->
            <div class="card border-0 bg-white shadow-sm p-4" style="border-radius: 18px;">
                <h5 class="fw-bold text-dark mb-4"><i class="bi bi-chat-text text-primary me-2"></i>Diskusi & Komentar</h5>

                <!-- Input comment form -->
                <form method="post" action="/bimbingan/logbook/comment" class="mb-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="logbook_id" value="<?= $logbook['id'] ?>">
                    
                    <div class="input-group">
                        <input type="text" name="komentar" class="form-control rounded-start-pill border-end-0 py-2" placeholder="Tulis komentar baru..." required>
                        <button class="btn btn-primary rounded-end-pill px-3" type="submit">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </form>

                <!-- Comments Timeline -->
                <?php if (empty($comments)): ?>
                    <div class="text-center py-4 text-muted small">
                        <span class="fs-2"></span>
                        <p class="mb-0">Belum ada diskusi untuk logbook ini.</p>
                    </div>
                <?php else: ?>
                    <div class="comments-timeline" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                        <?php foreach ($comments as $comment): ?>
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-dark small"><i class="bi bi-person me-1"></i><?= esc($comment['dosen_nama']) ?></strong>
                                    <span class="text-muted fs-8"><?= date('d/m H:i', strtotime($comment['created_at'])) ?></span>
                                </div>
                                <p class="small text-secondary mb-0 bg-light p-2 rounded-3">
                                    <?= esc($comment['komentar']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
