<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Settings extends BaseController
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

        return view('admin/settings/index', [
            'title'    => 'Paramètres',
            'settings' => (new SettingModel())->getAllKeyed(),
        ]);
    }

    public function update()
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'gold_price'    => 'required|decimal|greater_than[0]',
            'gold_discount' => 'required|integer|greater_than[0]|less_than_equal_to[100]',
            'max_recharge'  => 'required|decimal|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/settings')
                             ->with('errors', $this->validator->getErrors());
        }

        $model = new SettingModel();
        $model->setValue('gold_price',    $this->request->getPost('gold_price'));
        $model->setValue('gold_discount', $this->request->getPost('gold_discount'));
        $model->setValue('max_recharge',  $this->request->getPost('max_recharge'));

        return redirect()->to('/admin/settings')->with('success', 'Paramètres mis à jour.');
    }

    public function store()
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'key_name'    => 'required|is_unique[settings.key_name]',
            'value'       => 'required',
            'label'       => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/settings')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'create');
        }

        (new SettingModel())->insert([
            'key_name'    => $this->request->getPost('key_name'),
            'value'       => $this->request->getPost('value'),
            'label'       => $this->request->getPost('label'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/settings')->with('success', 'Paramètre ajouté.');
    }

    public function delete(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;
        (new SettingModel())->delete($id);
        return redirect()->to('/admin/settings')->with('success', 'Paramètre supprimé.');
    }
}