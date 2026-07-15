<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="fade-in-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="page-title">

                <h2 class="fw-bold mb-0">Data Dosen</h2>
            </div>
            <p class="text-muted mb-0">Kelola pembimbing dan informasi akademik dengan tampilan yang lebih modern.</p>
        </div>
        <a href="/dashboard" class="btn btn-outline-primary">Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Dosen Aktif</h5>
                        <span class="badge bg-primary">8</span>
                    </div>
                    <p class="text-muted mb-0">Pembimbing yang siap mendampingi kegiatan PKL mahasiswa.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Jadwal Konsultasi</h5>
                        <span class="badge bg-success">On Track</span>
                    </div>
                    <p class="text-muted mb-0">Konsultasi rutin dapat dijadwalkan dengan mudah.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="fw-bold mb-0">Monitoring</h5>
                        <span class="badge bg-warning text-dark">Ready</span>
                    </div>
                    <p class="text-muted mb-0">Pantau perkembangan mahasiswa secara sistematis.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Daftar Dosen Pembimbing</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Bidang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dr. Aulia Rahman</td>
                            <td>Teknologi Informasi</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Rina Fadilah, M.Kom</td>
                            <td>Basis Data</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                        </tr>
                        <tr>
                            <td>Bambang Prasetyo, S.T</td>
                            <td>Jaringan</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>