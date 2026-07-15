<?php

namespace App\Controllers;

use App\Models\InternshipModel;
use App\Models\LogbookModel;
use App\Models\CommentModel;

class LogbookController extends BaseController
{
    protected $logbookModel;
    protected $internshipModel;
    protected $commentModel;

    public function __construct()
    {
        $this->logbookModel = new LogbookModel();
        $this->internshipModel = new InternshipModel();
        $this->commentModel = new CommentModel();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('id');

        // Get internship
        $internship = $this->internshipModel->where('mahasiswa_id', $userId)->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        // Search parameter
        $search = $this->request->getGet('search') ?? '';

        // Query logbooks with pagination
        $query = $this->logbookModel->where('internship_id', $internship['id']);
        if (!empty($search)) {
            $query = $query->groupStart()
                ->like('aktivitas', $search)
                ->orLike('hasil', $search)
                ->orLike('kendala', $search)
                ->groupEnd();
        }

        // Fetch paginated logbooks
        $logbooks = $query->orderBy('tanggal', 'DESC')->paginate(10, 'logbooks');
        $pager = $this->logbookModel->pager;

        // Fetch comments for all these logbooks to display badges/counts
        $logbookIds = array_column($logbooks, 'id');
        $commentsCount = [];
        if (!empty($logbookIds)) {
            $commentsCountRaw = $this->commentModel->select('logbook_id, COUNT(id) as count')
                ->whereIn('logbook_id', $logbookIds)
                ->groupBy('logbook_id')
                ->findAll();
            foreach ($commentsCountRaw as $c) {
                $commentsCount[$c['logbook_id']] = $c['count'];
            }
        }

        return view('logbook/index', [
            'logbooks'      => $logbooks,
            'pager'         => $pager,
            'search'        => $search,
            'commentsCount' => $commentsCount,
            'internship'    => $internship
        ]);
    }

    public function create()
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        return view('logbook/create', [
            'internship' => $internship
        ]);
    }

    public function store()
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $rules = [
            'tanggal'     => 'required|valid_date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'aktivitas'   => 'required|min_length[5]',
            'hasil'       => 'required|min_length[5]',
            'dokumentasi' => 'max_size[dokumentasi,5120]|ext_in[dokumentasi,jpg,jpeg,png,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // File upload handling
        $dokumentasiName = null;
        $file = $this->request->getFile('dokumentasi');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dokumentasiName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/logbooks/', $dokumentasiName);
        }

        $this->logbookModel->save([
            'internship_id' => $internship['id'],
            'tanggal'       => $this->request->getPost('tanggal'),
            'jam_mulai'     => $this->request->getPost('jam_mulai'),
            'jam_selesai'   => $this->request->getPost('jam_selesai'),
            'aktivitas'     => trim($this->request->getPost('aktivitas')),
            'hasil'         => trim($this->request->getPost('hasil')),
            'kendala'       => trim($this->request->getPost('kendala') ?? ''),
            'dokumentasi'   => $dokumentasiName,
            'status_review' => 'Menunggu Review',
        ]);

        return redirect()->to('/logbook')->with('success', 'Logbook harian berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $logbook = $this->logbookModel->where('id', $id)->where('internship_id', $internship['id'])->first();
        if (!$logbook) {
            return redirect()->to('/logbook')->with('error', 'Logbook tidak ditemukan.');
        }

        // Authorization check: only editable before review
        if ($logbook['status_review'] !== 'Menunggu Review') {
            return redirect()->to('/logbook')->with('error', 'Logbook yang sudah direview dosen tidak dapat diubah.');
        }

        // Fetch comments for review history
        $db = \Config\Database::connect();
        $comments = $db->table('comments')
            ->select('comments.*, users.nama as dosen_nama')
            ->join('users', 'users.id = comments.dosen_id')
            ->where('comments.logbook_id', $logbook['id'])
            ->orderBy('comments.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return view('logbook/edit', [
            'logbook'    => $logbook,
            'comments'   => $comments,
            'internship' => $internship
        ]);
    }

    public function update($id)
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $logbook = $this->logbookModel->where('id', $id)->where('internship_id', $internship['id'])->first();
        if (!$logbook) {
            return redirect()->to('/logbook')->with('error', 'Logbook tidak ditemukan.');
        }

        if ($logbook['status_review'] !== 'Menunggu Review') {
            return redirect()->to('/logbook')->with('error', 'Logbook yang sudah direview dosen tidak dapat diubah.');
        }

        $rules = [
            'tanggal'     => 'required|valid_date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required',
            'aktivitas'   => 'required|min_length[5]',
            'hasil'       => 'required|min_length[5]',
            'dokumentasi' => 'max_size[dokumentasi,5120]|ext_in[dokumentasi,jpg,jpeg,png,pdf]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'tanggal'     => $this->request->getPost('tanggal'),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai'   => $this->request->getPost('jam_selesai'),
            'aktivitas'     => trim($this->request->getPost('aktivitas')),
            'hasil'         => trim($this->request->getPost('hasil')),
            'kendala'       => trim($this->request->getPost('kendala') ?? ''),
        ];

        // File upload handling
        $file = $this->request->getFile('dokumentasi');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file
            if ($logbook['dokumentasi']) {
                $oldPath = FCPATH . 'uploads/logbooks/' . $logbook['dokumentasi'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $dokumentasiName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/logbooks/', $dokumentasiName);
            $data['dokumentasi'] = $dokumentasiName;
        }

        $this->logbookModel->update($id, $data);

        return redirect()->to('/logbook')->with('success', 'Logbook harian berhasil diperbarui.');
    }

    public function delete($id)
    {
        $session = session();
        $internship = $this->internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if (!$internship) {
            return redirect()->to('/setup-pkl');
        }

        $logbook = $this->logbookModel->where('id', $id)->where('internship_id', $internship['id'])->first();
        if (!$logbook) {
            return redirect()->to('/logbook')->with('error', 'Logbook tidak ditemukan.');
        }

        if ($logbook['status_review'] !== 'Menunggu Review') {
            return redirect()->to('/logbook')->with('error', 'Logbook yang sudah direview dosen tidak dapat dihapus.');
        }

        // Delete associated documentation file
        if ($logbook['dokumentasi']) {
            $path = FCPATH . 'uploads/logbooks/' . $logbook['dokumentasi'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->logbookModel->delete($id);

        return redirect()->to('/logbook')->with('success', 'Logbook harian berhasil dihapus.');
    }
}
