<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\LevelModel;
use App\Models\DistrictModel;
use App\Models\BlockModel;
use App\Models\SectorModel;
use App\Models\MlaAreaModel;
use App\Models\OrganModel;
use CodeIgniter\API\ResponseTrait;

class CommitteeController extends BaseController
{
    use ResponseTrait;

    protected $userModel;
    protected $stateModel;
    protected $levelModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->stateModel = new StateModel();
        $this->levelModel = new LevelModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['title'] = 'Committee Details';
        // Load States and Levels for the initial dropdowns
        $data['states'] = $this->stateModel->orderBy('name', 'ASC')->findAll();
        $data['levels'] = $this->levelModel->orderBy('id', 'ASC')->findAll();
        
        return view('committee_details', $data);
    }

    public function getMembers()
    {
        $request = service('request');
        // Handle JSON or POST data
        $filters = $request->getJSON(true) ?? $request->getPost();

        if (empty($filters['level_id'])) {
            return $this->fail('Level is required', 400);
        }

        $levelId = $filters['level_id'];
        $stateId = $filters['state_id'] ?? null;

        // Determine Post Table based on standardized level ranges
        if ($levelId >= 1 && $levelId <= 5) {
            $postTable = 'action_level_posts';
        } elseif ($levelId >= 6 && $levelId <= 10) {
            $postTable = 'constituency_level_posts';
        } elseif ($levelId >= 11 && $levelId <= 15) {
            $postTable = 'governing_level_posts';
        } elseif ($levelId >= 16 && $levelId <= 17) {
            $postTable = 'managerial_level_posts';
        }

        if (!$postTable) {
             return $this->respond([]);
        }

        // 2. Fetch All Posts for this category (to show empty posts too)
        $db = \Config\Database::connect();
        $posts = $db->table($postTable)->orderBy('id', 'ASC')->get()->getResultArray();

        // 3. Fetch Appointments matching filters
        $builder = $db->table('appointments');
        $builder->select('users.first_name, users.last_name, users.photo, appointments.post_id');
        $builder->join('users', 'users.id = appointments.user_id');
        $builder->where('appointments.level_id', $levelId);
        $builder->where('appointments.status', 'approved');
        
        // Filter by Appointment Location
        if (!empty($stateId)) $builder->where('appointments.state_id', $stateId);
        
        if (!empty($filters['district_id'])) $builder->where('appointments.district_id', $filters['district_id']);
        if (!empty($filters['block_id'])) $builder->where('appointments.block_id', $filters['block_id']);
        if (!empty($filters['mla_area_id'])) $builder->where('appointments.mla_area_id', $filters['mla_area_id']);
        if (!empty($filters['sector_id'])) $builder->where('appointments.sector_id', $filters['sector_id']);
        // Add other location filters if needed (ls_id etc)

        $appointments = $builder->get()->getResultArray();

        // 4. Map Users to Posts
        $appointmentMap = [];
        foreach ($appointments as $appt) {
            $appointmentMap[$appt['post_id']] = $appt;
        }

        $result = [];
        foreach ($posts as $post) {
            $member = [
                'post_name' => $post['name'],
                'full_name' => 'Not appointed',
                'photo_url' => 'No image'
            ];

            if (isset($appointmentMap[$post['id']])) {
                $u = $appointmentMap[$post['id']];
                $member['full_name'] = trim($u['first_name'] . ' ' . ($u['last_name'] ?? ''));
                if (!empty($u['photo'])) {
                     $member['photo_url'] = base_url('uploads/photos/' . $u['photo']);
                }
            }
            $result[] = $member;
        }

        return $this->respond($result);
    }
    public function getSectors($blockId)
    {
        $sectorModel = new SectorModel();
        $sectors = $sectorModel->where('block_id', $blockId)->orderBy('name', 'ASC')->findAll();
        return $this->respond($sectors);
    }
}
