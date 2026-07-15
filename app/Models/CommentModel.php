<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table            = 'comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['logbook_id', 'dosen_id', 'komentar', 'created_at'];

    protected $useTimestamps = false; // created_at defaults in DB

    protected $validationRules      = [
        'logbook_id' => 'required|integer',
        'dosen_id'   => 'required|integer',
        'komentar'   => 'required|min_length[2]',
    ];
}
