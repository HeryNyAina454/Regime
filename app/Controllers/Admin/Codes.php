<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WalletCodeModel;
use App\Models\SettingModel;

class Codes extends BaseController
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

        $codeModel = new WalletCodeModel();
        $codes     = $codeModel->select('wallet_codes.*, users.username as used_by_name')
                               ->join('users', 'users.id = wallet_codes.used_by', 'left')
                               ->orderBy('wallet_codes.created_at', 'DESC')
                               ->findAll();

        $settings   = (new SettingModel())->getValue('max_recharge', 500);
        $totalCodes = count($codes);
        $usedCodes  = count(array_filter($codes, fn($c) => $c['is_used']));

        return view('admin/codes/index', [
            'title'      => 'Codes portefeuille',
            'codes'      => $codes,
            'totalCodes' => $totalCodes,
            'usedCodes'  => $usedCodes,
            'maxRecharge'=> $settings,
        ]);
    }

    public function store()
    {
        if ($r = $this->checkAdmin()) return $r;

        $maxRecharge = (float)(new SettingModel())->getValue('max_recharge', 500);

        $rules = [
            'code'   => 'required|min_length[4]|max_length[50]|is_unique[wallet_codes.code]',
            'amount' => "required|decimal|greater_than[0]|less_than_equal_to[{$maxRecharge}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/codes')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'create');
        }

        (new WalletCodeModel())->insert([
            'code'   => strtoupper(trim($this->request->getPost('code'))),
            'amount' => $this->request->getPost('amount'),
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code créé avec succès.');
    }

    public function validate_code(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;

        // Valider = marquer comme utilisable à nouveau (reset)
        $codeModel = new WalletCodeModel();
        $code      = $codeModel->find($id);

        if (!$code) {
            return redirect()->to('/admin/codes')->with('error', 'Code introuvable.');
        }

        $codeModel->update($id, [
            'is_used' => false,
            'used_by' => null,
            'used_at' => null,
        ]);

        return redirect()->to('/admin/codes')->with('success', 'Code réinitialisé et disponible à nouveau.');
    }

    public function delete(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;
        (new WalletCodeModel())->delete($id);
        return redirect()->to('/admin/codes')->with('success', 'Code supprimé.');
    }
}