<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Administrator',
                'email'      => 'admin@monitoringpkl.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Dosen Pembimbing',
                'email'      => 'dosen@monitoringpkl.com',
                'password'   => password_hash('dosen123', PASSWORD_DEFAULT),
                'role'       => 'dosen',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Mahasiswa Demo',
                'email'      => 'mahasiswa@monitoringpkl.com',
                'password'   => password_hash('mahasiswa123', PASSWORD_DEFAULT),
                'role'       => 'mahasiswa',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('users')->insertBatch($data);
    }
}