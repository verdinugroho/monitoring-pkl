<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">

                <h2 class="fw-bold mb-0">Data Perusahaan</h2>
            </div>
            <p class="text-muted mb-0">Tempat PKL yang tersedia dan dapat dikelola dengan cepat.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-primary">Kembali</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light">
                        <h5 class="fw-bold">PT Maju Bersama</h5>
                        <p class="text-muted mb-0">Bidang teknologi informasi dan pengembangan sistem.</p>
                        <small class="text-muted">Alamat: Bandung | Kuota: 4 mahasiswa</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-4 bg-light">
                        <h5 class="fw-bold">CV Digital Solusi</h5>
                        <p class="text-muted mb-0">Fokus pada desain, branding, dan layanan digital.</p>
                        <small class="text-muted">Alamat: Jakarta | Kuota: 3 mahasiswa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Rekap Mitra</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 d-flex justify-content-between">
                    <span>PT Maju Bersama</span>
                    <span class="badge bg-primary">Aktif</span>
                </li>
                <li class="list-group-item px-0 d-flex justify-content-between">
                    <span>CV Digital Solusi</span>
                    <span class="badge bg-success">Tersedia</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<?= $this->endSection() ?>