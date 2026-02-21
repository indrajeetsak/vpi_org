<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DistrictModel;
use App\Models\StateModel;

class Locations extends BaseController
{
    public function addDistricts()
    {
        $stateId = $this->request->getJsonVar('state_id');
        $districtNames = $this->request->getJsonVar('names');

        if (empty($stateId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'State ID is required.']);
        }

        $districtModel = new DistrictModel();

        // If no district names provided, delete all districts for this state
        if (empty($districtNames)) {
            $districtModel->where('state_id', $stateId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All districts removed.']);
        }

        // Sanitize district names
        $sanitizedDistrictNames = array_map('trim', $districtNames);
        $sanitizedDistrictNames = array_filter($sanitizedDistrictNames); // Remove empty names

        if (empty($sanitizedDistrictNames)) {
            $districtModel->where('state_id', $stateId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All districts removed.']);
        }

        // Get existing districts for this state
        $existingDistricts = $districtModel->where('state_id', $stateId)->findAll();
        $existingDistrictNames = array_map(function($d) { return $d['name']; }, $existingDistricts);

        // Find districts to add (in submitted list but not in database)
        $districtsToAdd = array_diff($sanitizedDistrictNames, $existingDistrictNames);

        // Find districts to delete (in database but not in submitted list)
        $districtsToDelete = array_diff($existingDistrictNames, $sanitizedDistrictNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new districts
        if (!empty($districtsToAdd)) {
            $dataToInsert = [];
            foreach ($districtsToAdd as $name) {
                $dataToInsert[] = [
                    'state_id' => $stateId,
                    'name' => $name,
                    'added_by' => session()->get('admin_id'),
                    'added_by_name' => session()->get('name'),
                ];
            }
            if ($districtModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed districts
        if (!empty($districtsToDelete)) {
            $districtModel->where('state_id', $stateId)->whereIn('name', $districtsToDelete)->delete();
            $deletedCount = count($districtsToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount district(s) added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount district(s) deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }

    public function getDistrictsByState($stateId)
    {
        $districtModel = new \App\Models\DistrictModel();
        $districts = $districtModel->where('state_id', $stateId)->orderBy('name', 'ASC')->findAll();

        if (!empty($districts)) {
            return $this->response->setJSON(['success' => true, 'districts' => $districts]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No districts found for this state.']);
        }
    }

    public function getBlocksByDistrict($districtId)
    {
        $blockModel = new \App\Models\BlockModel();
        $blocks = $blockModel->where('district_id', $districtId)->orderBy('name', 'ASC')->findAll();

        if (!empty($blocks)) {
            return $this->response->setJSON(['success' => true, 'blocks' => $blocks]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No blocks found for this district.']);
        }
    }

    public function addBlocks()
    {
        $districtId = $this->request->getJsonVar('district_id');
        $blockNames = $this->request->getJsonVar('names');

        if (empty($districtId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'District ID is required.']);
        }

        $blockModel = new \App\Models\BlockModel();

        // If no block names provided, delete all blocks for this district
        if (empty($blockNames)) {
            $blockModel->where('district_id', $districtId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All blocks removed.']);
        }

        // Sanitize block names
        $sanitizedBlockNames = array_map('trim', $blockNames);
        $sanitizedBlockNames = array_filter($sanitizedBlockNames); // Remove empty names

        if (empty($sanitizedBlockNames)) {
            $blockModel->where('district_id', $districtId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All blocks removed.']);
        }

        // Get existing blocks for this district
        $existingBlocks = $blockModel->where('district_id', $districtId)->findAll();
        $existingBlockNames = array_map(function($b) { return $b['name']; }, $existingBlocks);

        // Find blocks to add (in submitted list but not in database)
        $blocksToAdd = array_diff($sanitizedBlockNames, $existingBlockNames);

        // Find blocks to delete (in database but not in submitted list)
        $blocksToDelete = array_diff($existingBlockNames, $sanitizedBlockNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new blocks
        if (!empty($blocksToAdd)) {
            $dataToInsert = [];
            foreach ($blocksToAdd as $name) {
                $dataToInsert[] = [
                    'district_id' => $districtId,
                    'name' => $name,
                    'added_by' => session()->get('admin_id'),
                    'added_by_name' => session()->get('name'),
                ];
            }
            if ($blockModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed blocks
        if (!empty($blocksToDelete)) {
            $blockModel->where('district_id', $districtId)->whereIn('name', $blocksToDelete)->delete();
            $deletedCount = count($blocksToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount block(s) added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount block(s) deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }

    public function getMlaAreasByDistrict($districtId)
    {
        $mlaAreaModel = new \App\Models\MlaAreaModel();
        $mlaAreas = $mlaAreaModel->where('district_id', $districtId)->orderBy('name', 'ASC')->findAll();

        if (!empty($mlaAreas)) {
            return $this->response->setJSON(['success' => true, 'mla_areas' => $mlaAreas]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No MLA areas found for this district.']);
        }
    }

    public function addMlaAreas()
    {
        $districtId = $this->request->getJsonVar('district_id');
        $mlaAreaNames = $this->request->getJsonVar('names');

        if (empty($districtId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'District ID is required.']);
        }

        $mlaAreaModel = new \App\Models\MlaAreaModel();

        // If no MLA area names provided, delete all MLA areas for this district
        if (empty($mlaAreaNames)) {
            $mlaAreaModel->where('district_id', $districtId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All MLA areas removed.']);
        }

        // Sanitize MLA area names
        $sanitizedMlaAreaNames = array_map('trim', $mlaAreaNames);
        $sanitizedMlaAreaNames = array_filter($sanitizedMlaAreaNames); // Remove empty names

        if (empty($sanitizedMlaAreaNames)) {
            $mlaAreaModel->where('district_id', $districtId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All MLA areas removed.']);
        }

        // Get existing MLA areas for this district
        $existingMlaAreas = $mlaAreaModel->where('district_id', $districtId)->findAll();
        $existingMlaAreaNames = array_map(function($m) { return $m['name']; }, $existingMlaAreas);

        // Find MLA areas to add (in submitted list but not in database)
        $mlaAreasToAdd = array_diff($sanitizedMlaAreaNames, $existingMlaAreaNames);

        // Find MLA areas to delete (in database but not in submitted list)
        $mlaAreasToDelete = array_diff($existingMlaAreaNames, $sanitizedMlaAreaNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new MLA areas
        if (!empty($mlaAreasToAdd)) {
            $dataToInsert = [];
            foreach ($mlaAreasToAdd as $name) {
                $dataToInsert[] = [
                    'district_id' => $districtId,
                    'name' => $name,
                    'added_by' => session()->get('admin_id'),
                    'added_by_name' => session()->get('name'),
                ];
            }
            if ($mlaAreaModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed MLA areas
        if (!empty($mlaAreasToDelete)) {
            $mlaAreaModel->where('district_id', $districtId)->whereIn('name', $mlaAreasToDelete)->delete();
            $deletedCount = count($mlaAreasToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount MLA area(s) added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount MLA area(s) deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }

    public function getCirclesByBlock($blockId)
    {
        $circleModel = new \App\Models\CircleModel();
        $circles = $circleModel->where('block_id', $blockId)->orderBy('name', 'ASC')->findAll();

        if (!empty($circles)) {
            return $this->response->setJSON(['success' => true, 'circles' => $circles]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No circles found for this block.']);
        }
    }

    public function getSectorsByBlock($blockId)
    {
        $sectorModel = new \App\Models\SectorModel();
        $sectors = $sectorModel->where('block_id', $blockId)->orderBy('name', 'ASC')->findAll();

        if (!empty($sectors)) {
            return $this->response->setJSON(['success' => true, 'sectors' => $sectors]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No sectors found for this block.']);
        }
    }

    public function addSectors()
    {
        $blockId = $this->request->getJsonVar('block_id');
        $sectorNames = $this->request->getJsonVar('names');

        if (empty($blockId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Block ID is required.']);
        }

        $sectorModel = new \App\Models\SectorModel();

        // If no sector names provided, delete all sectors for this block
        if (empty($sectorNames)) {
            $sectorModel->where('block_id', $blockId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All sectors removed.']);
        }

        // Sanitize sector names
        $sanitizedSectorNames = array_map('trim', $sectorNames);
        $sanitizedSectorNames = array_filter($sanitizedSectorNames); // Remove empty names

        if (empty($sanitizedSectorNames)) {
            $sectorModel->where('block_id', $blockId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All sectors removed.']);
        }

        // Get existing sectors for this block
        $existingSectors = $sectorModel->where('block_id', $blockId)->findAll();
        $existingSectorNames = array_map(function($c) { return $c['name']; }, $existingSectors);

        // Find sectors to add (in submitted list but not in database)
        $sectorsToAdd = array_diff($sanitizedSectorNames, $existingSectorNames);

        // Find sectors to delete (in database but not in submitted list)
        $sectorsToDelete = array_diff($existingSectorNames, $sanitizedSectorNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new sectors
        if (!empty($sectorsToAdd)) {
            $dataToInsert = [];
            foreach ($sectorsToAdd as $name) {
                $dataToInsert[] = [
                    'block_id' => $blockId,
                    'name' => $name,
                    'added_by' => session()->get('admin_id'),
                    'added_by_name' => session()->get('name'),
                ];
            }
            if ($sectorModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed sectors
        if (!empty($sectorsToDelete)) {
            $sectorModel->where('block_id', $blockId)->whereIn('name', $sectorsToDelete)->delete();
            $deletedCount = count($sectorsToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount sector(s) added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount sector(s) deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }
    public function getVillagesBySector($sectorId)
    {
        $villageModel = new \App\Models\VillageModel();
        $villages = $villageModel->where('sector_id', $sectorId)->orderBy('name', 'ASC')->findAll();

        if (!empty($villages)) {
            return $this->response->setJSON(['success' => true, 'villages' => $villages]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No villages found for this sector.']);
        }
    }

    public function addVillages()
    {
        $sectorId = $this->request->getJsonVar('sector_id');
        $villageNames = $this->request->getJsonVar('names');

        if (empty($sectorId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sector ID is required.']);
        }

        $villageModel = new \App\Models\VillageModel();

        // If no village names provided, delete all villages for this sector
        if (empty($villageNames)) {
            $villageModel->where('sector_id', $sectorId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All villages removed.']);
        }

        // Sanitize village names
        $sanitizedVillageNames = array_map('trim', $villageNames);
        $sanitizedVillageNames = array_filter($sanitizedVillageNames); // Remove empty names

        if (empty($sanitizedVillageNames)) {
            $villageModel->where('sector_id', $sectorId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All villages removed.']);
        }

        // Get existing villages for this sector
        $existingVillages = $villageModel->where('sector_id', $sectorId)->findAll();
        $existingVillageNames = array_map(function($v) { return $v['name']; }, $existingVillages);

        // Find villages to add (in submitted list but not in database)
        $villagesToAdd = array_diff($sanitizedVillageNames, $existingVillageNames);

        // Find villages to delete (in database but not in submitted list)
        $villagesToDelete = array_diff($existingVillageNames, $sanitizedVillageNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new villages
        if (!empty($villagesToAdd)) {
            $dataToInsert = [];
            foreach ($villagesToAdd as $name) {
                $dataToInsert[] = [
                    'sector_id' => $sectorId,
                    'name' => $name,
                    'added_by' => session()->get('admin_id'),
                    'added_by_name' => session()->get('name'),
                ];
            }
            if ($villageModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed villages
        if (!empty($villagesToDelete)) {
            $villageModel->where('sector_id', $sectorId)->whereIn('name', $villagesToDelete)->delete();
            $deletedCount = count($villagesToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount village(s) added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount village(s) deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }
    public function getAllCircles()
    {
        $circleModel = new \App\Models\CircleModel();
        $circles = $circleModel->orderBy('name', 'ASC')->findAll();

        if (!empty($circles)) {
            return $this->response->setJSON(['success' => true, 'circles' => $circles]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No circles found.']);
        }
    }
}