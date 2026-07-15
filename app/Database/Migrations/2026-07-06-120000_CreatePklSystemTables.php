<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePklSystemTables extends Migration
{
    public function up()
    {
        // 1. Table: users
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['mahasiswa', 'dosen'],
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');

        // 2. Table: internships
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mahasiswa_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'dosen_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'perusahaan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'bidang' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tanggal_mulai' => [
                'type' => 'DATE',
            ],
            'tanggal_selesai' => [
                'type' => 'DATE',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['aktif', 'selesai'],
                'default'    => 'aktif',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('mahasiswa_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dosen_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('internships');

        // 3. Table: logbooks
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'internship_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'jam_mulai' => [
                'type' => 'TIME',
            ],
            'jam_selesai' => [
                'type' => 'TIME',
            ],
            'aktivitas' => [
                'type' => 'TEXT',
            ],
            'hasil' => [
                'type' => 'TEXT',
            ],
            'kendala' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dokumentasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'status_review' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu Review', 'Direvisi', 'Disetujui'],
                'default'    => 'Menunggu Review',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('internship_id', 'internships', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('logbooks');

        // 4. Table: comments
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'logbook_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'dosen_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'komentar' => [
                'type' => 'TEXT',
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('logbook_id', 'logbooks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dosen_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('comments');

        // 5. Table: documents
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'internship_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_file' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_file' => [
                'type'       => 'ENUM',
                'constraint' => ['foto', 'mingguan', 'akhir'],
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('internship_id', 'internships', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('documents');

        // 6. Table: assessments
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'internship_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => true,
            ],
            'disiplin' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kehadiran' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'kinerja' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'logbook' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'laporan' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'nilai_akhir' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at DATETIME DEFAULT CURRENT_TIMESTAMP',
            'updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('internship_id', 'internships', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('assessments');
    }

    public function down()
    {
        $this->forge->dropTable('assessments', true);
        $this->forge->dropTable('documents', true);
        $this->forge->dropTable('comments', true);
        $this->forge->dropTable('logbooks', true);
        $this->forge->dropTable('internships', true);
        $this->forge->dropTable('users', true);
    }
}
