<?php
namespace App\Controllers;

use App\Models\WalletModel;

class Gold extends BaseController
{
    private const GOLD_PRICE = 99.99;

    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $walletModel = new WalletModel();
        $walletModel->initForUser(session()->get('user_id'));
        $wallet = $walletModel->getByUser(session()->get('user_id'));

        return view('user/gold', [
            'wallet'     => $wallet,
            'goldPrice'  => self::GOLD_PRICE,
            'canAfford'  => $wallet['balance'] >= self::GOLD_PRICE,
        ]);
    }

    public function activate()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId      = session()->get('user_id');
        $walletModel = new WalletModel();
        $wallet      = $walletModel->getByUser($userId);

        if ($wallet['is_gold']) {
            return redirect()->to('/gold')->with('error', 'Vous avez déjà l\'option Gold.');
        }

        if ($wallet['balance'] < self::GOLD_PRICE) {
            return redirect()->to('/gold')->with('error', 'Solde insuffisant. Rechargez votre portefeuille.');
        }

        $walletModel->update($wallet['id'], [
            'balance' => $wallet['balance'] - self::GOLD_PRICE,
            'is_gold' => true,
        ]);

        return redirect()->to('/gold')->with('success', 'Option Gold activée ! Profitez de 15% de remise sur tous les régimes.');
    }
}