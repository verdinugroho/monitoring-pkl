<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Penilaian</h2>
            <p class="text-muted mb-0">Evaluasi hasil kerja mahasiswa selama pelaksanaan PKL.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-primary">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-light">
                        <h6 class="fw-bold">Kedisiplinan</h6>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-light">
                        <h6 class="fw-bold">Kerja Sama</h6>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-4 bg-light">
                        <h6 class="fw-bold">Kompetensi</h6>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-warning" style="width: 88%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Rekomendasi</h5>
            <p class="text-muted mb-0">Mahasiswa sangat potensial untuk dikembangkan lebih lanjut pada bidang pengembangan aplikasi dan kerja tim.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>