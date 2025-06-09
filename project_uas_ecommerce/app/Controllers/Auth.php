<?php
namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('auth/login');
    }

    public function doLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            session()->set('user_id', $user['id']);
            
            if($user['role'] === 'admin') {
                return redirect()->to('/admin');
            } else {
                return redirect()->to('/');
            }
        
        } else {
            return redirect()->to('/login')->with('error', 'Email atau password salah.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function doRegister()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = 'customer';

        // Validasi sederhana
        if (empty($username) || empty($email) || empty($password)) {
            return redirect()->to('/register')->with('error', 'Semua field harus diisi.');
        }

        // Periksa apakah username atau email sudah ada
        $existingUser = $userModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->to('/register')->with('error', 'Email sudah digunakan.');
        }

        // Simpan data pengguna
        $userModel->save([
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ]);

        return redirect()->to('/login')->with('success', 'Registrasi berhasil. Silakan login.');
    }
}