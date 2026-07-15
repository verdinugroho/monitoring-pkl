<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring PKL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f1f4f8;
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .glass-card {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 45px rgba(0,0,0,.25);
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(8px);
        }
        .left-panel {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 32px;
        }
        .left-panel h2 {
            font-size: 1.7rem;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }
        .btn-login {
            border-radius: 999px;
            padding: 12px 16px;
            font-weight: 600;
        }
        .brand-badge {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.18);
            font-size: 1.4rem;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-lg-10">
            <div class="glass-card">
                <div class="row g-0">
                    <div class="col-md-5 left-panel d-flex flex-column justify-content-center">
                        <!-- panel kiri kosong -->
                    </div>
                    <div class="col-md-7 p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Silakan Masuk</h3>
                            <p class="text-muted mb-0">Masukkan kredensial akun Anda untuk melanjutkan.</p>
                        </div>

                        <?php if(session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger rounded-4">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('success')): ?>
                            <div class="alert alert-success rounded-4">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/auth/processLogin">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="contoh@email.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                            </div>

                            <button class="btn btn-primary w-100 btn-login">Masuk ke Dashboard</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="/register" class="text-decoration-none">Belum punya akun? Daftar sekarang</a>
                        </div>
                        <div class="mt-2 text-center small text-muted">
                            Gunakan akun yang sudah terdaftar untuk masuk ke sistem monitoring PKL.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>