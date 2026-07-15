<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table            = 'documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['internship_id', 'nama_file', 'jenis_file', 'created_at'];

    protected $useTimestamps = false; // DB default for created_at

    protected $validationRules      = [
        'internship_id' => 'required|integer',
        'nama_file'     => 'required|min_length[3]',
        'jenis_file'    => 'required|in_list[foto,mingguan,akhir]',
    ];
}
