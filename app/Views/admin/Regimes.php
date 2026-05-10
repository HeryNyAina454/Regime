<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Models\RegimePricingModel;

class Regimes extends BaseController
{
    private function checkAdmin()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/login');
        }
        return null;
    }

    public function index()
    {
        if ($r = $this->checkAdmin()) return $r;

        $regimeModel = new RegimeModel();
        $regimes     = $regimeModel->getAllWithPricing();

        return view('admin/regimes/index', [
            'title'   => 'Gestion des régimes',
            'regimes' => $regimes,
        ]);
    }

    public function store()
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'description'      => 'required',
            'goal'             => 'required|in_list[gain,lose,ideal]',
            'meat_pct'         => 'required|integer|greater_than_equal_to[0]',
            'fish_pct'         => 'required|integer|greater_than_equal_to[0]',
            'poultry_pct'      => 'required|integer|greater_than_equal_to[0]',
            'weight_change_kg' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/regimes')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'create');
        }

        // Vérifier que les % totalisent 100
        $total = (int)$this->request->getPost('meat_pct')
               + (int)$this->request->getPost('fish_pct')
               + (int)$this->request->getPost('poultry_pct');

        if ($total !== 100) {
            return redirect()->to('/admin/regimes')
                             ->with('errors', ['composition' => 'La somme des % doit être égale à 100. Actuel : ' . $total . '%'])
                             ->with('modal', 'create');
        }

        $regimeModel = new RegimeModel();
        $regimeId    = $regimeModel->insert([
            'name'             => $this->request->getPost('name'),
            'description'      => $this->request->getPost('description'),
            'goal'             => $this->request->getPost('goal'),
            'meat_pct'         => $this->request->getPost('meat_pct'),
            'fish_pct'         => $this->request->getPost('fish_pct'),
            'poultry_pct'      => $this->request->getPost('poultry_pct'),
            'weight_change_kg' => $this->request->getPost('weight_change_kg'),
            'price'            => 0,
            'duration_days'    => 30,
        ]);

        // Sauvegarder les prix
        $pricingModel = new RegimePricingModel();
        $pricingModel->insertPricing(
            $regimeId,
            $this->request->getPost('duration_days') ?? [],
            $this->request->getPost('prices') ?? []
        );

        return redirect()->to('/admin/regimes')->with('success', 'Régime créé avec succès.');
    }

    public function update(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'description'      => 'required',
            'goal'             => 'required|in_list[gain,lose,ideal]',
            'meat_pct'         => 'required|integer|greater_than_equal_to[0]',
            'fish_pct'         => 'required|integer|greater_than_equal_to[0]',
            'poultry_pct'      => 'required|integer|greater_than_equal_to[0]',
            'weight_change_kg' => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/regimes')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'edit_' . $id);
        }

        $total = (int)$this->request->getPost('meat_pct')
               + (int)$this->request->getPost('fish_pct')
               + (int)$this->request->getPost('poultry_pct');

        if ($total !== 100) {
            return redirect()->to('/admin/regimes')
                             ->with('errors', ['composition' => 'La somme des % doit être égale à 100. Actuel : ' . $total . '%'])
                             ->with('modal', 'edit_' . $id);
        }

        $regimeModel = new RegimeModel();
        $regimeModel->update($id, [
            'name'             => $this->request->getPost('name'),
            'description'      => $this->request->getPost('description'),
            'goal'             => $this->request->getPost('goal'),
            'meat_pct'         => $this->request->getPost('meat_pct'),
            'fish_pct'         => $this->request->getPost('fish_pct'),
            'poultry_pct'      => $this->request->getPost('poultry_pct'),
            'weight_change_kg' => $this->request->getPost('weight_change_kg'),
        ]);

        $pricingModel = new RegimePricingModel();
        $pricingModel->insertPricing(
            $id,
            $this->request->getPost('duration_days') ?? [],
            $this->request->getPost('prices') ?? []
        );

        return redirect()->to('/admin/regimes')->with('success', 'Régime mis à jour.');
    }

    public function delete(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;

        (new RegimePricingModel())->deleteByRegime($id);
        (new RegimeModel())->delete($id);

        return redirect()->to('/admin/regimes')->with('success', 'Régime supprimé.');
    }
}