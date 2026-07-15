<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRoleAndUserFields extends Migration
{
    public function up()
    {
        // 1. Modify role ENUM to include 'admin'
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa','dosen','admin') NOT NULL DEFAULT 'mahasiswa'");

        // 2. Add new columns
        $this->forge->addColumn('users', [
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'nama',
            ],
            'nidn' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'nim',
            ],
            'prodi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'nidn',
            ],
            'status_akun' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'nonaktif'],
                'default'    => 'aktif',
                'after'      => 'role',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['nim', 'nidn', 'prodi', 'status_akun']);
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa','dosen') NOT NULL DEFAULT 'mahasiswa'");
    }
}
