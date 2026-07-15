<?php

namespace Tests\Unit;

use App\Controllers\Api\Mahasiswa;
use CodeIgniter\Test\CIUnitTestCase;
use Psr\Log\NullLogger;

class ApiMahasiswaTest extends CIUnitTestCase
{
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = \Config\Database::connect('tests');
        $this->db->query('DROP TABLE IF EXISTS db_mahasiswa');
        $this->db->query(
            'CREATE TABLE db_mahasiswa (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                nim VARCHAR(20) NOT NULL UNIQUE,
                nama VARCHAR(100) NOT NULL,
                prodi VARCHAR(100) NOT NULL,
                angkatan SMALLINT NOT NULL,
                telepon VARCHAR(20) NOT NULL,
                alamat TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $this->db->table('db_mahasiswa')->insert([
            'user_id' => 1,
            'nim' => '20240001',
            'nama' => 'Budi Santoso',
            'prodi' => 'Teknik Informatika',
            'angkatan' => 2024,
            'telepon' => '081234567890',
            'alamat' => 'Bandung',
        ]);
    }

    public function testApiMahasiswaIndexReturnsJsonResponse(): void
    {
        $controller = new Mahasiswa();
        $request = service('request');
        $response = service('response');
        $logger = new NullLogger();
        $controller->initController($request, $response, $logger);
        $response = $controller->index();

        $this->assertNotNull($response);
        $this->assertStringContainsString('application/json', $response->getHeaderLine('Content-Type'));

        $body = json_decode($response->getBody(), true);
        $this->assertIsArray($body);
        $this->assertNotEmpty($body);
    }
}
