<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;

class Mahasiswa extends BaseController
{
    protected $mahasiswa;

    public function __construct()
    {
        $this->mahasiswa = new MahasiswaModel();
    }

    public function index()
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        $search = trim($this->request->getGet('search') ?? '');
        $prodi = trim($this->request->getGet('prodi') ?? '');
        $query = $this->mahasiswa->orderBy('created_at', 'DESC');

        if ($search !== '') {
            $query->groupStart()
                ->like('nim', $search)
                ->orLike('nama', $search)
                ->orLike('prodi', $search)
                ->groupEnd();
        }

        if ($prodi !== '') {
            $query->where('prodi', $prodi);
        }

        $mahasiswa = $query->findAll();

        $data = [
            'title' => 'Data Mahasiswa',
            'mahasiswa' => $mahasiswa,
            'search' => $search,
            'prodi' => $prodi,
            'totalMahasiswa' => count($mahasiswa),
            'aktifMahasiswa' => count($mahasiswa),
        ];

        return view('mahasiswa/index', $data);
    }

    public function create()
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        return view('mahasiswa/create', [
            'title' => 'Tambah Mahasiswa',
        ]);
    }

    public function store()
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        $rules = [
            'nim' => 'required|is_unique[mahasiswa.nim]|max_length[20]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'prodi' => 'required|max_length[50]',
            'angkatan' => 'required|numeric',
            'telepon' => 'required|min_length[10]|max_length[15]',
            'alamat' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mahasiswa->save([
            'user_id' => session()->get('id') ?? 3,
            'nim' => trim($this->request->getPost('nim')),
            'nama' => trim($this->request->getPost('nama')),
            'prodi' => trim($this->request->getPost('prodi')),
            'angkatan' => trim($this->request->getPost('angkatan')),
            'telepon' => trim($this->request->getPost('telepon')),
            'alamat' => trim($this->request->getPost('alamat')),
        ]);

        return redirect()->to('/mahasiswa')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function detail($id)
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        $data = $this->mahasiswa->find($id);

        if (!$data) {
            return redirect()->to('/mahasiswa')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        return view('mahasiswa/detail', [
            'title' => 'Detail Mahasiswa',
            'mahasiswa' => $data,
        ]);
    }

    public function edit($id)
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        return view('mahasiswa/edit', [
            'title' => 'Edit Mahasiswa',
            'mahasiswa' => $this->mahasiswa->find($id),
        ]);
    }

    public function update($id)
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        $rules = [
            'nim' => 'required|max_length[20]',
            'nama' => 'required|min_length[3]|max_length[100]',
            'prodi' => 'required|max_length[50]',
            'angkatan' => 'required|numeric',
            'telepon' => 'required|min_length[10]|max_length[15]',
            'alamat' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->mahasiswa->update($id, [
            'nim' => trim($this->request->getPost('nim')),
            'nama' => trim($this->request->getPost('nama')),
            'prodi' => trim($this->request->getPost('prodi')),
            'angkatan' => trim($this->request->getPost('angkatan')),
            'telepon' => trim($this->request->getPost('telepon')),
            'alamat' => trim($this->request->getPost('alamat')),
        ]);

        return redirect()->to('/mahasiswa')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function delete($id)
    {
        $redirect = $this->enforceLogin();
        if ($redirect) {
            return $redirect;
        }

        $this->mahasiswa->delete($id);

        return redirect()->to('/mahasiswa')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}