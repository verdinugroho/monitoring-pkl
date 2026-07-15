<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('perusahaan')->insert([
            'nama'        => 'PT Teknologi Indonesia',
            'alamat'      => 'Jl. Malioboro No.1, Yogyakarta',
            'telepon'     => '0274123456',
            'email'       => 'info@ptteknologi.co.id',
            'pembimbing'  => 'Budi Santoso',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}