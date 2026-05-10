<?php
namespace App\Controllers;

use App\Models\WalletModel;
use App\Models\WalletCodeModel;
use App\Models\OrderModel;
use App\Models\RegimeModel;

class Wallet extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId      = session()->get('user_id');
        $walletModel = new WalletModel();
        $walletModel->initForUser($userId);
        $wallet = $walletModel->getByUser($userId);

        // Historique des commandes
        $orderModel = new OrderModel();
        $orders     = $orderModel->where('user_id', $userId)
                                 ->orderBy('ordered_at', 'DESC')
                                 ->findAll();

        // Enrichir les commandes avec le nom du régime
        $regimeModel = new RegimeModel();
        foreach ($orders as &$order) {
            $regime = $regimeModel->find($order['regime_id']);
            $order['regime_name'] = $regime ? $regime['name'] : 'Régime supprimé';
        }
        unset($order);

        // Historique des codes utilisés
        $codeModel = new WalletCodeModel();
        $usedCodes = $codeModel->where('used_by', $userId)
                               ->orderBy('used_at', 'DESC')
                               ->findAll();

        return view('user/wallet', [
            'wallet'    => $wallet,
            'orders'    => $orders,
            'usedCodes' => $usedCodes,
        ]);
    }

    public function recharge()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId    = session()->get('user_id');
        $code      = strtoupper(trim($this->request->getPost('code')));

        if (empty($code)) {
            return redirect()->to('/wallet')->with('error', 'Veuillez entrer un code.');
        }

        $codeModel  = new WalletCodeModel();
        $validCode  = $codeModel->findValidCode($code);

        if (!$validCode) {
            return redirect()->to('/wallet')->with('error', 'Code invalide ou déjà utilisé.');
        }

        // Créditer le portefeuille
        $walletModel = new WalletModel();
        $wallet      = $walletModel->getByUser($userId);

        $walletModel->update($wallet['id'], [
            'balance' => $wallet['balance'] + $validCode['amount'],
        ]);

        // Marquer le code comme utilisé
        $codeModel->markAsUsed($validCode['id'], $userId);

        $amount = number_format($validCode['amount'], 2);
        return redirect()->to('/wallet')->with('success', "+ {$amount} € ajoutés à votre portefeuille !");
    }
}