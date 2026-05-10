<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserProfileModel;

class Auth extends BaseController
{
    // ─── LOGIN ───────────────────────────────────────────────────
    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $email    = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userModel = new UserModel();
            $user = $userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id'  => $user['id'],
                    'username' => $user['username'],
                    'is_admin' => $user['is_admin'],
                    'logged_in' => true,
                ]);

                if ($user['is_admin']) {
                    return redirect()->to('/admin/dashboard');
                }
                return redirect()->to('/dashboard');
            }

            return view('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
        }

        return view('auth/login');
    }

    // ─── REGISTER ÉTAPE 1 : infos personnelles ───────────────────
    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[100]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[8]',
                'gender'   => 'required|in_list[H,F]',
            ];

            if (!$this->validate($rules)) {
                return view('auth/register_step1', [
                    'errors' => $this->validator->getErrors(),
                    'old'    => $this->request->getPost(),
                ]);
            }

            // Stocker temporairement en session avant étape 2
            session()->set('register_step1', [
                'username' => $this->request->getPost('username'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'gender'   => $this->request->getPost('gender'),
            ]);

            return redirect()->to('/register/health');
        }

        return view('auth/register_step1');
    }

    // ─── REGISTER ÉTAPE 2 : infos de santé ───────────────────────
    public function registerHealth()
    {
        // Si l'étape 1 n'est pas complétée, rediriger
        if (!session()->get('register_step1')) {
            return redirect()->to('/register');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'weight' => 'required|decimal|greater_than[0]',
                'height' => 'required|decimal|greater_than[0]',
                'age'    => 'required|integer|greater_than[0]|less_than[130]',
            ];

            if (!$this->validate($rules)) {
                return view('auth/register_step2', [
                    'errors' => $this->validator->getErrors(),
                    'old'    => $this->request->getPost(),
                ]);
            }

            $step1 = session()->get('register_step1');

            // Insertion dans users
            $userModel = new UserModel();
            $userId = $userModel->insert([
                'username' => $step1['username'],
                'email'    => $step1['email'],
                'password' => password_hash($step1['password'], PASSWORD_DEFAULT),
                'is_admin' => false,
            ]);

            // Insertion dans user_profiles
            $profileModel = new UserProfileModel();
            $profileModel->insert([
                'user_id' => $userId,
                'gender'  => $step1['gender'],
                'weight'  => $this->request->getPost('weight'),
                'height'  => $this->request->getPost('height'),
                'age'     => $this->request->getPost('age'),
            ]);

            // Nettoyer la session temporaire
            session()->remove('register_step1');

            return redirect()->to('/login')->with('success', 'Compte créé avec succès ! Connectez-vous.');
        }

        return view('auth/register_step2');
    }

    // ─── LOGOUT ──────────────────────────────────────────────────
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}