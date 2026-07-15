<?php

namespace App\Models;

use CodeIgniter\Model;

class InternshipModel extends Model
{
    protected $table            = 'internships';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'mahasiswa_id', 
        'dosen_id', 
        'perusahaan', 
        'bidang', 
        'tanggal_mulai', 
        'tanggal_selesai', 
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'mahasiswa_id'    => 'required|integer',
        'dosen_id'        => 'required|integer',
        'perusahaan'      => 'required|min_length[3]|max_length[150]',
        'bidang'          => 'required|min_length[2]|max_length[100]',
        'tanggal_mulai'   => 'required|valid_date',
        'tanggal_selesai' => 'required|valid_date',
        'status'          => 'required|in_list[aktif,selesai]',
    ];
}
