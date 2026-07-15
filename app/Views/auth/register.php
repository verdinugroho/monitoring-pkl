<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Monitoring PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #2563eb 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .glass-card { border: 0; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 45px rgba(0,0,0,.25); background: rgba(255,255,255,.95); backdrop-filter: blur(8px); }
        .left-panel { background: linear-gradient(135deg, #1d4ed8, #3b82f6); color: white; padding: 32px; }
        .form-control { border-radius: 12px; padding: 12px 14px; }
        .btn-login { border-radius: 999px; padding: 12px 16px; font-weight: 600; }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-10">
            <div class="glass-card">
                <div class="row g-0">
                    <div class="col-md-5 left-panel d-flex flex-column justify-content-center">
                        <h2 class="fw-bold mb-2">Buat Akun Baru</h2>
                        <p class="mb-0 opacity-90">Daftar sebagai mahasiswa untuk memulai akses ke sistem monitoring PKL.</p>
                    </div>
                    <div class="col-md-7 p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Sign Up</h3>
                            <p class="text-muted mb-0">Isi form berikut untuk membuat akun Anda.</p>
                        </div>

                        <?php if(session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger rounded-4">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/auth/processRegister">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required value="<?= old('nama') ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required value="<?= old('email') ?? '' ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Program Studi</label>
                                <select name="prodi" class="form-select" required>
                                    <option value="">-- Pilih Program Studi --</option>
                                    <option value="Informatika" <?= old('prodi') === 'Informatika' ? 'selected' : '' ?>>Informatika</option>
                                    <option value="Sipil" <?= old('prodi') === 'Sipil' ? 'selected' : '' ?>>Sipil</option>
                                    <option value="Industri" <?= old('prodi') === 'Industri' ? 'selected' : '' ?>>Industri</option>
                                    <option value="Mesin" <?= old('prodi') === 'Mesin' ? 'selected' : '' ?>>Mesin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <input type="hidden" name="role" value="mahasiswa">
                            <button class="btn btn-primary w-100 btn-login">Buat Akun</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="/login" class="text-decoration-none">Sudah punya akun? Masuk di sini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
