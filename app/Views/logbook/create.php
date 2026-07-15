<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none text-primary">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="/logbook" class="text-decoration-none text-primary">Logbook</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Logbook</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 bg-white shadow-sm" style="border-radius: 18px;">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="p-3 bg-primary-subtle text-primary rounded-4"><i class="bi bi-journal-plus fs-4"></i></div>
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Tambah Kegiatan Harian</h4>
                            <p class="text-muted small mb-0">Isi laporan aktivitas magang Anda di bawah ini dengan lengkap.</p>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form id="logbookForm" method="post" action="/logbook/store" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <?= csrf_field() ?>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal" class="form-control rounded-3 py-2" required value="<?= old('tanggal') ?? date('Y-m-d') ?>">
                                <div class="invalid-feedback">Pilih tanggal kegiatan.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control rounded-3 py-2" required value="<?= old('jam_mulai') ?? '08:00' ?>">
                                <div class="invalid-feedback">Isi jam mulai.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="form-control rounded-3 py-2" required value="<?= old('jam_selesai') ?? '17:00' ?>">
                                <div class="invalid-feedback">Isi jam selesai.</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Deskripsi Aktivitas</label>
                            <textarea name="aktivitas" class="form-control rounded-3" rows="4" placeholder="Jelaskan secara detail apa yang Anda pelajari dan kerjakan hari ini..." required><?= old('aktivitas') ?></textarea>
                            <div class="invalid-feedback">Tulis deskripsi aktivitas minimal 5 karakter.</div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Hasil Pekerjaan</label>
                            <textarea name="hasil" class="form-control rounded-3" rows="3" placeholder="Sebutkan hasil konkret dari pekerjaan Anda (misalnya: script coding, database design, dll)..." required><?= old('hasil') ?></textarea>
                            <div class="invalid-feedback">Tulis hasil pekerjaan minimal 5 karakter.</div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Kendala & Hambatan <span class="text-muted small fw-normal">(Opsional)</span></label>
                            <textarea name="kendala" class="form-control rounded-3" rows="2" placeholder="Tulis kendala teknis atau operasional yang Anda hadapi (bila ada)..."><?= old('kendala') ?></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold text-dark">Dokumentasi Kegiatan <span class="text-muted small fw-normal">(Opsional)</span></label>
                            <input type="file" name="dokumentasi" class="form-control rounded-3" accept=".jpg,.jpeg,.png,.pdf">
                            <div class="form-text text-muted">Format file yang diperbolehkan: <strong>JPG, JPEG, PNG, PDF</strong>. Ukuran file maksimal <strong>5 MB</strong>.</div>
                        </div>

                        <div class="mt-5 d-flex gap-3 justify-content-end">
                            <a href="/logbook" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold shadow-sm" id="btnSubmit">
                                <span class="spinner-border spinner-border-sm d-none me-2" id="btnSpinner" role="status" aria-hidden="true"></span>
                                Simpan Logbook
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bootstrap form validation
        const form = document.getElementById('logbookForm');
        const submitBtn = document.getElementById('btnSubmit');
        const spinner = document.getElementById('btnSpinner');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Show loader spinner and disable button to prevent double submits
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
<?= $this->endSection() ?>
