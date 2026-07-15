<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logbook PKL - <?= esc($student['nama']) ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #2563eb;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #666;
            font-size: 12px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #555;
            width: 150px;
        }
        .info-table td.value {
            color: #111;
        }
        .logbook-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .logbook-table th, .logbook-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }
        .logbook-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .logbook-table tr:nth-child(even) {
            background-color: #fcfcfc;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-disetujui {
            background-color: #dcfce7;
            color: #15803d;
        }
        .status-direvisi {
            background-color: #fef9c3;
            color: #a16207;
        }
        .status-menunggu {
            background-color: #f1f5f9;
            color: #475569;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Print instructions helper for browser print fallback -->
    <div class="no-print" style="margin-bottom: 20px; padding: 12px; background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; color: #1e3a8a;">
        <strong>Mode Cetak Browser:</strong> Halaman ini siap dicetak. Tekan <kbd>Ctrl + P</kbd> atau <kbd>Cmd + P</kbd> pada keyboard Anda untuk menyimpannya sebagai file PDF jika server tidak menginstalnya secara otomatis.
        <button onclick="window.print()" style="float: right; padding: 4px 12px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">Cetak Sekarang</button>
        <div style="clear: both;"></div>
    </div>

    <div class="header">
        <h2>Laporan Logbook Praktik Kerja Lapangan (PKL)</h2>
        <p>Sistem Monitoring & Tracking PKL Fakultas</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Mahasiswa</td>
            <td class="value">: <?= esc($student['nama']) ?></td>
            <td class="label">Nama Perusahaan</td>
            <td class="value">: <?= esc($internship['perusahaan']) ?></td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="value">: <?= esc($student['email']) ?></td>
            <td class="label">Bidang PKL</td>
            <td class="value">: <?= esc($internship['bidang']) ?></td>
        </tr>
        <tr>
            <td class="label">Dosen Pembimbing</td>
            <td class="value">: <?= esc($dosenNama) ?></td>
            <td class="label">Periode Magang</td>
            <td class="value">: <?= date('d M Y', strtotime($internship['tanggal_mulai'])) ?> s/d <?= date('d M Y', strtotime($internship['tanggal_selesai'])) ?></td>
        </tr>
    </table>

    <table class="logbook-table">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 70px;">Jam Kerja</th>
                <th>Aktivitas / Kegiatan</th>
                <th>Hasil Pekerjaan</th>
                <th>Kendala</th>
                <th style="width: 80px; text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logbooks)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #777;">Tidak ada data logbook kegiatan yang diisi.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($logbooks as $logbook): ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td style="white-space: nowrap;"><?= date('d M Y', strtotime($logbook['tanggal'])) ?></td>
                        <td style="white-space: nowrap;"><?= date('H:i', strtotime($logbook['jam_mulai'])) ?> - <?= date('H:i', strtotime($logbook['jam_selesai'])) ?></td>
                        <td><?= nl2br(esc($logbook['aktivitas'])) ?></td>
                        <td><?= nl2br(esc($logbook['hasil'])) ?></td>
                        <td><?= $logbook['kendala'] ? nl2br(esc($logbook['kendala'])) : '-' ?></td>
                        <td style="text-align: center;">
                            <?php if ($logbook['status_review'] === 'Disetujui'): ?>
                                <span class="status-badge status-disetujui">Disetujui</span>
                            <?php elseif ($logbook['status_review'] === 'Direvisi'): ?>
                                <span class="status-badge status-direvisi">Direvisi</span>
                            <?php else: ?>
                                <span class="status-badge status-menunggu">Menunggu</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis melalui Sistem Monitoring PKL Mahasiswa pada tanggal <?= date('d F Y H:i') ?>
    </div>

</body>
</html>
