<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">

                <h2 class="fw-bold mb-0">Laporan PKL</h2>
            </div>
            <p class="text-muted mb-0">Berikut ringkasan laporan yang telah dikumpulkan oleh mahasiswa.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-primary">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Laporan Mingguan 1</span>
                    <span class="badge bg-success">Terkirim</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Laporan Mingguan 2</span>
                    <span class="badge bg-warning text-dark">Review</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>Laporan Akhir</span>
                    <span class="badge bg-primary">Menunggu</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Catatan Supervisor</h5>
            <p class="text-muted mb-0">Mahasiswa menunjukkan perkembangan yang baik, terutama dalam kedisiplinan dan tanggung jawab kerja.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>