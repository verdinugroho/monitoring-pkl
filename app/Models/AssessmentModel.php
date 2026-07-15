<?php

namespace App\Models;

use CodeIgniter\Model;

class AssessmentModel extends Model
{
    protected $table            = 'assessments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'internship_id', 
        'disiplin', 
        'kehadiran', 
        'kinerja', 
        'logbook', 
        'laporan', 
        'nilai_akhir', 
        'catatan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules      = [
        'internship_id' => 'required|integer|is_unique[assessments.internship_id,id,{id}]',
        'disiplin'      => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'kehadiran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'kinerja'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'logbook'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'laporan'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        'nilai_akhir'   => 'required|numeric',
    ];
}
