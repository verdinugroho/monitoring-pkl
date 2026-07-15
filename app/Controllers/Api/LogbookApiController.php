<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\LogbookModel;

class LogbookApiController extends ResourceController
{
    protected $modelName = 'App\Models\LogbookModel';
    protected $format    = 'json';

    public static function parseInputData($request): array
    {
        if (is_object($request) && method_exists($request, 'getJSON')) {
            $json = $request->getJSON(true);
            if (is_array($json) && !empty($json)) {
                return $json;
            }
        }

        if (is_object($request) && method_exists($request, 'getRawInput')) {
            $rawInput = $request->getRawInput();
            if (is_string($rawInput) && trim($rawInput) !== '') {
                $decoded = json_decode($rawInput, true);
                if (is_array($decoded) && !empty($decoded)) {
                    return $decoded;
                }
            }
        }

        if (is_object($request) && method_exists($request, 'getVar')) {
            $vars = $request->getVar();
            if (is_array($vars)) {
                return $vars;
            }
        }

        return [];
    }

    public function index()
    {
        $internshipId = $this->request->getGet('internship_id');

        if ($internshipId) {
            $logbooks = $this->model->where('internship_id', $internshipId)->orderBy('tanggal', 'DESC')->findAll();
        } else {
            $logbooks = $this->model->orderBy('tanggal', 'DESC')->findAll();
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve logbooks',
            'data'    => $logbooks
        ], 200);
    }

    public function show($id = null)
    {
        $logbook = $this->model->find($id);

        if (!$logbook) {
            return $this->failNotFound('Logbook not found.');
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve logbook details',
            'data'    => $logbook
        ], 200);
    }

    public function create()
    {
        $rules = [
            'internship_id' => 'required|integer',
            'tanggal'       => 'required|valid_date',
            'jam_mulai'     => 'required',
            'jam_selesai'   => 'required',
            'aktivitas'     => 'required|min_length[5]',
            'hasil'         => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'internship_id' => $this->request->getVar('internship_id'),
            'tanggal'       => $this->request->getVar('tanggal'),
            'jam_mulai'     => $this->request->getVar('jam_mulai'),
            'jam_selesai'   => $this->request->getVar('jam_selesai'),
            'aktivitas'     => trim($this->request->getVar('aktivitas')),
            'hasil'         => trim($this->request->getVar('hasil')),
            'kendala'       => trim($this->request->getVar('kendala') ?? ''),
            'status_review' => $this->request->getVar('status_review') ?? 'Menunggu Review',
        ];

        $this->model->insert($data);
        $insertId = $this->model->getInsertID();

        return $this->respondCreated([
            'status'  => 201,
            'message' => 'Logbook successfully created.',
            'data'    => array_merge(['id' => $insertId], $data)
        ]);
    }

    public function update($id = null)
    {
        $logbook = $this->model->find($id);
        if (!$logbook) {
            return $this->failNotFound('Logbook not found.');
        }

        $input = self::parseInputData($this->request);

        // Validate rules dynamically based on input fields present
        $rules = [
            'tanggal'     => 'permit_empty|valid_date',
            'jam_mulai'   => 'permit_empty',
            'jam_selesai' => 'permit_empty',
            'aktivitas'   => 'permit_empty|min_length[5]',
            'hasil'       => 'permit_empty|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [];
        $allowedFields = ['tanggal', 'jam_mulai', 'jam_selesai', 'aktivitas', 'hasil', 'kendala', 'status_review'];
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $data[$field] = is_string($input[$field]) ? trim($input[$field]) : $input[$field];
            }
        }

        if (empty($data)) {
            return $this->fail('No fields to update.', 400);
        }

        $this->model->update($id, $data);

        return $this->respond([
            'status'  => 200,
            'message' => 'Logbook successfully updated.',
            'data'    => array_merge($logbook, $data)
        ], 200);
    }

    public function delete($id = null)
    {
        $logbook = $this->model->find($id);
        if (!$logbook) {
            return $this->failNotFound('Logbook not found.');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Logbook successfully deleted.',
            'data'    => ['id' => $id]
        ]);
    }
}
