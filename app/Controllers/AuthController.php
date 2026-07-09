<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use League\OAuth2\Client\Provider\Google;
use CodeIgniter\HTTP\RedirectResponse;

class AuthController extends BaseController
{
    public function __construct()
    {
        helper("form");
    }

    public function login()
    {
        if ($this->request->getPost()) {
            $username = $this->request->getVar("username");
            $password = $this->request->getVar("password");

            $db = \Config\Database::connect();
            $userTable = $db->table('user');
            
            $user = $userTable->where('username', $username)->get()->getRowArray();

            if ($user) {
                if (md5($password) == $user["password"]) {
                    session()->set([
                        "id" => $user["id"],
                        "username" => $user["username"],
                        "role" => $user["role"],
                        "isLoggedIn" => true,
                        "logged_in" => true,
                    ]);

                    if (!empty($user['cart_data'])) {
                        $savedCart = json_decode($user['cart_data'], true);
                        if (is_array($savedCart)) {
                            session()->set('cart', $savedCart);
                        }
                    }

                    return redirect()->to(base_url("/"));
                } else {
                    session()->setFlashdata("failed", "Password Salah!");
                    return redirect()->back();
                }
            } else {
                session()->setFlashdata("failed", "Username Tidak Ditemukan!");
                return redirect()->back();
            }
        } else {
            return view("v_login", ["is_register" => false]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to("login");
    }

    public function register()
    {
        if ($this->request->getPost()) {
            $db = \Config\Database::connect();
            $userTable = $db->table('user');
            
            $username = $this->request->getVar("username");
            $email = $this->request->getVar("email");
            $password = $this->request->getVar("password");

            // Cek jika username/email sudah ada
            $existing = $userTable->where('username', $username)->orWhere('email', $email)->get()->getRow();
            if ($existing) {
                session()->setFlashdata("failed_register", "Username atau Email sudah terdaftar!");
                return redirect()->back();
            }

            $userTable->insert([
                "username" => $username,
                "email" => $email,
                "password" => md5($password),
                "role" => "pelanggan",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata("success", "Pendaftaran berhasil! Silakan login.");
            return redirect()->to(base_url("login"));
        }
        
        return view("v_login", ["is_register" => true]);
    }

    protected function getProvider(): Google
    {
        return new Google([
            'clientId'     => env('GOOGLE_CLIENT_ID'),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => env('GOOGLE_REDIRECT_URI'),
        ]);
    }

    public function oauthGoogle()
    {
        $provider = $this->getProvider();
        
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['email', 'profile'],
        ]);
        
        session()->set('oauth2state', $provider->getState());
        
        return redirect()->to($authUrl);
    }

    public function oauthGoogleCallback()
    {
        $provider = $this->getProvider();
        $session = session();
        
        if (request()->getGet('error')) {
            return redirect()->to('/login')->with('failed', 'Login dengan Google dibatalkan.');
        }
        
        $state = request()->getGet('state');
        if (!$state || $state !== $session->get('oauth2state')) {
            $session->remove('oauth2state');
            return redirect()->to('/login')->with('failed', 'Sesi login tidak valid, silakan coba lagi.');
        }
        $session->remove('oauth2state');
        
        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => request()->getGet('code'),
            ]);
            
            $googleUser = $provider->getResourceOwner($token);
            $profile = $googleUser->toArray();
            
            $email = $profile['email'] ?? null;
            $googleId = $profile['sub'] ?? null;
            $verified = $profile['email_verified'] ?? false;
            
            if (!$email || !$verified) {
                return redirect()->to('/login')->with('failed', 'Akun Google tidak memiliki email terverifikasi.');
            }
            
            $googleProfile = [
                'name' => $profile['name'] ?? 'Google User',
                'email' => $email,
                'google_id' => $googleId
            ];
            
            return $this->processRealSocialLogin($googleProfile, 'Google');
            
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth error: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('/login')->with('failed', 'Terjadi kesalahan saat login dengan Google.');
        }
    }

    private function processRealSocialLogin($profile, $platform)
    {
        $db = \Config\Database::connect();
        $userTable = $db->table('user');
        
        $user = $userTable->where('email', $profile['email'])->get()->getRowArray();
        
        if (!$user) {
            // Pendaftaran Baru Otomatis jika email belum ada
            $username = strtolower(str_replace(' ', '', $profile['name'])) . rand(10, 99);
            $dataNewUser = [
                'username'   => $username,
                'email'      => $profile['email'],
                'google_id'  => $profile['google_id'] ?? null,
                'password'   => md5(rand(100000, 999999)),
                'role'       => 'pelanggan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $userTable->insert($dataNewUser);
            $user = $userTable->where('email', $profile['email'])->get()->getRowArray();
            session()->setFlashdata("success", "Pendaftaran berhasil via " . $platform . "! Anda telah masuk.");
        } else {
            // Jika user sudah ada tapi google_id nya belum terisi, update google_id-nya
            if (empty($user['google_id']) && isset($profile['google_id'])) {
                $userTable->where('id', $user['id'])->update(['google_id' => $profile['google_id']]);
            }
            session()->setFlashdata("success", "Berhasil masuk via " . $platform . "!");
        }
        
        // Set session login
        session()->set([
            "id"         => $user['id'],
            "username"   => $user['username'],
            "role"       => $user['role'],
            "isLoggedIn" => true,
            "logged_in"  => true,
        ]);

        if (!empty($user['cart_data'])) {
            $savedCart = json_decode($user['cart_data'], true);
            if (is_array($savedCart)) {
                session()->set('cart', $savedCart);
            }
        }

        return redirect()->to(base_url("/"));
    }

    public function oauthFacebook($action = 'login')
    {
        $email = $this->request->getPost('social_email') ?? 'fb_user' . rand(100,999) . '@yahoo.com';
        $name = $this->request->getPost('social_name') ?? 'Facebook User';
        
        $facebookProfile = [
            'name'  => $name,
            'email' => $email
        ];
        return $this->processSocialLogin($facebookProfile, $action);
    }

    private function processSocialLogin($profile, $action)
    {
        $db = \Config\Database::connect();
        $userTable = $db->table('user');

        $user = $userTable->where('email', $profile['email'])->get()->getRowArray();

        if ($action == 'register') {
            if ($user) {
                session()->setFlashdata("failed", "Email ini sudah terdaftar. Silakan gunakan form Login!");
                return redirect()->to(base_url("login"));
            }

            // Proses Pendaftaran via Social
            $username = strtolower(str_replace(' ', '', $profile['name'])) . rand(10, 99);
            $dataNewUser = [
                'username'   => $username,
                'email'      => $profile['email'],
                'password'   => md5(rand(100000, 999999)),
                'role'       => 'pelanggan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $userTable->insert($dataNewUser);
            
            session()->setFlashdata("success", "Pendaftaran via Social Media berhasil! Silakan login menggunakan email tersebut.");
            return redirect()->to(base_url("login"));

        } else {
            // Action = Login
            if (!$user) {
                session()->setFlashdata("failed", "Email ini belum terdaftar di sistem! Silakan daftar (Buat Akun) terlebih dahulu.");
                return redirect()->to(base_url("login"));
            }

            // Set session login
            session()->set([
                "id"         => $user['id'],
                "username"   => $user['username'],
                "role"       => $user['role'],
                "isLoggedIn" => true,
                "logged_in"  => true,
            ]);

            if (!empty($user['cart_data'])) {
                $savedCart = json_decode($user['cart_data'], true);
                if (is_array($savedCart)) {
                    session()->set('cart', $savedCart);
                }
            }

            return redirect()->to(base_url("/"));
        }
    }
}
