<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/bimbingan" class="text-decoration-none text-primary">Mahasiswa Bimbingan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Bimbingan</li>
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

    <!-- Student Header Summary Card -->
    <div class="card border-0 bg-white shadow-sm p-4 mb-4" style="border-radius: 18px;">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill bg-primary px-3 py-1 fs-8">Status: <?= esc(ucfirst($internship['status'])) ?></span>
                    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($internship['tanggal_mulai'])) ?> - <?= date('d M Y', strtotime($internship['tanggal_selesai'])) ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-1"><?= esc($student['nama']) ?></h3>
                <p class="text-secondary mb-0">Perusahaan: <strong class="text-dark"><?= esc($internship['perusahaan']) ?> (<?= esc($internship['bidang']) ?>)</strong></p>
                <p class="text-muted small mb-0"><i class="bi bi-envelope-fill me-1"></i><?= esc($student['email']) ?></p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="/bimbingan/export-pdf/<?= $internship['id'] ?>" target="_blank" class="btn btn-outline-danger px-4 py-2 rounded-pill fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i> Export Logbook ke PDF</a>
            </div>
        </div>
    </div>

    <!-- Stats & Progress Summary Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-white shadow-sm p-4 h-100" style="border-radius: 18px;">
                <div class="text-muted small mb-1">Progress PKL</div>
                <h4 class="fw-bold text-dark mb-2"><?= $progress ?>%</h4>
                <div class="progress mb-2" style="height: 6px; border-radius: 999px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $progress ?>%"></div>
                </div>
                <div class="text-muted fs-8">Terisi <?= $jumlahLogbook ?> dari <?= $totalHari ?> hari.</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-white shadow-sm p-4 h-100" style="border-radius: 18px;">
                <div class="text-muted small mb-1">Status Penilaian</div>
                <h4 class="fw-bold text-dark mb-2"><?= $assessment ? 'Selesai Dinilai' : 'Belum Dinilai' ?></h4>
                <div class="text-muted fs-8">
                    <?php if ($assessment): ?>
                        Nilai Akhir: <strong class="text-primary fs-6"><?= esc($assessment['nilai_akhir']) ?></strong>
                    <?php else: ?>
                        Menunggu penilaian dari Anda di tab Penilaian.
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-white shadow-sm p-4 h-100" style="border-radius: 18px;">
                <div class="text-muted small mb-1">Dokumen Diunggah</div>
                <h4 class="fw-bold text-dark mb-2"><?= count($documents) ?> Berkas</h4>
                <div class="text-muted fs-8">Foto kegiatan, laporan mingguan/akhir.</div>
            </div>
        </div>
    </div>

    <!-- Details Tab Panel Card -->
    <div class="card border-0 bg-white shadow-sm" style="border-radius: 18px; overflow: hidden;">
        <div class="card-header bg-white border-0 pt-3">
            <ul class="nav nav-tabs card-header-tabs" id="studentDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold text-secondary" id="logbook-tab" data-bs-toggle="tab" data-bs-target="#logbook-pane" type="button" role="tab" aria-controls="logbook-pane" aria-selected="true">Logbook Harian</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-secondary" id="document-tab" data-bs-toggle="tab" data-bs-target="#document-pane" type="button" role="tab" aria-controls="document-pane" aria-selected="false">Dokumen & Laporan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-secondary" id="assess-tab" data-bs-toggle="tab" data-bs-target="#assess-pane" type="button" role="tab" aria-controls="assess-pane" aria-selected="false">Penilaian Akhir</button>
                </li>
            </ul>
        </div>
        <div class="card-body p-4 tab-content" id="studentDetailTabsContent">
            <!-- TAB 1: LOGBOOK HARIAN -->
            <div class="tab-pane fade show active" id="logbook-pane" role="tabpanel" aria-labelledby="logbook-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Daftar Kegiatan Harian</h5>
                </div>
                
                <?php if (empty($logbooks)): ?>
                    <div class="text-center py-5 text-muted">
                        <span class="fs-1 opacity-50"></span>
                        <p class="small mt-2">Mahasiswa belum mengisi logbook harian.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-3" style="width: 130px;">Tanggal</th>
                                    <th style="width: 120px;">Jam</th>
                                    <th>Aktivitas</th>
                                    <th>Hasil Pekerjaan</th>
                                    <th style="width: 150px;">Status Review</th>
                                    <th class="pe-3 text-end" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logbooks as $logbook): ?>
                                    <tr>
                                        <td class="ps-3 fw-medium text-dark">
                                            <?= date('d M Y', strtotime($logbook['tanggal'])) ?>
                                        </td>
                                        <td class="text-secondary small">
                                            <?= date('H:i', strtotime($logbook['jam_mulai'])) ?> - <?= date('H:i', strtotime($logbook['jam_selesai'])) ?>
                                        </td>
                                        <td>
                                            <span class="text-dark small text-wrap-limit"><?= esc($logbook['aktivitas']) ?></span>
                                            <?php if ($logbook['kendala']): ?>
                                                <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle-fill me-1"></i>Kendala: <?= esc($logbook['kendala']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-secondary small">
                                            <span class="text-wrap-limit"><?= esc($logbook['hasil']) ?></span>
                                        </td>
                                        <td>
                                            <?php if ($logbook['status_review'] === 'Disetujui'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1 fs-8"><i class="bi bi-check-circle-fill me-1"></i> Disetujui</span>
                                            <?php elseif ($logbook['status_review'] === 'Direvisi'): ?>
                                                <span class="badge rounded-pill bg-warning-subtle text-warning px-3 py-1 fs-8"><i class="bi bi-pencil-square me-1"></i> Direvisi</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1 fs-8"><i class="bi bi-clock me-1"></i> Menunggu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="/bimbingan/logbook/<?= $logbook['id'] ?>" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fs-8 fw-semibold">Review</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 2: DOKUMEN & LAPORAN -->
            <div class="tab-pane fade" id="document-pane" role="tabpanel" aria-labelledby="document-tab">
                <h5 class="fw-bold text-dark mb-4">Berkas Pendukung & Laporan PKL</h5>
                
                <?php if (empty($documents)): ?>
                    <div class="text-center py-5 text-muted">
                        <span class="fs-1 opacity-50"></span>
                        <p class="small mt-2">Belum ada berkas pendukung yang diunggah oleh mahasiswa ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-3">Nama Berkas</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Unggah</th>
                                    <th class="pe-3 text-end">Unduh</th>
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
                                                <span class="text-dark small text-truncate" style="max-width: 320px;" title="<?= esc($doc['nama_file']) ?>"><?= esc($doc['nama_file']) ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($doc['jenis_file'] === 'foto'): ?>
                                                <span class="badge rounded-pill bg-success-subtle text-success px-2 py-1 fs-8">Foto Kegiatan</span>
                                            <?php elseif ($doc['jenis_file'] === 'mingguan'): ?>
                                                <span class="badge rounded-pill bg-primary-subtle text-primary px-2 py-1 fs-8">Laporan Mingguan</span>
                                            <?php else: ?>
                                                <span class="badge rounded-pill bg-danger-subtle text-danger px-2 py-1 fs-8">Laporan Akhir</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-secondary small">
                                            <?= date('d M Y H:i', strtotime($doc['created_at'])) ?>
                                        </td>
                                        <td class="pe-3 text-end">
                                            <a href="/uploads/documents/<?= $doc['nama_file'] ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle border-0 p-2"><i class="bi bi-download"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- TAB 3: PENILAIAN AKHIR -->
            <div class="tab-pane fade" id="assess-pane" role="tabpanel" aria-labelledby="assess-tab">
                <h5 class="fw-bold text-dark mb-4">Input Nilai Akhir Praktik Kerja Lapangan (PKL)</h5>

                <div class="row">
                    <!-- Grade Form Column -->
                    <div class="col-lg-7">
                        <form id="assessForm" method="post" action="/bimbingan/assess">
                            <?= csrf_field() ?>
                            <input type="hidden" name="internship_id" value="<?= $internship['id'] ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-dark">1. Disiplin (Bobot Nilai 0-100)</label>
                                    <input type="number" name="disiplin" id="score_disiplin" min="0" max="100" class="form-control score-input rounded-3 py-2" required value="<?= $assessment ? esc($assessment['disiplin']) : old('disiplin') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-dark">2. Kehadiran (Bobot Nilai 0-100)</label>
                                    <input type="number" name="kehadiran" id="score_kehadiran" min="0" max="100" class="form-control score-input rounded-3 py-2" required value="<?= $assessment ? esc($assessment['kehadiran']) : old('kehadiran') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-dark">3. Kinerja / Kompetensi (0-100)</label>
                                    <input type="number" name="kinerja" id="score_kinerja" min="0" max="100" class="form-control score-input rounded-3 py-2" required value="<?= $assessment ? esc($assessment['kinerja']) : old('kinerja') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-dark">4. Kelengkapan Logbook (0-100)</label>
                                    <input type="number" name="logbook" id="score_logbook" min="0" max="100" class="form-control score-input rounded-3 py-2" required value="<?= $assessment ? esc($assessment['logbook']) : old('logbook') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">5. Laporan Akhir (0-100)</label>
                                    <input type="number" name="laporan" id="score_laporan" min="0" max="100" class="form-control score-input rounded-3 py-2" required value="<?= $assessment ? esc($assessment['laporan']) : old('laporan') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-dark">Catatan Pembimbing / Evaluasi</label>
                                    <textarea name="catatan" class="form-control rounded-3" rows="4" placeholder="Tuliskan evaluasi atau catatan bimbingan untuk mahasiswa..."><?= $assessment ? esc($assessment['catatan']) : old('catatan') ?></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary px-5 py-2.5 rounded-pill fw-semibold shadow-sm mt-4" id="btnSubmitGrade">
                                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinnerGrade" role="status" aria-hidden="true"></span>
                                Simpan Penilaian
                            </button>
                        </form>
                    </div>

                    <!-- Grade Display Box -->
                    <div class="col-lg-5 mt-4 mt-lg-0">
                        <div class="bg-light p-4 rounded-4 text-center border h-100 d-flex flex-column justify-content-center">
                            <h6 class="text-secondary fw-semibold mb-2">Simulasi Nilai Akhir Mahasiswa</h6>
                            <div class="display-1 fw-bold text-primary mb-3" id="finalGradeDisplay">
                                <?= $assessment ? number_format($assessment['nilai_akhir'], 2) : '0.00' ?>
                            </div>
                            <p class="small text-muted mb-0">Formula Nilai Akhir otomatis dihitung berdasarkan rata-rata dari kelima nilai komponen di sebelah kiri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Automatic grade preview calculator
        const inputs = document.querySelectorAll('.score-input');
        const display = document.getElementById('finalGradeDisplay');
        const assessForm = document.getElementById('assessForm');
        const submitBtn = document.getElementById('btnSubmitGrade');
        const spinner = document.getElementById('btnSpinnerGrade');

        function calculateAverage() {
            let sum = 0;
            let count = 0;
            inputs.forEach(input => {
                const val = parseFloat(input.value);
                if (!isNaN(val) && val >= 0 && val <= 100) {
                    sum += val;
                    count++;
                }
            });
            if (count > 0) {
                const avg = sum / count;
                display.innerText = avg.toFixed(2);
            } else {
                display.innerText = "0.00";
            }
        }

        inputs.forEach(input => {
            input.addEventListener('input', calculateAverage);
        });

        if (assessForm) {
            assessForm.addEventListener('submit', function () {
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
            });
        }
        
        // Active tab matching from URL parameter if any
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'assess') {
            const targetTabEl = document.querySelector('#assess-tab');
            if (targetTabEl) {
                bootstrap.Tab.getOrCreateInstance(targetTabEl).show();
            }
        }
    });
</script>
<?= $this->endSection() ?>
