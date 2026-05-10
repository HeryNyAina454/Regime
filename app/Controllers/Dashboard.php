<?php
namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\OrderModel;
use App\Models\WalletModel;
use App\Models\WalletCodeModel;
use App\Models\RegimeModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId       = session()->get('user_id');
        $profileModel = new UserProfileModel();
        $profile      = $profileModel->where('user_id', $userId)->first();

        // IMC
        $imc = null; $imcLabel = null; $imcColor = null;
        if ($profile && $profile['height'] > 0) {
            $h   = $profile['height'] / 100;
            $imc = round($profile['weight'] / ($h * $h), 1);
            if      ($imc < 18.5) { $imcLabel = 'Insuffisance pondérale'; $imcColor = 'blue'; }
            elseif  ($imc < 25)   { $imcLabel = 'Poids normal';           $imcColor = 'green'; }
            elseif  ($imc < 30)   { $imcLabel = 'Surpoids';               $imcColor = 'orange'; }
            else                  { $imcLabel = 'Obésité';                $imcColor = 'red'; }
        }

        // Wallet
        $walletModel = new WalletModel();
        $walletModel->initForUser($userId);
        $wallet = $walletModel->getByUser($userId);

        // Commandes de l'utilisateur
        $orderModel  = new OrderModel();
        $orders      = $orderModel->where('user_id', $userId)
                                  ->orderBy('ordered_at', 'DESC')
                                  ->findAll();

        // Enrichir avec nom régime
        $regimeModel = new RegimeModel();
        foreach ($orders as &$o) {
            $r = $regimeModel->find($o['regime_id']);
            $o['regime_name'] = $r ? $r['name'] : '—';
        }
        unset($o);

        // Codes utilisés
        $codeModel = new WalletCodeModel();
        $usedCodes = $codeModel->where('used_by', $userId)->countAllResults();

        // Dépenses par mois (6 derniers mois)
        $db = \Config\Database::connect();
        $monthlySpend = $db->query("
            SELECT DATE_FORMAT(ordered_at, '%b %Y') as month,
                   SUM(price_paid) as total
            FROM orders
            WHERE user_id = {$userId}
              AND ordered_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(ordered_at, '%Y-%m')
            ORDER BY MIN(ordered_at) ASC
        ")->getResultArray();

        $goalLabels = [
            'gain'  => 'Augmenter le poids',
            'lose'  => 'Réduire le poids',
            'ideal' => 'Atteindre l\'IMC idéal',
            null    => 'Non défini',
        ];

        return view('user/dashboard', [
            'profile'      => $profile,
            'imc'          => $imc,
            'imcLabel'     => $imcLabel,
            'imcColor'     => $imcColor,
            'wallet'       => $wallet,
            'orders'       => $orders,
            'ordersCount'  => count($orders),
            'usedCodes'    => $usedCodes,
            'monthlySpend' => $monthlySpend,
            'goalLabel'    => $goalLabels[$profile['goal'] ?? null],
        ]);
    }
}