<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SportActivityModel;

class Activities extends BaseController
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

        return view('admin/activities/index', [
            'title'      => 'Activités sportives',
            'activities' => (new SportActivityModel())->orderBy('goal')->findAll(),
        ]);
    }

    public function store()
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'name'               => 'required|min_length[3]|max_length[100]',
            'description'        => 'required',
            'goal'               => 'required|in_list[gain,lose,ideal]',
            'duration_minutes'   => 'required|integer|greater_than[0]',
            'frequency_per_week' => 'required|integer|greater_than[0]|less_than[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/activities')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'create');
        }

        (new SportActivityModel())->insert([
            'name'               => $this->request->getPost('name'),
            'description'        => $this->request->getPost('description'),
            'goal'               => $this->request->getPost('goal'),
            'duration_minutes'   => $this->request->getPost('duration_minutes'),
            'frequency_per_week' => $this->request->getPost('frequency_per_week'),
        ]);

        return redirect()->to('/admin/activities')->with('success', 'Activité créée avec succès.');
    }

    public function update(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;

        $rules = [
            'name'               => 'required|min_length[3]|max_length[100]',
            'description'        => 'required',
            'goal'               => 'required|in_list[gain,lose,ideal]',
            'duration_minutes'   => 'required|integer|greater_than[0]',
            'frequency_per_week' => 'required|integer|greater_than[0]|less_than[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/activities')
                             ->with('errors', $this->validator->getErrors())
                             ->with('modal', 'edit_' . $id);
        }

        (new SportActivityModel())->update($id, [
            'name'               => $this->request->getPost('name'),
            'description'        => $this->request->getPost('description'),
            'goal'               => $this->request->getPost('goal'),
            'duration_minutes'   => $this->request->getPost('duration_minutes'),
            'frequency_per_week' => $this->request->getPost('frequency_per_week'),
        ]);

        return redirect()->to('/admin/activities')->with('success', 'Activité mise à jour.');
    }

    public function delete(int $id)
    {
        if ($r = $this->checkAdmin()) return $r;
        (new SportActivityModel())->delete($id);
        return redirect()->to('/admin/activities')->with('success', 'Activité supprimée.');
    }
}