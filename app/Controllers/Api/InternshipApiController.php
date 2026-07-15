<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\InternshipModel;

class InternshipApiController extends ResourceController
{
    protected $modelName = 'App\Models\InternshipModel';
    protected $format    = 'json';

    public function index()
    {
        $db = \Config\Database::connect();
        $internships = $db->table('internships')
            ->select('internships.*, s.nama as mahasiswa_nama, s.email as mahasiswa_email, d.nama as dosen_nama, d.email as dosen_email')
            ->join('users s', 's.id = internships.mahasiswa_id')
            ->join('users d', 'd.id = internships.dosen_id')
            ->get()
            ->getResultArray();

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve internships',
            'data'    => $internships
        ], 200);
    }

    public function show($id = null)
    {
        $db = \Config\Database::connect();
        $internship = $db->table('internships')
            ->select('internships.*, s.nama as mahasiswa_nama, s.email as mahasiswa_email, d.nama as dosen_nama, d.email as dosen_email')
            ->join('users s', 's.id = internships.mahasiswa_id')
            ->join('users d', 'd.id = internships.dosen_id')
            ->where('internships.id', $id)
            ->get()
            ->getRowArray();

        if (!$internship) {
            return $this->failNotFound('Internship data not found.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve internship details',
            'data'    => $internship
        ], 200);
    }
}
