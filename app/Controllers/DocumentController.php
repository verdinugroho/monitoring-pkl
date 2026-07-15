<?php

namespace App\Controllers;

use App\Models\InternshipModel;
use App\Models\DocumentModel;

class DocumentController extends BaseController
{
    protected $documentModel;
    protected $internshipModel;

    public function __construct()
    {
        $this->documentModel = new DocumentModel();
        $this->internshipModel = new InternshipModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('id');

        $internship = $this->internshipModel->where('mahasiswa_id', $userId)->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        // Fetch documents uploaded for this internship
        $documents = $this->documentModel->where('internship_id', $internship['id'])
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('document/index', [
            'documents'  => $documents,
            'internship' => $internship
        ]);
    }

    public function upload()
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $rules = [
            'jenis_file'    => 'required|in_list[foto,mingguan,akhir]',
            'document_file' => 'uploaded[document_file]|max_size[document_file,5120]|ext_in[document_file,jpg,jpeg,png,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('document_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/documents/', $fileName);

            $this->documentModel->save([
                'internship_id' => $internship['id'],
                'nama_file'     => $fileName,
                'jenis_file'    => $this->request->getPost('jenis_file'),
            ]);

            return redirect()->to('/documents')->with('success', 'Berkas berhasil diunggah.');
        }

        return redirect()->to('/documents')->with('error', 'Gagal mengunggah berkas.');
    }

    public function delete($id)
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $document = $this->documentModel->where('id', $id)->where('internship_id', $internship['id'])->first();
        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Berkas tidak ditemukan.');
        }

        // Delete from disk
        $path = FCPATH . 'uploads/documents/' . $document['nama_file'];
        if (file_exists($path)) {
            unlink($path);
        }

        $this->documentModel->delete($id);

        return redirect()->to('/documents')->with('success', 'Berkas berhasil dihapus.');
    }
}
