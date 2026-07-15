<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PklSystemSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Truncate tables first in reverse order of foreign keys
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $db->table('assessments')->truncate();
        $db->table('documents')->truncate();
        $db->table('comments')->truncate();
        $db->table('logbooks')->truncate();
        $db->table('internships')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // 1. Seed Users
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);

        $users = [
            [
                'id'          => 1,
                'nama'        => 'Ahmad Fauzi',
                'nim'         => '220401010001',
                'nidn'        => null,
                'prodi'       => 'Teknik Informatika',
                'email'       => 'mahasiswa@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'mahasiswa',
                'status_akun' => 'aktif',
            ],
            [
                'id'          => 2,
                'nama'        => 'Dr. Budi Utomo, M.T.',
                'nim'         => null,
                'nidn'        => '0420088201',
                'prodi'       => 'Teknik Informatika',
                'email'       => 'dosen@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'dosen',
                'status_akun' => 'aktif',
            ],
            [
                'id'          => 3,
                'nama'        => 'Siti Aminah',
                'nim'         => '220401010002',
                'nidn'        => null,
                'prodi'       => 'Sistem Informasi',
                'email'       => 'mahasiswa2@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'mahasiswa',
                'status_akun' => 'aktif',
            ],
            [
                'id'          => 4,
                'nama'        => 'Prof. Sri Wahyuni, Ph.D.',
                'nim'         => null,
                'nidn'        => '0420088202',
                'prodi'       => 'Sistem Informasi',
                'email'       => 'dosen2@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'dosen',
                'status_akun' => 'aktif',
            ],
            [
                'id'          => 5,
                'nama'        => 'Rian Hidayat',
                'nim'         => '220401010003',
                'nidn'        => null,
                'prodi'       => 'Teknik Informatika',
                'email'       => 'mahasiswa3@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'mahasiswa',
                'status_akun' => 'aktif',
            ],
            [
                'id'          => 6,
                'nama'        => 'Super Administrator',
                'nim'         => null,
                'nidn'         => null,
                'prodi'       => null,
                'email'       => 'admin@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'admin',
                'status_akun' => 'aktif',
            ],
        ];

        $db->table('users')->insertBatch($users);

        // 2. Seed Internships
        $internships = [
            [
                'id'              => 1,
                'mahasiswa_id'    => 1, // Ahmad Fauzi
                'dosen_id'        => 2, // Dr. Budi Utomo
                'perusahaan'      => 'PT Teknologi Nusantara',
                'bidang'          => 'Software Engineering',
                'tanggal_mulai'   => '2026-06-01',
                'tanggal_selesai' => '2026-08-31',
                'status'          => 'aktif',
            ],
            [
                'id'              => 2,
                'mahasiswa_id'    => 3, // Siti Aminah
                'dosen_id'        => 4, // Prof. Sri Wahyuni
                'perusahaan'      => 'PT Data Analytics Indonesia',
                'bidang'          => 'Data Analyst',
                'tanggal_mulai'   => '2026-06-01',
                'tanggal_selesai' => '2026-08-31',
                'status'          => 'selesai',
            ],
            [
                'id'              => 3,
                'mahasiswa_id'    => 5, // Rian Hidayat
                'dosen_id'        => 2, // Dr. Budi Utomo
                'perusahaan'      => 'PT Cyber Security Solusindo',
                'bidang'          => 'Network Security',
                'tanggal_mulai'   => '2026-06-15',
                'tanggal_selesai' => '2026-09-15',
                'status'          => 'aktif',
            ],
        ];

        $db->table('internships')->insertBatch($internships);

        // 3. Seed Logbooks
        $logbooks = [
            [
                'id'            => 1,
                'internship_id' => 1,
                'tanggal'       => '2026-07-01',
                'jam_mulai'     => '08:00:00',
                'jam_selesai'   => '17:00:00',
                'aktivitas'     => 'Mempelajari arsitektur sistem dan menyiapkan development environment.',
                'hasil'         => 'Local environment siap dengan Docker dan CodeIgniter 4.',
                'kendala'       => 'Instalasi Docker di laptop pribadi sempat lambat, diatasi dengan mengubah alokasi RAM.',
                'dokumentasi'   => null,
                'status_review' => 'Disetujui',
            ],
            [
                'id'            => 2,
                'internship_id' => 1,
                'tanggal'       => '2026-07-02',
                'jam_mulai'     => '08:00:00',
                'jam_selesai'   => '17:00:00',
                'aktivitas'     => 'Merancang database dan memigrasikan tabel relasional.',
                'hasil'         => 'Skema database selesai dirancang dan berhasil dimigrasikan.',
                'kendala'       => 'Tidak ada.',
                'dokumentasi'   => null,
                'status_review' => 'Disetujui',
            ],
            [
                'id'            => 3,
                'internship_id' => 1,
                'tanggal'       => '2026-07-03',
                'jam_mulai'     => '08:00:00',
                'jam_selesai'   => '17:00:00',
                'aktivitas'     => 'Membuat mockup antarmuka admin menggunakan Bootstrap 5.',
                'hasil'         => 'Interface halaman dashboard dan logbook selesai dirancang.',
                'kendala'       => 'Kesulitan menyelaraskan palet warna sesuai branding perusahaan, dibantu senior.',
                'dokumentasi'   => null,
                'status_review' => 'Menunggu Review',
            ],
            [
                'id'            => 4,
                'internship_id' => 1,
                'tanggal'       => '2026-07-04',
                'jam_mulai'     => '09:00:00',
                'jam_selesai'   => '16:00:00',
                'aktivitas'     => 'Mengimplementasikan REST API endpoint untuk resource logbook.',
                'hasil'         => 'Endpoint API logbook berfungsi baik dengan format JSON.',
                'kendala'       => 'Error CORS pada saat diuji dengan klien front-end.',
                'dokumentasi'   => null,
                'status_review' => 'Direvisi',
            ],
        ];

        $db->table('logbooks')->insertBatch($logbooks);

        // 4. Seed Comments
        $comments = [
            [
                'id'         => 1,
                'logbook_id' => 1,
                'dosen_id'   => 2, // Dr. Budi Utomo
                'komentar'   => 'Bagus, silakan dilanjutkan dengan perancangan database sesuai instruksi minggu lalu.',
                'created_at' => '2026-07-01 20:15:00',
            ],
            [
                'id'         => 2,
                'logbook_id' => 4,
                'dosen_id'   => 2,
                'komentar'   => 'Pastikan filter keamanan CSRF dan CORS diaktifkan dengan benar sebelum di-deploy.',
                'created_at' => '2026-07-04 18:30:00',
            ],
        ];

        $db->table('comments')->insertBatch($comments);

        // 5. Seed Documents
        $documents = [
            [
                'id'            => 1,
                'internship_id' => 1,
                'nama_file'     => 'laporan_mingguan_1.pdf',
                'jenis_file'    => 'mingguan',
                'created_at'    => '2026-07-05 10:00:00',
            ],
        ];

        $db->table('documents')->insertBatch($documents);

        // 6. Seed Assessments
        $assessments = [
            [
                'id'            => 1,
                'internship_id' => 2, // Siti Aminah (completed)
                'disiplin'      => 90,
                'kehadiran'     => 95,
                'kinerja'       => 88,
                'logbook'       => 92,
                'laporan'       => 90,
                'nilai_akhir'   => 91.00,
                'catatan'       => 'Siti menunjukkan kinerja yang sangat baik selama magang. Logbook terisi dengan lengkap dan laporan akhir disusun dengan sistematis.',
            ]
        ];

        $db->table('assessments')->insertBatch($assessments);
    }
}
