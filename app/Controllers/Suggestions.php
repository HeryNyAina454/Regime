<?php
namespace App\Controllers;

use App\Models\UserProfileModel;
use App\Models\RegimeModel;
use App\Models\SportActivityModel;
use App\Models\WalletModel;
use App\Models\OrderModel;

class Suggestions extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId       = session()->get('user_id');
        $profileModel = new UserProfileModel();
        $profile      = $profileModel->where('user_id', $userId)->first();

        // Pas d'objectif défini → rediriger vers profil
        if (!$profile || !$profile['goal']) {
            return redirect()->to('/profile')->with('error', 'Veuillez d\'abord choisir un objectif.');
        }

        $goal = $profile['goal'];

        // Calcul IMC
        $heightM = $profile['height'] / 100;
        $imc     = round($profile['weight'] / ($heightM * $heightM), 1);

        // Récupérer régimes et activités selon objectif
        $regimes    = (new RegimeModel())->getByGoal($goal);
        $activities = (new SportActivityModel())->getByGoal($goal);

        // Portefeuille
        $walletModel = new WalletModel();
        $walletModel->initForUser($userId);
        $wallet = $walletModel->getByUser($userId);

        // Commandes déjà passées
        $orderModel   = new OrderModel();
        $orderedIds   = array_column(
            (new OrderModel())->where('user_id', $userId)->findAll(),
            'regime_id'
        );

        // Libellé objectif
        $goalLabels = [
            'gain'  => 'Augmenter le poids',
            'lose'  => 'Réduire le poids',
            'ideal' => 'Atteindre l\'IMC idéal',
        ];

        return view('user/suggestions', [
            'profile'     => $profile,
            'imc'         => $imc,
            'goal'        => $goal,
            'goalLabel'   => $goalLabels[$goal],
            'regimes'     => $regimes,
            'activities'  => $activities,
            'wallet'      => $wallet,
            'orderedIds'  => $orderedIds,
        ]);
    }

    public function buy()
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId    = session()->get('user_id');
        $regimeId  = (int) $this->request->getPost('regime_id');

        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find($regimeId);

        if (!$regime) return redirect()->to('/suggestions')->with('error', 'Régime introuvable.');

        $orderModel  = new OrderModel();
        if ($orderModel->hasOrdered($userId, $regimeId)) {
            return redirect()->to('/suggestions')->with('error', 'Vous avez déjà acheté ce régime.');
        }

        $walletModel = new WalletModel();
        $wallet      = $walletModel->getByUser($userId);

        // Appliquer remise Gold -15%
        $price = $regime['price'];
        if ($wallet['is_gold']) $price = round($price * 0.85, 2);

        if ($wallet['balance'] < $price) {
            return redirect()->to('/suggestions')->with('error', 'Solde insuffisant. Rechargez votre portefeuille.');
        }

        // Déduire du portefeuille
        $walletModel->update($wallet['id'], [
            'balance' => $wallet['balance'] - $price,
        ]);

        // Enregistrer la commande
        $orderModel->insert([
            'user_id'    => $userId,
            'regime_id'  => $regimeId,
            'price_paid' => $price,
        ]);

        return redirect()->to('/suggestions')->with('success', 'Régime acheté avec succès !');
    }
}