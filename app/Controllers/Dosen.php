<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InternshipModel;
use App\Models\LogbookModel;
use App\Models\CommentModel;
use App\Models\DocumentModel;
use App\Models\AssessmentModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Dosen extends BaseController
{
    protected $userModel;
    protected $internshipModel;
    protected $logbookModel;
    protected $commentModel;
    protected $documentModel;
    protected $assessmentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->internshipModel = new InternshipModel();
        $this->logbookModel = new LogbookModel();
        $this->commentModel = new CommentModel();
        $this->documentModel = new DocumentModel();
        $this->assessmentModel = new AssessmentModel();
    }

    public function index()
    {
        return redirect()->to('/bimbingan');
    }

    public function bimbingan()
    {
        $session = session();
        $dosenId = $session->get('id');

        $search = $this->request->getGet('search') ?? '';

        // Fetch students under this supervisor with user details
        $db = \Config\Database::connect();
        $builder = $db->table('internships')
            ->select('internships.*, users.nama as mahasiswa_nama, users.email as mahasiswa_email')
            ->join('users', 'users.id = internships.mahasiswa_id')
            ->where('internships.dosen_id', $dosenId);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.nama', $search)
                ->orLike('internships.perusahaan', $search)
                ->orLike('internships.bidang', $search)
                ->groupEnd();
        }

        $bimbingan = $builder->orderBy('users.nama', 'ASC')->get()->getResultArray();

        // Calculate progress for each student
        foreach ($bimbingan as &$student) {
            $mulai = new \DateTime($student['tanggal_mulai']);
            $selesai = new \DateTime($student['tanggal_selesai']);
            $interval = $mulai->diff($selesai);
            $totalHari = max(1, $interval->days + 1);

            $jumlahLogbook = $this->logbookModel->where('internship_id', $student['id'])->countAllResults();
            $student['jumlah_logbook'] = $jumlahLogbook;
            $student['total_hari'] = $totalHari;
            $student['progress'] = min(100, round(($jumlahLogbook / $totalHari) * 100, 1));
        }

        return view('dosen/bimbingan', [
            'bimbingan' => $bimbingan,
            'search'    => $search
        ]);
    }

    public function detail($id)
    {
        $session = session();
        $dosenId = $session->get('id');

        // Fetch internship
        $internship = $this->internshipModel->find($id);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Mahasiswa bimbingan tidak ditemukan.');
        }

        // Student details
        $student = $this->userModel->find($internship['mahasiswa_id']);

        // Logbooks
        $logbooks = $this->logbookModel->where('internship_id', $id)
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        // Uploaded documents
        $documents = $this->documentModel->where('internship_id', $id)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Assessment
        $assessment = $this->assessmentModel->where('internship_id', $id)->first();

        // Calculate progress
        $mulai = new \DateTime($internship['tanggal_mulai']);
        $selesai = new \DateTime($internship['tanggal_selesai']);
        $interval = $mulai->diff($selesai);
        $totalHari = max(1, $interval->days + 1);
        $jumlahLogbook = count($logbooks);
        $progress = min(100, round(($jumlahLogbook / $totalHari) * 100, 1));

        return view('dosen/detail', [
            'internship'    => $internship,
            'student'       => $student,
            'logbooks'      => $logbooks,
            'documents'     => $documents,
            'assessment'    => $assessment,
            'totalHari'     => $totalHari,
            'jumlahLogbook' => $jumlahLogbook,
            'progress'      => $progress
        ]);
    }

    public function logbookDetails($id)
    {
        $session = session();
        $dosenId = $session->get('id');

        $logbook = $this->logbookModel->find($id);
        if (!$logbook) {
            return redirect()->to('/bimbingan')->with('error', 'Logbook tidak ditemukan.');
        }

        $internship = $this->internshipModel->find($logbook['internship_id']);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Akses tidak sah.');
        }

        $student = $this->userModel->find($internship['mahasiswa_id']);

        // Comments history
        $db = \Config\Database::connect();
        $comments = $db->table('comments')
            ->select('comments.*, users.nama as dosen_nama')
            ->join('users', 'users.id = comments.dosen_id')
            ->where('comments.logbook_id', $id)
            ->orderBy('comments.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return view('dosen/logbook_detail', [
            'logbook'    => $logbook,
            'internship' => $internship,
            'student'    => $student,
            'comments'   => $comments
        ]);
    }

    public function addComment()
    {
        $session = session();
        $dosenId = $session->get('id');

        $logbookId = $this->request->getPost('logbook_id');
        $komentar  = trim($this->request->getPost('komentar') ?? '');

        if (empty($komentar)) {
            return redirect()->back()->with('error', 'Komentar tidak boleh kosong.');
        }

        $logbook = $this->logbookModel->find($logbookId);
        if (!$logbook) {
            return redirect()->to('/bimbingan')->with('error', 'Logbook tidak ditemukan.');
        }

        $internship = $this->internshipModel->find($logbook['internship_id']);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Akses tidak sah.');
        }

        $this->commentModel->save([
            'logbook_id' => $logbookId,
            'dosen_id'   => $dosenId,
            'komentar'   => $komentar,
        ]);

        return redirect()->to("/bimbingan/logbook/{$logbookId}")->with('success', 'Komentar berhasil ditambahkan.');
    }

    public function updateLogbookStatus()
    {
        $session = session();
        $dosenId = $session->get('id');

        $logbookId     = $this->request->getPost('logbook_id');
        $statusReview  = $this->request->getPost('status_review');
        $catatan       = trim($this->request->getPost('catatan') ?? '');

        $logbook = $this->logbookModel->find($logbookId);
        if (!$logbook) {
            return redirect()->to('/bimbingan')->with('error', 'Logbook tidak ditemukan.');
        }

        $internship = $this->internshipModel->find($logbook['internship_id']);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Akses tidak sah.');
        }

        // Update status review
        $this->logbookModel->update($logbookId, [
            'status_review' => $statusReview
        ]);

        // Save review note as a comment if provided
        if (!empty($catatan)) {
            $this->commentModel->save([
                'logbook_id' => $logbookId,
                'dosen_id'   => $dosenId,
                'komentar'   => "Status review diubah menjadi [{$statusReview}]. Catatan: {$catatan}",
            ]);
        } else {
            $this->commentModel->save([
                'logbook_id' => $logbookId,
                'dosen_id'   => $dosenId,
                'komentar'   => "Status review diubah menjadi [{$statusReview}].",
            ]);
        }

        return redirect()->to("/bimbingan/logbook/{$logbookId}")->with('success', 'Status review logbook berhasil diperbarui.');
    }

    public function submitAssessment()
    {
        $session = session();
        $dosenId = $session->get('id');

        $internshipId = $this->request->getPost('internship_id');
        
        $internship = $this->internshipModel->find($internshipId);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Akses tidak sah.');
        }

        $rules = [
            'disiplin'  => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kehadiran' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'kinerja'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'logbook'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'laporan'   => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $disiplin  = $this->request->getPost('disiplin');
        $kehadiran = $this->request->getPost('kehadiran');
        $kinerja   = $this->request->getPost('kinerja');
        $logbook   = $this->request->getPost('logbook');
        $laporan   = $this->request->getPost('laporan');
        $catatan   = trim($this->request->getPost('catatan') ?? '');

        // Auto-calculate final grade: average of all components
        $nilaiAkhir = ($disiplin + $kehadiran + $kinerja + $logbook + $laporan) / 5;

        // Check if assessment already exists
        $existing = $this->assessmentModel->where('internship_id', $internshipId)->first();

        $data = [
            'internship_id' => $internshipId,
            'disiplin'      => $disiplin,
            'kehadiran'     => $kehadiran,
            'kinerja'       => $kinerja,
            'logbook'       => $logbook,
            'laporan'       => $laporan,
            'nilai_akhir'   => $nilaiAkhir,
            'catatan'       => $catatan,
        ];

        if ($existing) {
            $this->assessmentModel->update($existing['id'], $data);
        } else {
            $this->assessmentModel->save($data);
        }

        // Automatically set internship status to "selesai" upon grading
        $this->internshipModel->update($internshipId, [
            'status' => 'selesai'
        ]);

        return redirect()->to("/bimbingan/detail/{$internshipId}")->with('success', 'Nilai PKL berhasil disimpan. Status PKL otomatis diubah menjadi Selesai.');
    }

    public function exportPdf($id)
    {
        $session = session();
        $dosenId = $session->get('id');

        $internship = $this->internshipModel->find($id);
        if (!$internship || $internship['dosen_id'] != $dosenId) {
            return redirect()->to('/bimbingan')->with('error', 'Akses tidak sah.');
        }

        $student = $this->userModel->find($internship['mahasiswa_id']);
        $logbooks = $this->logbookModel->where('internship_id', $id)
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        $html = view('dosen/export_pdf_view', [
            'internship' => $internship,
            'student'    => $student,
            'logbooks'   => $logbooks,
            'dosenNama'  => $session->get('nama')
        ]);

        // Attempt PDF generation via Dompdf if class exists
        if (class_exists('Dompdf\Dompdf')) {
            try {
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true);
                
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                
                $filename = 'Logbook_PKL_' . str_replace(' ', '_', $student['nama']) . '.pdf';
                return $this->response->setHeader('Content-Type', 'application/pdf')
                    ->setBody($dompdf->output());
            } catch (\Exception $e) {
                // If it fails, fallback to simple print layout
            }
        }

        // Fallback: render print view directly to browser
        return $html;
    }

    public function penilaian()
    {
        $session = session();
        $dosenId = $session->get('id');

        $search = $this->request->getGet('search') ?? '';

        $db = \Config\Database::connect();
        $builder = $db->table('internships')
            ->select('internships.id as internship_id, internships.perusahaan, internships.bidang, internships.status as pkl_status,
                      users.nama as mahasiswa_nama, users.nim as mahasiswa_nim, users.email as mahasiswa_email,
                      assessments.nilai_akhir, assessments.disiplin, assessments.kehadiran, assessments.kinerja, assessments.logbook, assessments.laporan, assessments.catatan')
            ->join('users', 'users.id = internships.mahasiswa_id')
            ->join('assessments', 'assessments.internship_id = internships.id', 'left')
            ->where('internships.dosen_id', $dosenId);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('users.nama', $search)
                ->orLike('users.nim', $search)
                ->orLike('internships.perusahaan', $search)
                ->groupEnd();
        }

        $bimbinganPenilaian = $builder->orderBy('users.nama', 'ASC')->get()->getResultArray();

        return view('dosen/penilaian', [
            'title'              => 'Penilaian PKL Mahasiswa',
            'bimbinganPenilaian' => $bimbinganPenilaian,
            'search'             => $search
        ]);
    }
}
