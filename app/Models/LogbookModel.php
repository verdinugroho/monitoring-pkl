<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbookModel extends Model
{
    protected $table            = 'logbooks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'internship_id', 
        'tanggal', 
        'jam_mulai', 
        'jam_selesai', 
        'aktivitas', 
        'hasil', 
        'kendala', 
        'dokumentasi', 
        'status_review'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'internship_id' => 'required|integer',
        'tanggal'       => 'required|valid_date',
        'jam_mulai'     => 'required',
        'jam_selesai'   => 'required',
        'aktivitas'     => 'required|min_length[5]',
        'hasil'         => 'required|min_length[5]',
        'status_review' => 'required|in_list[Menunggu Review,Direvisi,Disetujui]',
    ];
}
