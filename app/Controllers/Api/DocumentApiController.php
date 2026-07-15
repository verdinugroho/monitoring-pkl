<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DocumentModel;
use App\Models\InternshipModel;

class DocumentApiController extends ResourceController
{
    protected $modelName = 'App\Models\DocumentModel';
    protected $format    = 'json';

    public function index()
    {
        $internshipId = $this->request->getGet('internship_id');

        if ($internshipId) {
            $documents = $this->model->where('internship_id', $internshipId)->orderBy('created_at', 'DESC')->findAll();
        } else {
            $documents = $this->model->orderBy('created_at', 'DESC')->findAll();
        }

        return $this->respond([
            'status'  => 200,
            'message' => 'Success retrieve documents',
            'data'    => $documents
        ], 200);
    }

    public function create()
    {
        $rules = [
            'internship_id' => 'required|integer',
            'jenis_file'    => 'required|in_list[foto,mingguan,akhir]',
            'document_file' => 'uploaded[document_file]|max_size[document_file,5120]|ext_in[document_file,jpg,jpeg,png,pdf]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $internshipId = $this->request->getVar('internship_id');

        // Check internship exists
        $internshipModel = new InternshipModel();
        $internship = $internshipModel->find($internshipId);
        if (!$internship) {
            return $this->failNotFound('Internship not found.');
        }

        $file = $this->request->getFile('document_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/documents/', $fileName);

            $data = [
                'internship_id' => $internshipId,
                'nama_file'     => $fileName,
                'jenis_file'    => $this->request->getVar('jenis_file'),
            ];

            $this->model->insert($data);
            $insertId = $this->model->getInsertID();

            return $this->respondCreated([
                'status'  => 201,
                'message' => 'Document successfully uploaded.',
                'data'    => array_merge(['id' => $insertId], $data)
            ]);
        }

        return $this->fail('Failed to upload file.', 500);
    }

    public function delete($id = null)
    {
        $document = $this->model->find($id);
        if (!$document) {
            return $this->failNotFound('Document not found.');
        }

        // Delete from disk
        $path = FCPATH . 'uploads/documents/' . $document['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'status'  => 200,
            'message' => 'Document successfully deleted.',
            'data'    => ['id' => $id]
        ]);
    }
}
