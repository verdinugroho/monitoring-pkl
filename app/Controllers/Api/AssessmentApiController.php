<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AssessmentModel;
use App\Models\InternshipModel;

class AssessmentApiController extends ResourceController
{
    protected $modelName = 'App\Models\AssessmentModel';
    protected $format    = 'json';

    public function index()
    {
        $assessments = $this->model->findAll();

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve assessments',
            'data'    => $assessments
        ], 200);
    }

    public function create()
    {
        $rules = [
            'internship_id' => 'required|integer',
            'disiplin'      => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kehadiran'     => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kinerja'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'logbook'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'laporan'       => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $internshipId = $this->request->getVar('internship_id');

        // Check if internship exists
        $internshipModel = new InternshipModel();
        $internship = $internshipModel->find($internshipId);
        if (!$internship) {
            return $this->failNotFound('Internship not found.');
        }

        // Check if assessment already exists
        $existing = $this->model->where('internship_id', $internshipId)->first();
        if ($existing) {
            return $this->fail('Assessment already exists for this internship. Use update instead.', 409);
        }

        $disiplin  = (int)$this->request->getVar('disiplin');
        $kehadiran = (int)$this->request->getVar('kehadiran');
        $kinerja   = (int)$this->request->getVar('kinerja');
        $logbook   = (int)$this->request->getVar('logbook');
        $laporan   = (int)$this->request->getVar('laporan');
        
        // Auto-calculate average
        $nilaiAkhir = ($disiplin + $kehadiran + $kinerja + $logbook + $laporan) / 5;

        $data = [
            'internship_id' => $internshipId,
            'disiplin'      => $disiplin,
            'kehadiran'     => $kehadiran,
            'kinerja'       => $kinerja,
            'logbook'       => $logbook,
            'laporan'       => $laporan,
            'nilai_akhir'   => $nilaiAkhir,
            'catatan'       => trim($this->request->getVar('catatan') ?? ''),
        ];

        $this->model->insert($data);
        $insertId = $this->model->getInsertID();

        // Automatically set internship status to "selesai"
        $internshipModel->update($internshipId, [
            'status' => 'selesai'
        ]);

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Assessment successfully created. Internship status set to selesai.',
            'data'    => array_merge(['id' => $insertId], $data)
        ]);
    }
}
