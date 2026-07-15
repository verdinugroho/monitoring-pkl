<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function processLogin()
    {
        $session = session();
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Email tidak ditemukan');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->to('/login')->with('error', 'Password salah');
        }

        // Check if account is active
        if (isset($user['status_akun']) && $user['status_akun'] === 'nonaktif') {
            return redirect()->to('/login')->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }

        $sessionData = [
            'id'   => $user['id'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true
        ];

        $session->set($sessionData);

        // Redirect based on role
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/dashboard');
    }

    public function processRegister()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'prodi' => 'required',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new UserModel();
        $model->skipValidation(true)->save([
            'nama' => trim($this->request->getPost('nama')),
            'email' => trim($this->request->getPost('email')),
            'prodi' => trim($this->request->getPost('prodi')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'mahasiswa',
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat. Silakan masuk dengan akun baru Anda.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}