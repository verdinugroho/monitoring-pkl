<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\InternshipModel;
use App\Models\LogbookModel;
use App\Models\AssessmentModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $internshipModel;
    protected $logbookModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->internshipModel = new InternshipModel();
        $this->logbookModel    = new LogbookModel();
    }

    // ─── Dashboard ──────────────────────────────────────────
    public function dashboard()
    {
        $totalMahasiswa = $this->userModel->where('role', 'mahasiswa')->countAllResults();
        $totalDosen     = $this->userModel->where('role', 'dosen')->countAllResults();
        $pklAktif       = $this->internshipModel->where('status', 'aktif')->countAllResults();
        $pklSelesai     = $this->internshipModel->where('status', 'selesai')->countAllResults();

        // Chart 1: Mahasiswa per status PKL
        $mahasiswaBelumPkl = $totalMahasiswa - $pklAktif - $pklSelesai;
        if ($mahasiswaBelumPkl < 0) $mahasiswaBelumPkl = 0;
        $statusPklData = [
            'labels' => json_encode(['PKL Aktif', 'PKL Selesai', 'Belum PKL']),
            'data'   => json_encode([$pklAktif, $pklSelesai, $mahasiswaBelumPkl]),
        ];

        // Chart 2: Aktivitas logbook mingguan (4 minggu terakhir)
        $weeklyLabels = [];
        $weeklyCounts = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = date('Y-m-d', strtotime("-{$i} weeks monday"));
            $weekEnd   = date('Y-m-d', strtotime("-{$i} weeks sunday"));
            $weekLabel = date('d M', strtotime($weekStart));
            $weeklyLabels[] = "Minggu " . date('d/m', strtotime($weekStart));
            $weeklyCounts[] = $this->logbookModel
                ->where('tanggal >=', $weekStart)
                ->where('tanggal <=', $weekEnd)
                ->countAllResults();
        }

        // Recent activities
        $db = \Config\Database::connect();
        $recentLogbooks = $db->table('logbooks')
            ->select('logbooks.*, internships.perusahaan, users.nama as mahasiswa_nama')
            ->join('internships', 'internships.id = logbooks.internship_id')
            ->join('users', 'users.id = internships.mahasiswa_id')
            ->orderBy('logbooks.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('admin/dashboard', [
            'title'           => 'Admin Dashboard',
            'totalMahasiswa'  => $totalMahasiswa,
            'totalDosen'      => $totalDosen,
            'pklAktif'        => $pklAktif,
            'pklSelesai'      => $pklSelesai,
            'statusPklLabels' => $statusPklData['labels'],
            'statusPklData'   => $statusPklData['data'],
            'weeklyLabels'    => json_encode($weeklyLabels),
            'weeklyCounts'    => json_encode($weeklyCounts),
            'recentLogbooks'  => $recentLogbooks,
        ]);
    }

    // ─── CRUD Mahasiswa ─────────────────────────────────────
    public function mahasiswa()
    {
        $search = $this->request->getGet('search') ?? '';
        $prodi  = $this->request->getGet('prodi') ?? '';
        $page   = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        $builder = $this->userModel->where('role', 'mahasiswa');

        if ($search !== '') {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nim', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        if ($prodi !== '') {
            $builder->where('prodi', $prodi);
        }

        $total = $builder->countAllResults(false);
        $mahasiswa = $builder->orderBy('nama', 'ASC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->findAll();

        // Get unique prodi for filter
        $prodiList = $this->userModel->where('role', 'mahasiswa')
            ->where('prodi IS NOT NULL')
            ->where('prodi !=', '')
            ->select('prodi')
            ->distinct()
            ->findAll();

        return view('admin/mahasiswa/index', [
            'title'     => 'Kelola Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'search'    => $search,
            'prodi'     => $prodi,
            'prodiList' => array_column($prodiList, 'prodi'),
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'totalPages' => ceil($total / $perPage),
        ]);
    }

    public function createMahasiswa()
    {
        return view('admin/mahasiswa/create', [
            'title' => 'Tambah Mahasiswa',
        ]);
    }

    public function storeMahasiswa()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'nim'      => 'required|min_length[3]|max_length[20]|is_unique[users.nim]',
            'prodi'    => 'required|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->skipValidation(true)->save([
            'nama'        => trim($this->request->getPost('nama')),
            'nim'         => trim($this->request->getPost('nim')),
            'prodi'       => trim($this->request->getPost('prodi')),
            'email'       => trim($this->request->getPost('email')),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => 'mahasiswa',
            'status_akun' => 'aktif',
        ]);

        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function editMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/mahasiswa/edit', [
            'title' => 'Edit Mahasiswa',
            'user'  => $user,
        ]);
    }

    public function updateMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'nim'   => "required|min_length[3]|max_length[20]|is_unique[users.nim,id,{$id}]",
            'prodi' => 'required|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        $password = $this->request->getPost('password');
        if ($password !== null && $password !== '') {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'  => trim($this->request->getPost('nama')),
            'nim'   => trim($this->request->getPost('nim')),
            'prodi' => trim($this->request->getPost('prodi')),
            'email' => trim($this->request->getPost('email')),
        ];

        if ($password !== null && $password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->skipValidation(true)->update($id, $data);
        return redirect()->to('/admin/mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function deleteMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/mahasiswa')->with('success', 'Mahasiswa berhasil dihapus.');
    }

    public function detailMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $internship = $this->internshipModel->where('mahasiswa_id', $id)->first();
        $logbooks = [];
        $dosen = null;

        if ($internship) {
            $logbooks = $this->logbookModel->where('internship_id', $internship['id'])
                ->orderBy('tanggal', 'DESC')->findAll();
            $dosen = $this->userModel->find($internship['dosen_id']);
        }

        return view('admin/mahasiswa/detail', [
            'title'      => 'Detail Mahasiswa',
            'user'       => $user,
            'internship' => $internship,
            'logbooks'   => $logbooks,
            'dosen'      => $dosen,
        ]);
    }

    public function toggleStatusMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $newStatus = $user['status_akun'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->userModel->skipValidation(true)->update($id, ['status_akun' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to('/admin/mahasiswa')->with('success', "Akun mahasiswa berhasil {$label}.");
    }

    public function resetPasswordMahasiswa($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'mahasiswa') {
            return redirect()->to('/admin/mahasiswa')->with('error', 'Data tidak ditemukan.');
        }

        $this->userModel->skipValidation(true)->update($id, [
            'password' => password_hash('password123', PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/mahasiswa')->with('success', 'Password mahasiswa berhasil direset ke default (password123).');
    }

    // ─── CRUD Dosen ─────────────────────────────────────────
    public function dosen()
    {
        $search = $this->request->getGet('search') ?? '';
        $prodi  = $this->request->getGet('prodi') ?? '';
        $page   = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 10;

        $builder = $this->userModel->where('role', 'dosen');

        if ($search !== '') {
            $builder->groupStart()
                ->like('nama', $search)
                ->orLike('nidn', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }
        if ($prodi !== '') {
            $builder->where('prodi', $prodi);
        }

        $total = $builder->countAllResults(false);
        $dosenList = $builder->orderBy('nama', 'ASC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->findAll();

        $prodiList = $this->userModel->where('role', 'dosen')
            ->where('prodi IS NOT NULL')
            ->where('prodi !=', '')
            ->select('prodi')
            ->distinct()
            ->findAll();

        return view('admin/dosen/index', [
            'title'     => 'Kelola Dosen',
            'dosenList' => $dosenList,
            'search'    => $search,
            'prodi'     => $prodi,
            'prodiList' => array_column($prodiList, 'prodi'),
            'total'     => $total,
            'page'      => $page,
            'perPage'   => $perPage,
            'totalPages' => ceil($total / $perPage),
        ]);
    }

    public function createDosen()
    {
        return view('admin/dosen/create', [
            'title' => 'Tambah Dosen',
        ]);
    }

    public function storeDosen()
    {
        $rules = [
            'nama'     => 'required|min_length[3]|max_length[100]',
            'nidn'     => 'required|min_length[3]|max_length[20]|is_unique[users.nidn]',
            'prodi'    => 'required|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->skipValidation(true)->save([
            'nama'        => trim($this->request->getPost('nama')),
            'nidn'        => trim($this->request->getPost('nidn')),
            'prodi'       => trim($this->request->getPost('prodi')),
            'email'       => trim($this->request->getPost('email')),
            'password'    => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'        => 'dosen',
            'status_akun' => 'aktif',
        ]);

        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil ditambahkan.');
    }

    public function editDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        return view('admin/dosen/edit', [
            'title' => 'Edit Dosen',
            'user'  => $user,
        ]);
    }

    public function updateDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'nama'  => 'required|min_length[3]|max_length[100]',
            'nidn'  => "required|min_length[3]|max_length[20]|is_unique[users.nidn,id,{$id}]",
            'prodi' => 'required|max_length[100]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        $password = $this->request->getPost('password');
        if ($password !== null && $password !== '') {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama'  => trim($this->request->getPost('nama')),
            'nidn'  => trim($this->request->getPost('nidn')),
            'prodi' => trim($this->request->getPost('prodi')),
            'email' => trim($this->request->getPost('email')),
        ];

        if ($password !== null && $password !== '') {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->skipValidation(true)->update($id, $data);
        return redirect()->to('/admin/dosen')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function deleteDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $this->userModel->delete($id);
        return redirect()->to('/admin/dosen')->with('success', 'Dosen berhasil dihapus.');
    }

    public function detailDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $bimbingan = $this->internshipModel->where('dosen_id', $id)->findAll();

        // Enrich with student names
        foreach ($bimbingan as &$item) {
            $mhs = $this->userModel->find($item['mahasiswa_id']);
            $item['mahasiswa_nama'] = $mhs ? $mhs['nama'] : '-';
            $item['mahasiswa_nim']  = $mhs ? ($mhs['nim'] ?? '-') : '-';
        }

        return view('admin/dosen/detail', [
            'title'     => 'Detail Dosen',
            'user'      => $user,
            'bimbingan' => $bimbingan,
        ]);
    }

    public function toggleStatusDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $newStatus = $user['status_akun'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->userModel->skipValidation(true)->update($id, ['status_akun' => $newStatus]);

        $label = $newStatus === 'aktif' ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->to('/admin/dosen')->with('success', "Akun dosen berhasil {$label}.");
    }

    public function resetPasswordDosen($id)
    {
        $user = $this->userModel->find($id);
        if (!$user || $user['role'] !== 'dosen') {
            return redirect()->to('/admin/dosen')->with('error', 'Data tidak ditemukan.');
        }

        $this->userModel->skipValidation(true)->update($id, [
            'password' => password_hash('password123', PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/admin/dosen')->with('success', 'Password dosen berhasil direset ke default (password123).');
    }

    // Rekap Penilaian Seluruh Mahasiswa
    public function penilaian()
    {
        $db = \Config\Database::connect();
        $search = $this->request->getGet('search') ?? '';

        $builder = $db->table('internships')
            ->select('internships.id as internship_id, internships.perusahaan, internships.bidang, internships.status as pkl_status,
                      mhs.nama as mahasiswa_nama, mhs.nim as mahasiswa_nim, mhs.prodi as mahasiswa_prodi,
                      dosen.nama as dosen_nama,
                      assessments.nilai_akhir, assessments.disiplin, assessments.kehadiran, assessments.kinerja, assessments.logbook, assessments.laporan, assessments.catatan')
            ->join('users mhs', 'mhs.id = internships.mahasiswa_id')
            ->join('users dosen', 'dosen.id = internships.dosen_id')
            ->join('assessments', 'assessments.internship_id = internships.id', 'left');

        if (!empty($search)) {
            $builder->groupStart()
                ->like('mhs.nama', $search)
                ->orLike('mhs.nim', $search)
                ->orLike('dosen.nama', $search)
                ->orLike('internships.perusahaan', $search)
                ->groupEnd();
        }

        $penilaianList = $builder->orderBy('mhs.nama', 'ASC')->get()->getResultArray();

        return view('admin/penilaian', [
            'title'         => 'Rekap Penilaian PKL',
            'penilaianList' => $penilaianList,
            'search'        => $search
        ]);
    }
}
