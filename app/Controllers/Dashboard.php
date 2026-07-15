<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InternshipModel;
use App\Models\LogbookModel;
use App\Models\CommentModel;
use App\Models\AssessmentModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = $session->get('id');
        $role = $session->get('role');

        $internshipModel = new InternshipModel();
        $logbookModel    = new LogbookModel();
        $commentModel    = new CommentModel();
        $userModel       = new UserModel();
        $assessmentModel = new AssessmentModel();

        if ($role === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        if ($role === 'mahasiswa') {
            // Get student's internship
            $internship = $internshipModel->where('mahasiswa_id', $userId)->first();

            if (!$internship) {
                return redirect()->to('/setup-pkl');
            }

            // Fetch Dosen Pembimbing name
            $dosen = $userModel->find($internship['dosen_id']);
            $dosenNama = $dosen ? $dosen['nama'] : 'Belum ditentukan';

            // Calculate days and progress
            $mulai = new \DateTime($internship['tanggal_mulai']);
            $selesai = new \DateTime($internship['tanggal_selesai']);
            $interval = $mulai->diff($selesai);
            $totalHari = max(1, $interval->days + 1);

            $jumlahLogbook = $logbookModel->where('internship_id', $internship['id'])->countAllResults();
            $progress = min(100, round(($jumlahLogbook / $totalHari) * 100, 1));

            // Fetch latest comments
            // Query with join to get the writer's name (dosen)
            $db = \Config\Database::connect();
            $recentComments = $db->table('comments')
                ->select('comments.*, users.nama as dosen_nama, logbooks.tanggal as logbook_tanggal, logbooks.aktivitas as logbook_aktivitas')
                ->join('logbooks', 'logbooks.id = comments.logbook_id')
                ->join('users', 'users.id = comments.dosen_id')
                ->where('logbooks.internship_id', $internship['id'])
                ->orderBy('comments.created_at', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Calculate weekly chart activity (weekly intervals of the internship)
            $weeks = [];
            $labels = [];
            $counts = [];
            $tempDate = clone $mulai;
            $weekNum = 1;
            // Show up to 12 weeks or until the end date
            while ($tempDate <= $selesai && $weekNum <= 12) {
                $weekStart = $tempDate->format('Y-m-d');
                $nextDate = clone $tempDate;
                $nextDate->modify('+6 days');
                $weekEnd = $nextDate->format('Y-m-d');

                $count = $logbookModel->where('internship_id', $internship['id'])
                    ->where('tanggal >=', $weekStart)
                    ->where('tanggal <=', $weekEnd)
                    ->countAllResults();

                $labels[] = "Minggu " . $weekNum;
                $counts[] = $count;

                $tempDate->modify('+7 days');
                $weekNum++;
            }

            // Fetch final grade if exists
            $assessment = $assessmentModel->where('internship_id', $internship['id'])->first();

            return view('dashboard/mahasiswa', [
                'internship'    => $internship,
                'dosenNama'     => $dosenNama,
                'totalHari'     => $totalHari,
                'jumlahLogbook' => $jumlahLogbook,
                'progress'      => $progress,
                'comments'      => $recentComments,
                'chartLabels'   => json_encode($labels),
                'chartCounts'   => json_encode($counts),
                'assessment'    => $assessment
            ]);

        } else {
            // DOSEN DASHBOARD
            // Students managed by this Dosen
            $bimbingan = $internshipModel->where('dosen_id', $userId)->findAll();
            $totalBimbingan = count($bimbingan);

            $mahasiswaAktif = 0;
            $mahasiswaSelesai = 0;
            $internshipIds = [];

            foreach ($bimbingan as $i) {
                $internshipIds[] = $i['id'];
                if ($i['status'] === 'aktif') {
                    $mahasiswaAktif++;
                } else {
                    $mahasiswaSelesai++;
                }
            }

            // Total logbook this week
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $endOfWeek   = date('Y-m-d', strtotime('sunday this week'));

            $logbooksThisWeek = 0;
            $logbooksPending = 0;

            if (!empty($internshipIds)) {
                $logbooksThisWeek = $logbookModel->whereIn('internship_id', $internshipIds)
                    ->where('tanggal >=', $startOfWeek)
                    ->where('tanggal <=', $endOfWeek)
                    ->countAllResults();

                $logbooksPending = $logbookModel->whereIn('internship_id', $internshipIds)
                    ->where('status_review', 'Menunggu Review')
                    ->countAllResults();
            }

            // Activity Chart for Dosen: logbooks count per day for the last 7 days
            $chartLabels = [];
            $chartCounts = [];
            for ($i = 6; $i >= 0; $i--) {
                $dateStr = date('Y-m-d', strtotime("-$i days"));
                $dayName = date('d M', strtotime($dateStr));
                $chartLabels[] = $dayName;

                if (!empty($internshipIds)) {
                    $count = $logbookModel->whereIn('internship_id', $internshipIds)
                        ->where('tanggal', $dateStr)
                        ->countAllResults();
                } else {
                    $count = 0;
                }
                $chartCounts[] = $count;
            }

            return view('dashboard/dosen', [
                'totalBimbingan'   => $totalBimbingan,
                'mahasiswaAktif'   => $mahasiswaAktif,
                'mahasiswaSelesai' => $mahasiswaSelesai,
                'logbooksThisWeek' => $logbooksThisWeek,
                'logbooksPending'  => $logbooksPending,
                'chartLabels'      => json_encode($chartLabels),
                'chartCounts'      => json_encode($chartCounts)
            ]);
        }
    }

    public function setupPkl()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'mahasiswa') {
            return redirect()->to('/dashboard');
        }

        $internshipModel = new InternshipModel();
        $existing = $internshipModel->where('mahasiswa_id', $session->get('id'))->first();
        if ($existing) {
            return redirect()->to('/dashboard');
        }

        $userModel = new UserModel();
        $dosenList = $userModel->where('role', 'dosen')->findAll();
        $currentUser = $userModel->find($session->get('id'));

        return view('dashboard/setup_pkl', [
            'dosenList' => $dosenList,
            'user' => $currentUser
        ]);
    }

    public function storePkl()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'mahasiswa') {
            return redirect()->to('/dashboard');
        }

        $rules = [
            'dosen_id'        => 'required|integer',
            'prodi'           => 'required',
            'perusahaan'      => 'required|min_length[3]|max_length[150]',
            'bidang'          => 'required|min_length[2]|max_length[100]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $userModel->update($session->get('id'), [
            'prodi' => trim($this->request->getPost('prodi'))
        ]);

        $internshipModel = new InternshipModel();
        $internshipModel->save([
            'mahasiswa_id'    => $session->get('id'),
            'dosen_id'        => $this->request->getPost('dosen_id'),
            'perusahaan'      => trim($this->request->getPost('perusahaan')),
            'bidang'          => trim($this->request->getPost('bidang')),
            'tanggal_mulai'   => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'status'          => 'aktif',
        ]);

        return redirect()->to('/dashboard')->with('success', 'Data PKL berhasil dikonfigurasi. Anda sekarang dapat mengisi logbook harian.');
    }
}