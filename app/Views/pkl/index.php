<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">

                <h2 class="fw-bold mb-0">Program PKL</h2>
            </div>
            <p class="text-muted mb-0">Pantau alur kegiatan praktik kerja lapangan mahasiswa.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-primary">Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-bold">Status Saat Ini</h5>
                    <p class="text-muted">Mahasiswa sedang menjalani tahap observasi di perusahaan mitra.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-bold">Target Penyelesaian</h5>
                    <p class="text-muted">Target pengumpulan laporan dan evaluasi akhir berada dalam jadwal.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Timeline PKL</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0">1. Penerimaan mahasiswa di mitra perusahaan</li>
                <li class="list-group-item px-0">2. Observasi dan pengenalan lingkungan kerja</li>
                <li class="list-group-item px-0">3. Penyelesaian tugas dan pembuatan laporan</li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>