<?php
namespace App\Controllers;

use App\Models\RegimeModel;
use App\Models\SportActivityModel;
use App\Models\OrderModel;
use App\Models\UserProfileModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ExportPdf extends BaseController
{
    public function preview(int $regimeId)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId = session()->get('user_id');

        // Vérifier que le régime est acheté
        $orderModel = new OrderModel();
        if (!$orderModel->hasOrdered($userId, $regimeId)) {
            return redirect()->to('/suggestions')->with('error', 'Vous devez acheter ce régime pour l\'exporter.');
        }

        $regime     = (new RegimeModel())->find($regimeId);
        $activities = (new SportActivityModel())->getByGoal($regime['goal']);
        $profile    = (new UserProfileModel())->where('user_id', $userId)->first();

        $heightM = $profile['height'] / 100;
        $imc     = round($profile['weight'] / ($heightM * $heightM), 1);

        $goalLabels = [
            'gain'  => 'Augmenter le poids',
            'lose'  => 'Réduire le poids',
            'ideal' => 'Atteindre l\'IMC idéal',
        ];

        return view('user/pdf_preview', [
            'regime'     => $regime,
            'activities' => $activities,
            'profile'    => $profile,
            'imc'        => $imc,
            'goalLabel'  => $goalLabels[$regime['goal']],
            'username'   => session()->get('username'),
        ]);
    }

    public function generate(int $regimeId)
    {
        if (!session()->get('logged_in')) return redirect()->to('/login');

        $userId = session()->get('user_id');

        // Vérifier achat
        $orderModel = new OrderModel();
        if (!$orderModel->hasOrdered($userId, $regimeId)) {
            return redirect()->to('/suggestions')->with('error', 'Accès non autorisé.');
        }

        $regime     = (new RegimeModel())->find($regimeId);
        $activities = (new SportActivityModel())->getByGoal($regime['goal']);
        $profile    = (new UserProfileModel())->where('user_id', $userId)->first();

        $heightM = $profile['height'] / 100;
        $imc     = round($profile['weight'] / ($heightM * $heightM), 1);

        $goalLabels = [
            'gain'  => 'Augmenter le poids',
            'lose'  => 'Réduire le poids',
            'ideal' => 'Atteindre l\'IMC idéal',
        ];

        // Générer le HTML pour le PDF
        $html = view('user/pdf_template', [
            'regime'     => $regime,
            'activities' => $activities,
            'profile'    => $profile,
            'imc'        => $imc,
            'goalLabel'  => $goalLabels[$regime['goal']],
            'username'   => session()->get('username'),
        ]);

        // Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'regimepro-' . strtolower(str_replace(' ', '-', $regime['name'])) . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}