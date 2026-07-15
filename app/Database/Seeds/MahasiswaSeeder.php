<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('mahasiswa')->insert([
            'user_id'    => 3,
            'nim'        => '231011001',
            'nama'       => 'Mahasiswa Demo',
            'prodi'      => 'Informatika',
            'angkatan'   => 2023,
            'telepon'    => '081234567890',
            'alamat'     => 'Yogyakarta',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}