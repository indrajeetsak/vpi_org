<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FrontModel;

class Fronts extends BaseController
{
    public function index()
    {
        $frontModel = new FrontModel();
        $fronts = $frontModel->getAllFronts();

        // Convert array of fronts to a newline-separated string
        $frontNames = array_column($fronts, 'name');
        $data['frontsText'] = implode("\n", $frontNames);
        
        return view('admin/manage_fronts', $data);
    }

    public function update()
    {
        $frontsText = $this->request->getPost('fronts_text');
        
        // Split by newline and trim
        $newFronts = array_filter(array_map('trim', explode("\n", $frontsText)));
        // Remove duplicates in input
        $newFronts = array_unique($newFronts);
        
        $frontModel = new FrontModel();
        $existingFronts = $frontModel->findAll();
        $existingNames = array_column($existingFronts, 'name');

        // Determine added and removed fronts
        $toAdd = array_diff($newFronts, $existingNames);
        $toRemove = array_diff($existingNames, $newFronts);

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Add new fronts
            if (!empty($toAdd)) {
                $insertData = [];
                foreach ($toAdd as $name) {
                    $insertData[] = ['name' => $name];
                }
                $frontModel->insertBatch($insertData);
            }

            // Remove deleted fronts
            if (!empty($toRemove)) {
                $frontModel->whereIn('name', $toRemove)->delete();
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to update fronts.');
            }

            return redirect()->back()->with('success', 'Fronts updated successfully.');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
