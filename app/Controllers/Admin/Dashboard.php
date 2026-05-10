<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\RegimeModel;
use App\Models\WalletModel;
use App\Models\WalletCodeModel;
use App\Models\UserProfileModel;
use App\Models\SportActivityModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();

        // ── KPIs globaux ─────────────────────────────────────────
        $totalUsers   = (new UserModel())->where('is_admin', false)->countAllResults();
        $totalOrders  = (new OrderModel())->countAllResults();
        $totalRevenue = $db->query("SELECT COALESCE(SUM(price_paid),0) as t FROM orders")->getRow()->t;
        $goldUsers    = $db->query("SELECT COUNT(*) as t FROM wallets WHERE is_gold = 1")->getRow()->t;
        $usedCodes    = (new WalletCodeModel())->where('is_used', true)->countAllResults();
        $totalCodes   = (new WalletCodeModel())->countAllResults();

        // ── Objectifs répartition ────────────────────────────────
        $goalStats = $db->query("
            SELECT goal, COUNT(*) as total
            FROM user_profiles
            WHERE goal IS NOT NULL
            GROUP BY goal
        ")->getResultArray();

        // ── IMC moyen ────────────────────────────────────────────
        $imcData = $db->query("
            SELECT
                ROUND(AVG(weight / POW(height/100, 2)), 1) as avg_imc,
                SUM(CASE WHEN weight/POW(height/100,2) < 18.5 THEN 1 ELSE 0 END) as under,
                SUM(CASE WHEN weight/POW(height/100,2) BETWEEN 18.5 AND 24.9 THEN 1 ELSE 0 END) as normal,
                SUM(CASE WHEN weight/POW(height/100,2) BETWEEN 25 AND 29.9 THEN 1 ELSE 0 END) as `over`,
                SUM(CASE WHEN weight/POW(height/100,2) >= 30 THEN 1 ELSE 0 END) as obese
            FROM user_profiles
            WHERE height > 0 AND weight > 0
        ")->getRow();

        // ── Régimes les plus vendus ──────────────────────────────
        $topRegimes = $db->query("
            SELECT r.name, COUNT(o.id) as sales, SUM(o.price_paid) as revenue
            FROM orders o
            JOIN regimes r ON r.id = o.regime_id
            GROUP BY o.regime_id
            ORDER BY sales DESC
            LIMIT 5
        ")->getResultArray();

        // ── Revenus par mois (6 derniers mois) ───────────────────
        $monthlyRevenue = $db->query("
            SELECT DATE_FORMAT(ordered_at, '%b %Y') as month,
                   COUNT(*) as orders,
                   SUM(price_paid) as revenue
            FROM orders
            WHERE ordered_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(ordered_at, '%Y-%m')
            ORDER BY MIN(ordered_at) ASC
        ")->getResultArray();

        // ── Inscriptions par mois ────────────────────────────────
        $monthlyUsers = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month,
                   COUNT(*) as total
            FROM users
            WHERE is_admin = 0
              AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY MIN(created_at) ASC
        ")->getResultArray();

        // ── Derniers utilisateurs inscrits ───────────────────────
        $recentUsers = $db->query("
            SELECT u.username, u.email, u.created_at,
                   p.goal, w.balance, w.is_gold
            FROM users u
            LEFT JOIN user_profiles p ON p.user_id = u.id
            LEFT JOIN wallets w ON w.user_id = u.id
            WHERE u.is_admin = 0
            ORDER BY u.created_at DESC
            LIMIT 8
        ")->getResultArray();

        return view('admin/dashboard', compact(
            'totalUsers', 'totalOrders', 'totalRevenue', 'goldUsers',
            'usedCodes', 'totalCodes', 'goalStats', 'imcData',
            'topRegimes', 'monthlyRevenue', 'monthlyUsers', 'recentUsers'
        ));
    }
}