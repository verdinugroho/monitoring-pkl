<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DosenSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('dosen')->insert([
            'user_id'    => 2,
            'nidn'       => '1234567890',
            'nama'       => 'Dosen Pembimbing',
            'telepon'    => '081111111111',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}