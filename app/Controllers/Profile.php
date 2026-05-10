<?php

namespace App\Controllers;

use App\Models\UserProfileModel;

class Profile extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', session()->get('user_id'))->first();

        // Calcul IMC
        $imc = null;
        $imcLabel = null;
        $imcColor = null;

        if ($profile && $profile['height'] > 0) {
            $heightM = $profile['height'] / 100;
            $imc = round($profile['weight'] / ($heightM * $heightM), 1);

            if      ($imc < 18.5) { $imcLabel = 'Insuffisance pondérale'; $imcColor = 'blue'; }
            elseif  ($imc < 25)   { $imcLabel = 'Poids normal';           $imcColor = 'green'; }
            elseif  ($imc < 30)   { $imcLabel = 'Surpoids';               $imcColor = 'orange'; }
            else                  { $imcLabel = 'Obésité';                $imcColor = 'red'; }
        }

        return view('user/profile', [
            'profile'   => $profile,
            'imc'       => $imc,
            'imcLabel'  => $imcLabel,
            'imcColor'  => $imcColor,
        ]);
    }

    public function saveGoal()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $rules = ['goal' => 'required|in_list[gain,lose,ideal]'];

        if (!$this->validate($rules)) {
            return redirect()->to('/profile')->with('error', 'Objectif invalide.');
        }

        $profileModel = new UserProfileModel();
        $profile = $profileModel->where('user_id', session()->get('user_id'))->first();

        if ($profile) {
            $profileModel->update($profile['id'], [
                'goal' => $this->request->getPost('goal'),
            ]);
        }

        return redirect()->to('/dashboard')->with('success', 'Objectif enregistré avec succès !');
    }
}