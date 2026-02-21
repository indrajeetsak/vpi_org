<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SectorModel;
use App\Models\StateModel;
use App\Models\DistrictModel;
use App\Models\BlockModel;

class Circles extends BaseController
{
    public function index()
    {
        $stateModel = new StateModel();
        $data['all_states'] = $stateModel->findAll();
        
        $circleModel = new \App\Models\CircleModel();
        $data['circles'] = $circleModel->orderBy('id', 'ASC')->findAll();

        return view('admin/manage_circles', $data);
    }


    public function getSectorsForCircle()
    {
        $blockId = $this->request->getVar('block_id');
        $circleId = $this->request->getVar('circle');

        if (!$blockId || !$circleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Block and Circle are required.']);
        }

        $sectorModel = new SectorModel();
        
        // Fetch all sectors for the block
        // We need to return sectors that are EITHER assigned to this circle OR not assigned to any circle
        
        $sectors = $sectorModel->where('block_id', $blockId)
                               ->groupStart()
                                   ->where('circle_id', null)
                                   ->orWhere('circle_id', 0)
                                   ->orWhere('circle_id', $circleId)
                               ->groupEnd()
                               ->orderBy('name', 'ASC')
                               ->findAll();

        // Add circle_id back to response for frontend check
        foreach ($sectors as &$sector) {
            $sector['circle'] = $sector['circle_id']; // For frontend compatibility if needed, or update frontend to use circle_id
        }

        return $this->response->setJSON(['success' => true, 'sectors' => $sectors]);
    }

    public function updateCircleAssignment()
    {
        $blockId = $this->request->getJsonVar('block_id');
        $circleId = $this->request->getJsonVar('circle');
        $sectorIds = $this->request->getJsonVar('sector_ids') ?? []; // Array of selected sector IDs

        if (!$blockId || !$circleId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Block and Circle are required.']);
        }

        $sectorModel = new SectorModel();
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Assign selected sectors to this circle
            if (!empty($sectorIds)) {
                $sectorModel->whereIn('id', $sectorIds)
                            ->set(['circle_id' => $circleId])
                            ->update();
            }

            // 2. Unassign sectors that were previously in this circle but are NOT in the submitted list
            // Only for this block
            $builder = $sectorModel->builder();
            $builder->where('block_id', $blockId)
                    ->where('circle_id', $circleId);
            
            if (!empty($sectorIds)) {
                $builder->whereNotIn('id', $sectorIds);
            }
            
            $builder->set(['circle_id' => null])
                    ->update();

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Transaction failed.']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Circle assignment updated successfully.']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
