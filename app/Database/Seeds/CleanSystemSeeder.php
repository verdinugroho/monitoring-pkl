<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CleanSystemSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        // Turn off FK checks and truncate all tables
        $db->query('SET FOREIGN_KEY_CHECKS = 0;');
        $db->table('assessments')->truncate();
        $db->table('documents')->truncate();
        $db->table('comments')->truncate();
        $db->table('logbooks')->truncate();
        $db->table('internships')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS = 1;');

        // Hash password default: password123
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);

        $users = [
            [
                'id'          => 1,
                'nama'        => 'Super Administrator',
                'nim'         => null,
                'nidn'        => null,
                'prodi'       => null,
                'email'       => 'admin@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'admin',
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
                'nama'        => 'Ahmad Fauzi',
                'nim'         => '220401010001',
                'nidn'        => null,
                'prodi'       => 'Teknik Informatika',
                'email'       => 'mahasiswa@gmail.com',
                'password'    => $passwordHash,
                'role'        => 'mahasiswa',
                'status_akun' => 'aktif',
            ],
        ];

        $db->table('users')->insertBatch($users);
    }
}
