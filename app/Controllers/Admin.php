<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\FourLsModel;
use App\Models\AppointmentModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $stateModel; // Added
    protected $fourLsModel;
    protected $adminModel;

    public function __construct()
    {
        helper(['url']); // Added for site_url in views
        $this->userModel = new UserModel();
        $this->stateModel = new StateModel();
        $this->fourLsModel = new FourLsModel();
        $this->adminModel = new \App\Models\AdminModel();
    }    public function index()
    {
        // Fetch stats for admin dashboard
        $data['pendingApplicationsCount'] = $this->userModel
            ->join('appointments', 'appointments.user_id = users.id', 'inner')
            ->where('appointments.status', 'pending')
            ->countAllResults();
        $data['activeOfficeBearersCount'] = $this->userModel
            ->join('appointments', 'appointments.user_id = users.id', 'inner')
            ->join('admins', 'admins.mobile = users.mobile', 'left')
            ->where('appointments.status', 'approved')
            ->where('admins.id IS NULL')
            ->countAllResults();
        $data['all_states'] = $this->stateModel->orderBy('name', 'ASC')->findAll();

        // Recently Approved Summary
        $sevenDaysAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $recentlyApprovedCount = $this->userModel
            ->join('appointments', 'appointments.user_id = users.id', 'inner')
            ->where('appointments.status', 'approved')
            ->where('appointments.updated_at >=', $sevenDaysAgo)
            ->countAllResults();
        $data['recentlyApprovedSummary'] = $recentlyApprovedCount > 0 ? $recentlyApprovedCount . ' approved in the last 7 days.' : 'No recent approvals.';

        // Constituted Committee Counts
        $appointmentModel = new AppointmentModel();
        
        $countConstituted = function($levelId, $locationField) use ($appointmentModel) {
            $ids = $appointmentModel
                ->where('level_id', $levelId)
                ->where('status', 'approved')
                ->where($locationField . ' IS NOT NULL')
                ->distinct()
                ->findColumn($locationField);

            return $ids ? count(array_filter(array_unique($ids), fn($v) => $v !== null)) : 0;
        };

        // 1. State Level (ID 11)
        $data['constitutedStateCommittees'] = $countConstituted(11, 'state_id');
            
        // 2. District Level (ID 16)
        $data['constitutedDistrictCommittees'] = $countConstituted(16, 'district_id');

        // 3. MLA Level (ID 6)
        $data['constitutedMlaCommittees'] = $countConstituted(6, 'mla_area_id');

        // 4. Block Level (ID 5)
        $data['constitutedBlockCommittees'] = $countConstituted(5, 'block_id');

        // 5. MP Level (ID 7)
        $data['constitutedMpCommittees'] = $countConstituted(7, 'ls_id');

        // 6. Sector Level (ID 3)
        $data['constitutedSectorCommittees'] = $countConstituted(3, 'sector_id');

        // 7. Circle Level (ID 4)
        $data['constitutedCircleCommittees'] = $countConstituted(4, 'circle_id');

        // 8. Village/Ward Level (ID 2)
        $data['constitutedVillageCommittees'] = $countConstituted(2, 'village_id');

        // Payment Success Rate (Placeholder)
        // This would require a PaymentModel and logic to calculate the rate.
        // For example:
        // $paymentModel = new \App\Models\PaymentModel(); // Assuming you have one
        // $successfulPayments = $paymentModel->where('payment_status', 'success')->countAllResults();
        // $totalPaymentsAttempted = $paymentModel->countAllResults();
        // $data['paymentSuccessRate'] = $totalPaymentsAttempted > 0 ? round(($successfulPayments / $totalPaymentsAttempted) * 100, 2) . '%' : 'Data N/A';
        $data['paymentSuccessRate'] = 'Data N/A'; // Keep as placeholder if PaymentModel is not ready
        return view('admin/dashboard', $data); // View: admin/dashboard.php
    }

    /**
     * Show constituted committees for a given level with full location breadcrumb
     */
    public function committeeDetails($levelId)
    {
        $levelLabels = [
            11 => 'State Level',
            16 => 'District Level',
            6  => 'MLA Level',
            5  => 'Block/Town Level',
            7  => 'MP Level',
            4  => 'Circle Level',
            3  => 'Sector Level',
            2  => 'Village/Ward Level',
        ];

        if (!isset($levelLabels[$levelId])) {
            session()->setFlashdata('error', 'Invalid level.');
            return redirect()->to('admin/dashboard');
        }

        $db = \Config\Database::connect();
        $committees = [];

        if ($levelId == 11) {
            // State Level: just state
            $q = $db->query("
                SELECT s.name AS loc1, NULL AS loc2, NULL AS loc3, NULL AS loc4,
                       COUNT(a.id) AS member_count, a.state_id AS location_id
                FROM appointments a
                INNER JOIN states s ON s.id = a.state_id
                WHERE a.level_id = 11 AND a.status = 'approved' AND a.state_id IS NOT NULL
                GROUP BY a.state_id, s.name
                ORDER BY s.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 16) {
            // District Level: district, state
            $q = $db->query("
                SELECT d.name AS loc1, s.name AS loc2, NULL AS loc3, NULL AS loc4,
                       COUNT(a.id) AS member_count, a.district_id AS location_id
                FROM appointments a
                INNER JOIN districts d ON d.id = a.district_id
                INNER JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 16 AND a.status = 'approved' AND a.district_id IS NOT NULL
                GROUP BY a.district_id, d.name, s.name
                ORDER BY s.name ASC, d.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 6) {
            // MLA Level: mla_area, district, state
            $q = $db->query("
                SELECT COALESCE(m.name, CONCAT(COALESCE(d.name, 'Unknown District'), ' Area')) AS loc1,
                       d.name AS loc2, s.name AS loc3, NULL AS loc4,
                       COUNT(a.id) AS member_count, a.mla_area_id AS location_id
                FROM appointments a
                LEFT JOIN mla_area m ON m.id = a.mla_area_id
                LEFT JOIN districts d ON d.id = COALESCE(m.district_id, a.district_id)
                LEFT JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 6 AND a.status = 'approved' AND a.mla_area_id IS NOT NULL
                GROUP BY a.mla_area_id, m.name, d.name, s.name
                ORDER BY s.name ASC, d.name ASC, m.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 5) {
            // Block Level: block, district, state
            $q = $db->query("
                SELECT COALESCE(b.name, CONCAT(COALESCE(d.name, 'Unknown District'), ' Block')) AS loc1,
                       d.name AS loc2, s.name AS loc3, NULL AS loc4,
                       COUNT(a.id) AS member_count, a.block_id AS location_id
                FROM appointments a
                LEFT JOIN blocks b ON b.id = a.block_id
                LEFT JOIN districts d ON d.id = COALESCE(b.district_id, a.district_id)
                LEFT JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 5 AND a.status = 'approved' AND a.block_id IS NOT NULL
                GROUP BY a.block_id, b.name, d.name, s.name
                ORDER BY s.name ASC, d.name ASC, b.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 7) {
            // MP (1LS) Level: mp constituency, state
            $q = $db->query("
                SELECT COALESCE(l.name, CONCAT(COALESCE(s.name, 'Unknown State'), ' LS')) AS loc1,
                       s.name AS loc2, NULL AS loc3, NULL AS loc4,
                       COUNT(a.id) AS member_count, a.ls_id AS location_id
                FROM appointments a
                LEFT JOIN ls l ON l.id = a.ls_id
                LEFT JOIN states s ON s.id = COALESCE(l.state_id, a.state_id)
                WHERE a.level_id = 7 AND a.status = 'approved' AND a.ls_id IS NOT NULL
                GROUP BY a.ls_id, l.name, s.name
                ORDER BY s.name ASC, l.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 3) {
            // Sector Level: sector, block, district, state
            $q = $db->query("
                SELECT COALESCE(sc.name, CONCAT(COALESCE(b.name, 'Unknown Block'), ' Sector')) AS loc1,
                       b.name AS loc2, d.name AS loc3, s.name AS loc4,
                       COUNT(a.id) AS member_count, a.sector_id AS location_id
                FROM appointments a
                LEFT JOIN sectors sc ON sc.id = a.sector_id
                LEFT JOIN blocks b ON b.id = COALESCE(sc.block_id, a.block_id)
                LEFT JOIN districts d ON d.id = COALESCE(b.district_id, a.district_id)
                LEFT JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 3 AND a.status = 'approved' AND a.sector_id IS NOT NULL
                GROUP BY a.sector_id, sc.name, b.name, d.name, s.name
                ORDER BY s.name ASC, d.name ASC, b.name ASC, sc.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 4) {
            // Circle Level: circle, block, district, state
            $q = $db->query("
                SELECT c.name AS loc1, b.name AS loc2, d.name AS loc3, s.name AS loc4, NULL AS loc5,
                       COUNT(a.id) AS member_count, a.circle_id AS location_id
                FROM appointments a
                LEFT JOIN circles c ON c.id = a.circle_id
                LEFT JOIN blocks b ON b.id = a.block_id
                LEFT JOIN districts d ON d.id = COALESCE(b.district_id, a.district_id)
                LEFT JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 4 AND a.status = 'approved' AND a.circle_id IS NOT NULL
                GROUP BY a.circle_id, c.name, b.name, d.name, s.name
                ORDER BY s.name ASC, d.name ASC, b.name ASC, c.name ASC
            ");
            $committees = $q->getResultArray();

        } elseif ($levelId == 2) {
            // Village/Ward Level: village, sector, block, district, state
            $q = $db->query("
                SELECT COALESCE(v.name, CONCAT(COALESCE(sc.name, 'Unknown Sector'), ' Village/Ward')) AS loc1,
                       sc.name AS loc2, b.name AS loc3, d.name AS loc4, s.name AS loc5,
                       COUNT(a.id) AS member_count, a.village_id AS location_id
                FROM appointments a
                LEFT JOIN villages v ON v.id = a.village_id
                LEFT JOIN sectors sc ON sc.id = COALESCE(v.sector_id, a.sector_id)
                LEFT JOIN blocks b ON b.id = COALESCE(sc.block_id, a.block_id)
                LEFT JOIN districts d ON d.id = COALESCE(b.district_id, a.district_id)
                LEFT JOIN states s ON s.id = d.state_id
                WHERE a.level_id = 2 AND a.status = 'approved' AND a.village_id IS NOT NULL
                GROUP BY a.village_id, v.name, sc.name, b.name, d.name, s.name
                ORDER BY s.name ASC, d.name ASC, b.name ASC, sc.name ASC, v.name ASC
            ");
            $committees = $q->getResultArray();
        }

        $data['committees'] = $committees;
        $data['levelLabel'] = $levelLabels[$levelId];
        $data['levelId'] = $levelId;

        return view('admin/committee_detail', $data);
    }

    /**
     * AJAX: Natural language committee query
     */
    public function queryCommittee()
    {
        // Suppress notices/warnings that might corrupt JSON output on live servers
        $prevErrLevel = error_reporting(E_ERROR | E_PARSE);
        ob_start(); // capture any stray output (warnings, notices, etc.)

        try {
            $question = trim($this->request->getPost('question') ?? '');

            if (empty($question)) {
                ob_end_clean();
                error_reporting($prevErrLevel);
                return $this->response->setJSON(['error' => 'Please enter a question.']);
            }

            $engine = new \App\Helpers\CommitteeQueryEngine();
            $result = $engine->parse($question);

            ob_end_clean(); // discard any stray output
            error_reporting($prevErrLevel);
            return $this->response->setJSON($result);

        } catch (\Throwable $e) {
            ob_end_clean();
            error_reporting($prevErrLevel);
            return $this->response->setJSON([
                'error'   => $e->getMessage(),
                'answer'  => '',
                'results' => [],
                'type'    => 'error',
            ]);
        }
    }

    public function applications()
    {
        $builder = $this->userModel;
        
        // Select Users data + Appointment data + Location Names
        $builder->select('
            levels.name as level_name,
            COALESCE(alp.name, clp.name, glp.name, mlp.name) as post_name,
            
            app_states.name as state_name,
            app_districts.name as district_name
        ');

        // Join Appointments
        $builder->join('appointments', 'appointments.user_id = users.id', 'inner');

        // Join Levels & Posts
        $builder->join('levels', 'levels.id = appointments.level_id', 'left');
        $builder->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left');
        $builder->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 15', 'left');
        $builder->join('managerial_level_posts as mlp', 'mlp.id = appointments.post_id AND appointments.level_id BETWEEN 16 AND 17', 'left');

        // Join Locations (Appointment Jurisdiction)
        $builder->join('states as app_states', 'app_states.id = appointments.state_id', 'left');
        $builder->join('districts as app_districts', 'app_districts.id = appointments.district_id', 'left');

        // Filter by Pending Appointments
        $builder->where('appointments.status', 'pending');
        
        $builder->orderBy('appointments.created_at', 'DESC');

        $data['applications'] = $builder->paginate(10);
        $data['pager'] = $builder->pager;
        
        return view('admin/usersRequest.php', $data);
    }

    public function viewApplicationDetails($userId)
    {
        // 1. Fetch User (Personal Info)
        $user = $this->userModel
            ->select('users.*, 
                states.name as state_name,
                districts.name as district_name,
                mla_area.name as mla_area_name,
                blocks.name as block_name')
            // Join Personal Location Tables
            ->join('states', 'states.id = users.state_id', 'left')
            ->join('districts', 'districts.id = users.district_id', 'left')
            ->join('mla_area', 'mla_area.id = users.mla_area_id', 'left')
            ->join('blocks', 'blocks.id = users.block_id', 'left')
            ->find($userId);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 2. Fetch Appointment (Jurisdiction info & names)
        // We look for the latest appointment (or pending/approved)
        $appointmentModel = new AppointmentModel();
        $builder = $appointmentModel->builder();
        $builder->select('
            appointments.id as appointment_id,
            appointments.status as appointment_status,
            appointments.created_at as appointment_created_at,
            appointments.updated_at as appointment_updated_at,
            
            app_states.name as committee_state,
            app_districts.name as committee_district,
            app_mla_area.name as committee_mla_area,
            app_blocks.name as committee_block,
            app_sectors.name as committee_sector,
            
            levels.name as level_name,
            organs.name as organ_name,
            COALESCE(alp.name, clp.name, glp.name, mlp.name) as post_name
        ');
        
        // Joins for Appointment Location
        $builder->join('states as app_states', 'app_states.id = appointments.state_id', 'left');
        $builder->join('districts as app_districts', 'app_districts.id = appointments.district_id', 'left');
        $builder->join('mla_area as app_mla_area', 'app_mla_area.id = appointments.mla_area_id', 'left');
        $builder->join('blocks as app_blocks', 'app_blocks.id = appointments.block_id', 'left');
        $builder->join('sectors as app_sectors', 'app_sectors.id = appointments.sector_id', 'left');
        
        // Joins for Organ/Level/Post
        $builder->join('levels', 'levels.id = appointments.level_id', 'left');
        $builder->join('organs', 'organs.id = appointments.organ_id', 'left');
        $builder->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left');
        $builder->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 15', 'left');
        $builder->join('managerial_level_posts as mlp', 'mlp.id = appointments.post_id AND appointments.level_id BETWEEN 16 AND 17', 'left');

        $appointment = $builder->where('user_id', $userId)
                               ->orderBy('appointments.created_at', 'DESC')
                               ->get()->getRowArray();

        // 3. Merge Appointment Data into User Array
        if ($appointment) {
            $user = array_merge($user, $appointment);
            // Ensure status comes from appointment if it's the main context of this view?
            // "Application Details" usually refers to the appointment request.
            // But users table also has 'status'.
            // Let's defer to appointment status for "Application Status"
            $user['status'] = $appointment['appointment_status']; 
            $user['created_at'] = $appointment['appointment_created_at'];
            $user['updated_at'] = $appointment['appointment_updated_at'];
        } else {
             // Fallbacks if no appointment found
             $user['level_name'] = 'N/A';
             $user['post_name'] = 'N/A';
             $user['organ_name'] = 'N/A';
        }

        $data['user'] = $user;

        // Check if it's an AJAX request
        if ($this->request->isAJAX()) {
            return view('admin/application_detail_modal', $data);
        }

        return view('admin/application_detail_view', $data);
    }

    public function approveApplication($userId)
    {
        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel->where('user_id', $userId)->where('status', 'pending')->first();
        
        if ($appointment) {
            $appointmentModel->update($appointment['id'], [
                'status' => 'approved',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            session()->setFlashdata('success', 'Application approved.');
        } else {
            session()->setFlashdata('error', 'No pending application found for this user.');
        }

        return redirect()->to('admin/applications');
    }

    public function rejectApplication($userId)
    {
        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel->where('user_id', $userId)->where('status', 'pending')->first();

        if ($appointment) {
            $appointmentModel->update($appointment['id'], [
                'status' => 'rejected',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            session()->setFlashdata('info', 'Application rejected.');
        } else {
            session()->setFlashdata('error', 'No pending application found for this user.');
        }

        return redirect()->to('admin/applications');
    }   
    public function users() // Was usersList in MD
    {
        $searchTerm = $this->request->getGet('search_term');
        $filterLevelId    = $this->request->getGet('level_id');
        $filterLocationId = $this->request->getGet('location_id');

        // Start building the query.
        $builder = $this->userModel;

        // Join with appointments table to get office bearer details
        $builder->join('appointments', 'appointments.user_id = users.id', 'inner'); 

        // Join with the appropriate post table based on the appointment's level_id
        // and use COALESCE to pick the non-null post name.
        $builder->select("
            users.*, 
            appointments.status,
            levels.name as level_name,
            COALESCE(alp.name, clp.name, glp.name, mlp.name) as post_name,
            s.name as state_name,
            d.name as district_name,
            b.name as block_name,
            sc.name as sector_name,
            mla.name as mla_name,
            l1.name as ls_name,
            l2.Name as ls2_name,
            l3.name as ls3_name,
            l4.Name as ls4_name
        ")
        ->join('levels', 'levels.id = appointments.level_id', 'left')
        ->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left')
        ->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left')
        ->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 15', 'left')
        ->join('managerial_level_posts as mlp', 'mlp.id = appointments.post_id AND appointments.level_id BETWEEN 16 AND 17', 'left')
        ->join('states s', 's.id = appointments.state_id', 'left')
        ->join('districts d', 'd.id = appointments.district_id', 'left')
        ->join('blocks b', 'b.id = appointments.block_id', 'left')
        ->join('sectors sc', 'sc.id = appointments.sector_id', 'left')
        ->join('mla_area mla', 'mla.id = appointments.mla_area_id', 'left')
        ->join('ls l1', 'l1.id = appointments.ls_id', 'left')
        ->join('2ls l2', 'l2.id = appointments.2ls_id', 'left')
        ->join('3ls l3', 'l3.id = appointments.3ls_id', 'left')
        ->join('4ls l4', 'l4.id = appointments.4ls_id', 'left');

        // Join with admins table to exclude them from Office Bearer list
        $builder->join('admins', 'admins.mobile = users.mobile', 'left');

        // Filter for active, non-admin users.
        $builder->where('appointments.status', 'approved')
                ->where('admins.id IS NULL');

        // ── Exact ID-based location filter (from committee_detail "View Members") ──
        // Map level_id → the appointments column that holds the location ID
        $levelLocationFieldMap = [
            11 => 'appointments.state_id',
            16 => 'appointments.district_id',
            6  => 'appointments.mla_area_id',
            5  => 'appointments.block_id',
            7  => 'appointments.ls_id',
            3  => 'appointments.sector_id',
        ];

        if (!empty($filterLevelId) && !empty($filterLocationId) && isset($levelLocationFieldMap[(int)$filterLevelId])) {
            $locField = $levelLocationFieldMap[(int)$filterLevelId];
            $builder->where('appointments.level_id', (int)$filterLevelId)
                    ->where($locField, (int)$filterLocationId);
        }

        // ── Text search (general search bar) ──
        if (!empty($searchTerm)) {
            $keywords = explode(' ', $searchTerm);
            foreach ($keywords as $word) {
                if (trim($word) === '') continue;
                
                $builder->groupStart()
                        ->like('users.first_name', $word)
                        ->orLike('users.last_name', $word)
                        ->orLike('users.email', $word)
                        ->orLike('users.mobile', $word)
                        ->orLike('alp.name', $word)
                        ->orLike('clp.name', $word)
                        ->orLike('glp.name', $word)
                        ->orLike('mlp.name', $word)
                        ->orLike('levels.name', $word)
                        ->orLike('s.name', $word)
                        ->orLike('d.name', $word)
                        ->orLike('b.name', $word)
                        ->orLike('sc.name', $word)
                        ->orLike('mla.name', $word)
                        ->orLike('l1.name', $word)
                        ->orLike('l2.Name', $word)
                        ->orLike('l3.name', $word)
                        ->orLike('l4.Name', $word)
                        ->groupEnd();
            }
        }

        $data = [
            'users' => $builder->orderBy('appointments.post_id', 'ASC')
            ->paginate(20),
            'pager' => $this->userModel->pager,
        ];
        return view('admin/users_list', $data); // View: admin/users_list.php
    }

    public function checkAdminUser($mobile)
    {
        // This is a temporary debug function.
        // It's recommended to remove this and its route once the issue is resolved.
        if (ENVIRONMENT !== 'development') {
            // Restrict access to development environment for security
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $user = $this->userModel->where('mobile', $mobile)->first();

        if ($user) {
            header('Content-Type: text/plain');
            echo "--- User Details for Mobile: {$mobile} ---\n\n";
            
            print_r($user);

            echo "\n--- Login Checks ---\n\n";
            echo "1. Is Admin Flag (is_admin): " . ($user['is_admin'] == 1 ? 'YES (Correct)' : 'NO (INCORRECT - Must be 1 for admin login)') . "\n";
            echo "2. Account Status (status): '" . esc($user['status']) . "' (Should be 'approved' for login)\n";
            
            $passwordCorrect = password_verify('admin123', $user['password']);
            echo "3. Password verification for 'admin123': " . ($passwordCorrect ? 'SUCCESS' : 'FAILURE (Hash in DB does not match this password)') . "\n";

        } else {
            echo "<h1>User with mobile {$mobile} not found.</h1>";
        }
        exit; // Stop execution to prevent rendering a full page
    }
    public function editUserForm($userId)
    {
        $data['user'] = $this->userModel->find($userId);
        
        if (!$data['user']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Fetch Appointment Data
        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel->where('user_id', $userId)->where('status', 'approved')->first();

        // Merge appointment data into user array with 'committee_' prefix for location
        // and override level/post/organ
        if ($appointment) {
            $data['user']['level_id'] = $appointment['level_id'];
            $data['user']['post_id'] = $appointment['post_id'];
            $data['user']['organ_id'] = $appointment['organ_id'];
            
            $data['user']['committee_state_id'] = $appointment['state_id'];
            $data['user']['committee_district_id'] = $appointment['district_id'];
            $data['user']['block_id'] = $appointment['block_id']; // block_id (appointment)
            $data['user']['circle_id'] = $appointment['circle_id'];
            $data['user']['sector_id'] = $appointment['sector_id'];
            $data['user']['village_id'] = $appointment['village_id'];
            $data['user']['mla_area_id'] = $appointment['mla_area_id'];
            $data['user']['ls_id'] = $appointment['ls_id'];
            $data['user']['status'] = $appointment['status'];
        } else {
            // Nullify if no appointment found (shouldn't happen for active OBs)
            $data['user']['level_id'] = null;
            $data['user']['post_id'] = null;
            $data['user']['organ_id'] = null;
            $data['user']['committee_state_id'] = null;
            $data['user']['committee_district_id'] = null;
            // distinct fields ...
        }
        
        // Load all dropdown data
        $stateModel = new \App\Models\StateModel();
        $districtModel = new \App\Models\DistrictModel();
        $blockModel = new \App\Models\BlockModel();
        $mlaAreaModel = new \App\Models\MlaAreaModel();
        $levelModel = new \App\Models\LevelModel();
        $organModel = new \App\Models\OrganModel();
        
        $data['all_states'] = $stateModel->orderBy('name', 'ASC')->findAll();
        $data['all_levels'] = $levelModel->orderBy('name', 'ASC')->findAll();
        $data['all_organs'] = $organModel->orderBy('name', 'ASC')->findAll();
        
        $circleModel = new \App\Models\CircleModel();
        $data['all_circles'] = $circleModel->orderBy('name', 'ASC')->findAll();
        
        // Load PERSONAL location lists
        if (!empty($data['user']['state_id'])) {
            $data['districts'] = $districtModel->where('state_id', $data['user']['state_id'])->orderBy('name', 'ASC')->findAll();
        }

        // Load APPOINTMENT location lists
        if (!empty($data['user']['committee_state_id'])) {
            $data['committee_districts'] = $districtModel->where('state_id', $data['user']['committee_state_id'])->orderBy('name', 'ASC')->findAll();
        }
        
        if (!empty($data['user']['committee_district_id'])) {
            $data['current_blocks'] = $blockModel->where('district_id', $data['user']['committee_district_id'])->orderBy('name', 'ASC')->findAll();
            $data['current_mla_areas'] = $mlaAreaModel->where('district_id', $data['user']['committee_district_id'])->orderBy('name', 'ASC')->findAll();
        }

        if (!empty($data['user']['block_id'])) { // This is appointment block
            $sectorModel = new \App\Models\SectorModel();
            $data['current_sectors'] = $sectorModel->where('block_id', $data['user']['block_id'])->orderBy('name', 'ASC')->findAll();
        }
        
        if (!empty($data['user']['sector_id'])) {
            $villageModel = new \App\Models\VillageModel();
            $data['current_villages'] = $villageModel->where('sector_id', $data['user']['sector_id'])->orderBy('name', 'ASC')->findAll();
        }
        
        // Load current post based on level
        if (!empty($data['user']['level_id'])) {
            $postModel = new \App\Models\PostModel();
            $data['current_posts'] = $postModel->getPostsByLevel($data['user']['level_id']);
        }
        
        helper('form');
        return view('admin/edit_user_form', $data);
    }

    public function updateUser($userId)
    {
        $user = $this->userModel->find($userId);
        if (!$user) {
            session()->setFlashdata('error', 'User not found.');
            return redirect()->to('admin/usersList');
        }

        // Validation rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'permit_empty|max_length[100]',
            'state_id' => 'required|numeric',
            'district_id' => 'required|numeric',
            'level_id' => 'required|numeric',
            'post_id' => 'required|numeric',
            'status' => 'required|in_list[pending,approved,rejected,suspended]',
        ];

        if (!$this->validate($rules)) {
            session()->setFlashdata('error', 'Validation failed. Please check your inputs.');
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Prepare User Data (Personal)
        $updateData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'father_name' => $this->request->getPost('father_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'address_line1' => $this->request->getPost('address_line1'),
            
            // Personal Location
            'state_id' => $this->request->getPost('state_id'),
            'district_id' => $this->request->getPost('district_id'),
            'mla_area_id' => $this->request->getPost('mla_area_id') ?: null, 
            'block_id' => $this->request->getPost('block_id') ?: null, 
            
            'edited_by' => session()->get('admin_id'),
            'edited_by_name' => session()->get('name'),
        ];

        // Prepare Appointment Data
        $appointmentData = [
            'level_id' => $this->request->getPost('level_id'),
            'post_id' => $this->request->getPost('post_id'),
            'organ_id' => $this->request->getPost('organ_id') ?: null,
            'status' => $this->request->getPost('status'), // Sync status
        ];
        
        // Location mapping
        $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
        $appointmentData['district_id'] = $this->request->getPost('committee_district_id') ?: null;
        $appointmentData['block_id'] = $this->request->getPost('block_id') ?: null;
        $appointmentData['circle_id'] = $this->request->getPost('circle_id') ?: null;
        $appointmentData['sector_id'] = $this->request->getPost('sector_id') ?: null;
        $appointmentData['village_id'] = $this->request->getPost('village_id') ?: null;
        $appointmentData['mla_area_id'] = $this->request->getPost('mla_area_id') ?: null;
        $appointmentData['ls_id'] = $this->request->getPost('ls_id') ?: null;

        // Audit info for appointment
        $appointmentData['edited_by'] = session()->get('admin_id');
        $appointmentData['edited_by_name'] = session()->get('name');

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $validationRules = [
                'photo' => 'uploaded[photo]|max_size[photo,2048]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]'
            ];
            
            if ($this->validate($validationRules)) {
                $newName = $photo->getRandomName();
                $photo->move(WRITEPATH . '../public/uploads/photos', $newName);
                $updateData['photo'] = $newName;
                
                // Delete old photo if exists
                if (!empty($user['photo']) && file_exists(WRITEPATH . '../public/uploads/photos/' . $user['photo'])) {
                    unlink(WRITEPATH . '../public/uploads/photos/' . $user['photo']);
                }
            } else {
                session()->setFlashdata('error', 'Invalid photo file. Please upload JPG or PNG (max 2MB).');
                return redirect()->back()->withInput();
            }
        }

        // Update user
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            log_message('debug', 'Updating user ID: ' . $userId . ' with data: ' . json_encode($updateData));
            $updateResult = $this->userModel->update($userId, $updateData);
            log_message('debug', 'User update result: ' . ($updateResult ? 'success' : 'failed'));
            
            if (!$updateResult) {
                log_message('error', 'User update failed. Errors: ' . json_encode($this->userModel->errors()));
            }
            
            // Update appointment
            $appointmentModel = new AppointmentModel();
            // Check if appointment exists (it should for approved office bearers)
            $existingApp = $appointmentModel->where('user_id', $userId)->first();
            if ($existingApp) {
                $appointmentModel->update($existingApp['id'], $appointmentData);
            } else {
                // Create if missing (edge case)
                $appointmentData['user_id'] = $userId;
                $appointmentData['added_by'] = session()->get('admin_id');
                $appointmentData['added_by_name'] = session()->get('name');
                $appointmentModel->insert($appointmentData);
            }
            
            $db->transComplete();
            
            session()->setFlashdata('success', 'Record Updated Successfully');
            return redirect()->to('admin/users/edit/' . $userId);
        } catch (\Exception $e) {
            $db->transRollback();
            session()->setFlashdata('error', 'Failed to update user details: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function deleteUser($userId)
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete associated appointments
            $appointmentModel = new AppointmentModel();
            $appointmentModel->where('user_id', $userId)->delete();

            // Delete user
            $this->userModel->delete($userId);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Failed to delete office bearer.');
            }

            return redirect()->to('admin/usersList')->with('success', 'Office bearer deleted successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function export()
    {
        // Logic for exporting data (users, applications) to CSV/Excel
        return view('admin/export_excel'); // View: admin/export_excel.php
    }


public function viewApplication($applicationId)
    {
        // Fetch the application data using the provided ID
        $application = $this->userModel->find($applicationId);

        // Check if the application exists
        if (!$application) {
            // Handle the case where the application is not found (e.g., show a 404 page)
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Prepare the data to be sent to the view
        $data['application'] = $application;

        // Load the view and pass the data
        return view('admin/viewApplication', $data);
    }

    public function exportAllUsersDetailed()
    {
        // Get all users with their details (Personal Names + Appointment Details)
        $builder = $this->userModel;
        $builder->select('
            users.*,
            personal_states.name as personal_state_name,
            personal_districts.name as personal_district_name,
            personal_mla.name as personal_mla_name,
            personal_blocks.name as personal_block_name,
            personal_sectors.name as personal_sector_name,
            
            appointments.id as appointment_id,
            appointments.status as appointment_status,
            
            app_states.name as committee_state_name,
            app_districts.name as committee_district_name,
            app_mla.name as committee_mla_name,
            app_blocks.name as committee_block_name,
            
            levels.name as level_name,
            organs.name as organ_name,
            COALESCE(alp.name, clp.name, glp.name, mlp.name) as post_name
        ');

        // Joins for Personal Location
        $builder->join('states as personal_states', 'personal_states.id = users.state_id', 'left');
        $builder->join('districts as personal_districts', 'personal_districts.id = users.district_id', 'left');
        $builder->join('mla_area as personal_mla', 'personal_mla.id = users.mla_area_id', 'left');
        $builder->join('blocks as personal_blocks', 'personal_blocks.id = users.block_id', 'left');
        $builder->join('sectors as personal_sector', 'personal_sector.id = users.sector_id', 'left');

        // Join Appointments (Latest one per user)
        // Note: For large datasets, a subquery for 'latest' might be better, 
        // but here we merge with the latest appointment found.
        $builder->join('appointments', 'appointments.user_id = users.id', 'left');

        // Joins for Appointment Location
        $builder->join('states as app_states', 'app_states.id = appointments.state_id', 'left');
        $builder->join('districts as app_districts', 'app_districts.id = appointments.district_id', 'left');
        $builder->join('mla_area as app_mla', 'app_mla.id = appointments.mla_area_id', 'left');
        $builder->join('blocks as app_blocks', 'app_blocks.id = appointments.block_id', 'left');

        // Joins for Organ/Level/Post
        $builder->join('levels', 'levels.id = appointments.level_id', 'left');
        $builder->join('organs', 'organs.id = appointments.organ_id', 'left');
        $builder->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left');
        $builder->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 15', 'left');
        $builder->join('managerial_level_posts as mlp', 'mlp.id = appointments.post_id AND appointments.level_id BETWEEN 16 AND 17', 'left');

        $users = $builder->findAll();

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="all_users_detailed_' . date('Y-m-d') . '.csv"');

        // Create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for proper Excel encoding
        fputs($output, "\xEF\xBB\xBF");

        // Set up the column headers
        fputcsv($output, [
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Mobile',
            'Alternate Mobile',
            'Date of Birth',
            'Gender',
            'Father Name',
            'Mother Name',
            'Aadhaar Number',
            'Personal State',
            'Personal District',
            'Personal MLA Area',
            'Personal Block',
            'Address Line 1',
            'Address Line 2',
            'Pin Code',
            'User Status',
            'Committee State',
            'Committee District',
            'Committee MLA Area',
            'Committee Block',
            'Organ',
            'Level',
            'Post',
            'Appointment Status',
            'Created At'
        ]);

        // Output each user row
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['email'],
                $user['mobile'],
                $user['alternate_mobile'],
                $user['date_of_birth'],
                $user['gender'],
                $user['father_name'],
                $user['mother_name'],
                $user['aadhaar_number'],
                $user['personal_state_name'],
                $user['personal_district_name'],
                $user['personal_mla_name'],
                $user['personal_block_name'],
                $user['address_line1'],
                $user['address_line2'],
                $user['pin_code'],
                $user['status'],
                $user['committee_state_name'],
                $user['committee_district_name'],
                $user['committee_mla_name'],
                $user['committee_block_name'],
                $user['organ_name'],
                $user['level_name'],
                $user['post_name'],
                $user['appointment_status'],
                $user['created_at']
            ]);
        }

        fclose($output);
        exit;
    }

    public function manageListing()
    {
        return view('admin/manage_listing');
    }

    /**
     * Display the Add Office Bearer form
     */
    public function addOfficeBearer()
    {
        $data['all_states'] = $this->stateModel->orderBy('name', 'ASC')->findAll();
        
        $organModel = new \App\Models\OrganModel();
        $data['organs'] = $organModel->orderBy('name', 'ASC')->findAll();

        $frontModel = new \App\Models\FrontModel();
        $data['fronts'] = $frontModel->orderBy('name', 'ASC')->findAll();

        return view('admin/add_office_bearer', $data);
    }

    /**
     * Save the new Office Bearer
     */
    public function saveOfficeBearer()
    {
        $validation = \Config\Services::validation();
        
        // Validation Rules
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[255]',
            'last_name' => 'permit_empty|max_length[255]',
            'father_husband_name' => 'permit_empty|min_length[3]|max_length[255]',
            'mobile' => 'required|regex_match[/^[0-9]{10}$/]',
            'state_id' => 'required|is_natural_no_zero',
            'address' => 'permit_empty|min_length[10]',
            'appointed_level' => 'required|in_list[Village,Sector,Circle,Block,District,MLA Constituency,MP Constituency,State]',
            'post_id' => 'required|is_natural_no_zero',
            'organ_id' => 'required|is_natural_no_zero', // Made required
        ];

        $messages = [
            'mobile' => [
                'regex_match' => 'Please enter a valid 10-digit mobile number.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->getErrors()
            ]);
        }

        // Custom check for existing mobile to provide detailed error
        // Check if user exists
        $existingUser = $this->userModel->where('mobile', $this->request->getPost('mobile'))->first();
        
        // If user exists, check if they have an active appointment
        if ($existingUser) {
            $appointmentModel = new AppointmentModel();
            $existingAppointment = $appointmentModel->where('user_id', $existingUser['id'])
                                                    ->where('post_id', $this->request->getPost('post_id'))
                                                    ->where('level_id', $levelMapping[$this->request->getPost('appointed_level')] ?? null) // Check for same level
                                                    ->where('status', 'approved')
                                                    ->first();
            
            if ($existingAppointment) {
                 // Fetch Level Name
                $levelModel = new \App\Models\LevelModel();
                $level = $levelModel->find($existingAppointment['level_id']);
                $levelName = $level ? $level['name'] : 'Unknown Level';

                // Fetch Post Name
                $postName = 'Unknown Post';
                $postModel = new \App\Models\PostModel();
                $post = $postModel->getPostByIdAndLevel($existingAppointment['post_id'], $existingAppointment['level_id']);
                if ($post) {
                    $postName = $post['name'];
                }

                // Fetch Location Name
                $locationName = 'Unknown Location';
                $lId = $existingAppointment['level_id'];
                
                if ($lId == 2) { // Village
                    $villageModel = new \App\Models\VillageModel();
                    $loc = $villageModel->find($existingAppointment['village_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 3) { // Sector
                    $sectorModel = new \App\Models\SectorModel();
                    $loc = $sectorModel->find($existingAppointment['sector_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 4) { // Circle
                    $circleModel = new \App\Models\CircleModel();
                    $loc = $circleModel->find($existingAppointment['circle_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 5) { // Block
                    $blockModel = new \App\Models\BlockModel();
                    $loc = $blockModel->find($existingAppointment['block_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 16) { // District
                    $districtModel = new \App\Models\DistrictModel();
                    $loc = $districtModel->find($existingAppointment['district_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 6) { // MLA
                    $mlaModel = new \App\Models\MlaAreaModel();
                    $loc = $mlaModel->find($existingAppointment['mla_area_id']);
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 7) { // MP
                    $fourLsModel = new \App\Models\FourLsModel();
                    $loc = $fourLsModel->find($existingAppointment['ls_id']); // Assuming ls_id maps to 4LS
                    $locationName = $loc ? $loc['name'] : '';
                } elseif ($lId == 11) { // State
                    $stateModel = new \App\Models\StateModel();
                    $loc = $stateModel->find($existingAppointment['state_id']);
                    $locationName = $loc ? $loc['name'] : '';
                }

                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Duplicate appointment detected. Please check the error below.",
                    'errors' => ['mobile' => "This person is already appointed as $postName at '$locationName' '$levelName'"]
                ]);
            }
        }

        $appointedLevel = $this->request->getPost('appointed_level');
        $postId = $this->request->getPost('post_id');
        
        // Map appointed level to level_id (you may need to adjust these based on your levels table)
        $levelMapping = [
            'Village' => 2,
            'Sector' => 3,
            'Circle' => 4,
            'Block' => 5,
            'District' => 16,
            'MLA Constituency' => 6,
            'MP Constituency' => 7,
            'State' => 11,
        ];

        $levelId = $levelMapping[$appointedLevel] ?? null;

        if (!$levelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid appointment level'
            ]);
        }

        // Prepare User Data (Personal Info)
        // Note: For now, we still save personal address fields.
        // We use the committee fields as fallback IF usage intends to overwrite personal address with appointment address
        // But cleaner is to use the dedicated personal address fields from form (which are address, state_id, district_id)
        
        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name') ?: '',
            'father_name' => $this->request->getPost('father_husband_name'),
            'mobile' => $this->request->getPost('mobile'),
            'email' => $this->request->getPost('mobile') . '@vpi-ob.local', // Generate unique email from mobile
            'aadhaar_number' => 'OB' . $this->request->getPost('mobile'), // Generate unique aadhaar placeholder
            
            // Personal Address
            'state_id' => $this->request->getPost('state_id'),
            'district_id' => $this->request->getPost('district_id'),
            'address_line1' => $this->request->getPost('address'),
            
            'added_by' => session()->get('admin_id'),
            'added_by_name' => session()->get('name'),
        ];

        // Prepare Appointment Data
        $appointmentData = [
            'level_id' => $levelId,
            'post_id' => $postId,
            'organ_id' => $this->request->getPost('organ_id') ?: null,
            'front_id' => $this->request->getPost('front_id') ?: null, // Added front_id
            'status' => 'approved',
            'added_by' => session()->get('admin_id'),
            'added_by_name' => session()->get('name'),
        ];

        // Add location-specific IDs based on appointed level
        // We capture these from committee_* fields or direct fields
        $appointmentData['state_id'] = $this->request->getPost('committee_state_id') ?: $this->request->getPost('state_id'); // Fallback only if committee hidden? No, committee fields are distinct.
        $appointmentData['district_id'] = $this->request->getPost('committee_district_id') ?: null; // Don't fallback to personal district for appointment unless logic demands
        
        // Refine location logic:
        if ($appointedLevel === 'State') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
        } elseif ($appointedLevel === 'District') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
             $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
        } elseif ($appointedLevel === 'Block') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
             $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
             $appointmentData['block_id'] = $this->request->getPost('block_id') ?: null;
        } elseif ($appointedLevel === 'Circle') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
             $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
             $appointmentData['block_id'] = $this->request->getPost('block_id');
             $appointmentData['circle_id'] = $this->request->getPost('circle_id') ?: null;
        } elseif ($appointedLevel === 'Sector') {
            $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
            $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
            $appointmentData['block_id'] = $this->request->getPost('block_id');
            $appointmentData['sector_id'] = $this->request->getPost('sector_id') ?: null;
        } elseif ($appointedLevel === 'Village') {
            $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
            $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
            $appointmentData['block_id'] = $this->request->getPost('block_id');
            $appointmentData['sector_id'] = $this->request->getPost('sector_id');
            $appointmentData['village_id'] = $this->request->getPost('village_id') ?: null;
        } elseif ($appointedLevel === 'MLA Constituency') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
             $appointmentData['district_id'] = $this->request->getPost('committee_district_id');
             $appointmentData['mla_area_id'] = $this->request->getPost('mla_area_id') ?: null;
        } elseif ($appointedLevel === 'MP Constituency') {
             $appointmentData['state_id'] = $this->request->getPost('committee_state_id');
             $appointmentData['ls_id'] = $this->request->getPost('ls_id') ?: null;
        }

        // Save to database
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Create User (if not exists, or update?)
            // If we found existing user above without appointment, we should use that user ID.
            if ($existingUser) {
                $userId = $existingUser['id'];
                // Optional: Update personal details if changed? For now, let's keep existing user data mostly.
            } else {
                $this->userModel->insert($userData);
                $userId = $this->userModel->getInsertID();
            }

            // 2. Create Appointment
            $appointmentData['user_id'] = $userId;
            $appointmentModel = new AppointmentModel();
            $appointmentModel->insert($appointmentData);

            $db->transComplete(); // Commit

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Office bearer added successfully'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error saving office bearer: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save office bearer. Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get posts by appointment level name
     */
    public function getPostsByAppointmentLevel($levelName)
    {
        // Map level names to level IDs (Standardized to match levels table)
        $levelMapping = [
            'Village' => 2,
            'Sector' => 3,
            'Circle' => 4,
            'Block' => 5,
            'District' => 16,
            'MLA Constituency' => 6,
            'MP Constituency' => 7,
            'State' => 11,
        ];

        $levelId = $levelMapping[$levelName] ?? null;

        if (!$levelId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid level name'
            ]);
        }

        $postModel = new \App\Models\PostModel();
        $posts = $postModel->getPostsByLevel($levelId);

        return $this->response->setJSON([
            'success' => true,
            'posts' => $posts
        ]);
    }

    /**
     * Get posts availability for a specific location
     */
    public function getPostsAvailability()
    {
        $level = $this->request->getVar('level');
        $locationData = [
            'state_id' => $this->request->getVar('state_id'),
            'district_id' => $this->request->getVar('district_id'),
            'block_id' => $this->request->getVar('block_id'),
            'sector_id' => $this->request->getVar('sector_id'),
            'village_id' => $this->request->getVar('village_id'), // NEW
            'circle_id' => $this->request->getVar('circle_id'),   // NEW
            'mla_area_id' => $this->request->getVar('mla_area_id'),
            'ls_id' => $this->request->getVar('ls_id'),
        ];

        // Map level names to level IDs and required location fields
        $levelConfig = [
            'Village' => ['id' => 2, 'groupBy' => ['state_id', 'district_id', 'block_id', 'sector_id', 'village_id']],
            'Sector' => ['id' => 3, 'groupBy' => ['state_id', 'district_id', 'block_id', 'sector_id']],
            'Circle' => ['id' => 4, 'groupBy' => ['state_id', 'district_id', 'block_id', 'circle_id']],
            'Block' => ['id' => 5, 'groupBy' => ['state_id', 'district_id', 'block_id']],
            'District' => ['id' => 16, 'groupBy' => ['state_id', 'district_id']],
            'MLA Constituency' => ['id' => 6, 'groupBy' => ['state_id', 'district_id', 'mla_area_id']],
            'MP Constituency' => ['id' => 7, 'groupBy' => ['state_id', 'ls_id']], // Assuming usage of 1LS ID
            'State' => ['id' => 11, 'groupBy' => ['state_id']],
            // Note: Governing Level 11-15, Managerial Level 16-17 logic handled by PostModel
        ];

        if (!isset($levelConfig[$level])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid level']);
        }

        $config = $levelConfig[$level];
        $levelId = $config['id'];

        // Fetch all posts for this level
        $postModel = new \App\Models\PostModel();
        $allPosts = $postModel->getPostsByLevel($levelId);

        // Fetch existing appointments for this location
        $appointmentModel = new AppointmentModel();
        $builder = $appointmentModel->builder();
        $builder->select('appointments.post_id, users.first_name, users.last_name, users.mobile, users.photo');
        $builder->join('users', 'users.id = appointments.user_id');
        $builder->where('appointments.level_id', $levelId);
        $builder->where('appointments.status', 'approved');

        // Apply location filters ONLY for fields relevant to this level
        $relevantFields = $config['groupBy'];
        
        foreach ($relevantFields as $field) {
            if (!empty($locationData[$field])) {
                $builder->where('appointments.' . $field, $locationData[$field]);
            }
        }

        // Organ and Front filtering
        $organId = $this->request->getVar('organ_id');
        if (!empty($organId)) {
            if ($organId == 1) {
                // Main Committee: Include NULL or 0 for legacy records
                $builder->groupStart()
                    ->where('appointments.organ_id', $organId)
                    ->orWhere('appointments.organ_id', null)
                    ->orWhere('appointments.organ_id', 0)
                    ->groupEnd();
            } else {
                $builder->where('appointments.organ_id', $organId);
            }
        }

        $frontId = $this->request->getVar('front_id');
        if (!empty($frontId)) {
            $builder->where('appointments.front_id', $frontId);
        } else {
            // If no front is selected (e.g., Main Committee), ensure we only get posts without a front
            $builder->groupStart()
                    ->where('appointments.front_id', null)
                    ->orWhere('appointments.front_id', 0)
                    ->groupEnd();
        }

        $occupiedPosts = $builder->get()->getResultArray();
        
        // Map occupants to posts
        $occupancyMap = [];
        foreach ($occupiedPosts as $user) {
            $occupancyMap[$user['post_id']] = $user;
        }

        // Prepare final response
        $postsWithStatus = [];
        foreach ($allPosts as $post) {
            $occupiedBy = null;
            if (isset($occupancyMap[$post['id']])) {
                $u = $occupancyMap[$post['id']];
                $occupiedBy = [
                    'name' => trim($u['first_name'] . ' ' . ($u['last_name'] ?? '')),
                    'mobile' => $u['mobile'],
                    'photo' => $u['photo'] ? base_url('uploads/photos/' . $u['photo']) : null
                ];
            }

            $postsWithStatus[] = [
                'id' => $post['id'],
                'name' => $post['name'],
                'occupied_by' => $occupiedBy
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $postsWithStatus,
            'debug' => [
                'levelId' => $levelId,
                'locationData' => $locationData,
                'filteringBy' => $config['groupBy'],
                'occupiedCount' => count($occupiedPosts),
                // 'occupiedRaw' => $occupiedPosts, // Commented out to reduce output size
                // 'sql' => (string)$appointmentModel->getLastQuery()
            ]
        ]);
    }



    public function manageLocation()
    {
        $data['all_states'] = $this->stateModel->orderBy('name', 'ASC')->findAll();
        $data['recentUsers'] = $this->userModel->orderBy('created_at', 'DESC')->limit(10)->findAll();
        return view('admin/manage_location', $data);
    }

    public function manageConstituencies()
    {
        $data['loksabha_list'] = $this->fourLsModel->orderBy('Name', 'ASC')->findAll();
        return view('admin/manage_constituencies', $data);
    }

    /**
     * Get 3 Loksabha constituencies for a specific 4 Loksabha
     */
    public function get3LsByFourLs($fourLsId)
    {
        $threeLsModel = new \App\Models\ThreeLsModel();
        $threeLs = $threeLsModel->get3LsByFourLs($fourLsId);

        if (!empty($threeLs)) {
            return $this->response->setJSON(['success' => true, '3ls' => $threeLs]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No 3 Loksabha found for this 4 Loksabha.']);
        }
    }

    // --- Admin Management ---

    public function manageAdmins()
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        $data['admins'] = $this->adminModel->findAll();
        return view('admin/manage_admins', $data);
    }

    public function createAdmin()
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }
        return view('admin/admin_form');
    }

    public function storeAdmin()
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[admins.email]',
            'mobile' => 'required|regex_match[/^[0-9]{10}$/]|is_unique[admins.mobile]',
            'password' => 'required|min_length[6]',
            'admin_type' => 'required|in_list[1,2]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'password' => $this->request->getPost('password'), // Model handles hashing
            'admin_type' => $this->request->getPost('admin_type'),
        ];

        $this->adminModel->insert($data);
        return redirect()->to('admin/manage-admins')->with('success', 'Admin created successfully');
    }

    public function editAdmin($id)
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        $data['admin'] = $this->adminModel->find($id);
        if (!$data['admin']) {
            return redirect()->to('admin/manage-admins')->with('error', 'Admin not found');
        }
        return view('admin/admin_form', $data);
    }

    public function updateAdmin($id)
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        $admin = $this->adminModel->find($id);
        if (!$admin) {
            return redirect()->to('admin/manage-admins')->with('error', 'Admin not found');
        }

        $rules = [
            'name' => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[admins.email,id,$id]",
            'mobile' => "required|regex_match[/^[0-9]{10}$/]|is_unique[admins.mobile,id,$id]",
            'admin_type' => 'required|in_list[1,2]'
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'mobile' => $this->request->getPost('mobile'),
            'admin_type' => $this->request->getPost('admin_type'),
        ];

        if (!empty($password)) {
            $data['password'] = $password; // Model handles hashing
        }

        $this->adminModel->skipValidation(true)->update($id, $data);
        return redirect()->to('admin/manage-admins')->with('success', 'Admin updated successfully');
    }

    public function deleteAdmin($id)
    {
        if (session('admin_type') != 1) {
            return redirect()->to('admin/dashboard')->with('error', 'Access Denied');
        }

        if ($id == session('admin_id')) {
            return redirect()->to('admin/manage-admins')->with('error', 'You cannot delete yourself.');
        }

        $this->adminModel->delete($id);
        return redirect()->to('admin/manage-admins')->with('success', 'Admin deleted successfully');
    }

    /**
     * Add/Update/Delete 3 Loksabha constituencies for a 4 Loksabha
     */
    public function add3Ls()
    {
        $fourLsId = $this->request->getJsonVar('4ls_id');
        $threeLsNames = $this->request->getJsonVar('names');

        if (empty($fourLsId)) {
            return $this->response->setJSON(['success' => false, 'message' => '4 Loksabha ID is required.']);
        }

        $threeLsModel = new \App\Models\ThreeLsModel();

        // If no 3 Loksabha names provided, delete all 3 Loksabha for this 4 Loksabha
        if (empty($threeLsNames)) {
            $threeLsModel->where('4ls_id', $fourLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 3 Loksabha removed.']);
        }

        // Sanitize 3 Loksabha names
        $sanitized3LsNames = array_map('trim', $threeLsNames);
        $sanitized3LsNames = array_filter($sanitized3LsNames); // Remove empty names

        if (empty($sanitized3LsNames)) {
            $threeLsModel->where('4ls_id', $fourLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 3 Loksabha removed.']);
        }

        // Get existing 3 Loksabha for this 4 Loksabha
        $existing3Ls = $threeLsModel->where('4ls_id', $fourLsId)->findAll();
        $existing3LsNames = array_map(function($ls) { return $ls['name']; }, $existing3Ls);

        // Find 3 Loksabha to add (in submitted list but not in database)
        $threeLsToAdd = array_diff($sanitized3LsNames, $existing3LsNames);

        // Find 3 Loksabha to delete (in database but not in submitted list)
        $threeLsToDelete = array_diff($existing3LsNames, $sanitized3LsNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new 3 Loksabha
        if (!empty($threeLsToAdd)) {
            $dataToInsert = [];
            foreach ($threeLsToAdd as $name) {
                $dataToInsert[] = [
                    '4ls_id' => $fourLsId,
                    'name' => $name,
                ];
            }
            if ($threeLsModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed 3 Loksabha
        if (!empty($threeLsToDelete)) {
            $threeLsModel->where('4ls_id', $fourLsId)->whereIn('name', $threeLsToDelete)->delete();
            $deletedCount = count($threeLsToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount 3 Loksabha added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount 3 Loksabha deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }

    /**
     * Get 2 Loksabha constituencies for a specific 3 Loksabha
     */
    public function get2LsByThreeLs($threeLsId)
    {
        $twoLsModel = new \App\Models\TwoLsModel();
        $twoLs = $twoLsModel->get2LsByThreeLs($threeLsId);

        if (!empty($twoLs)) {
            return $this->response->setJSON(['success' => true, '2ls' => $twoLs]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No 2 Loksabha found for this 3 Loksabha.']);
        }
    }



    /**
     * Get 1 Loksabha constituencies for a specific 2 Loksabha
     */
    public function get1LsByTwoLs($twoLsId)
    {
        $oneLsModel = new \App\Models\OneLsModel();
        $oneLs = $oneLsModel->get1LsByTwoLs($twoLsId);

        if (!empty($oneLs)) {
            return $this->response->setJSON(['success' => true, '1ls' => $oneLs]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'No 1 Loksabha found for this 2 Loksabha.']);
        }
    }

    /**
     * Add/Update/Delete 2 Loksabha constituencies for a 3 Loksabha
     */
    public function add2Ls()
    {
        $threeLsId = $this->request->getJsonVar('3ls_id');
        $twoLsNames = $this->request->getJsonVar('names');

        if ($threeLsId === null || $threeLsId === '') {
            return $this->response->setJSON(['success' => false, 'message' => '3 Loksabha ID is required.']);
        }

        $twoLsModel = new \App\Models\TwoLsModel();

        // If no 2 Loksabha names provided, delete all 2 Loksabha for this 3 Loksabha
        if (empty($twoLsNames)) {
            $twoLsModel->where('3ls_id', $threeLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 2 Loksabha removed.']);
        }

        // Sanitize 2 Loksabha names
        $sanitized2LsNames = array_map('trim', $twoLsNames);
        $sanitized2LsNames = array_filter($sanitized2LsNames); // Remove empty names

        if (empty($sanitized2LsNames)) {
            $twoLsModel->where('3ls_id', $threeLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 2 Loksabha removed.']);
        }

        // Get existing 2 Loksabha for this 3 Loksabha
        $existing2Ls = $twoLsModel->where('3ls_id', $threeLsId)->findAll();
        $existing2LsNames = array_map(function($ls) { return $ls['Name']; }, $existing2Ls);

        // Find 2 Loksabha to add (in submitted list but not in database)
        $twoLsToAdd = array_diff($sanitized2LsNames, $existing2LsNames);

        // Find 2 Loksabha to delete (in database but not in submitted list)
        $twoLsToDelete = array_diff($existing2LsNames, $sanitized2LsNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new 2 Loksabha
        if (!empty($twoLsToAdd)) {
            $dataToInsert = [];
            foreach ($twoLsToAdd as $name) {
                $dataToInsert[] = [
                    '3ls_id' => $threeLsId,
                    'Name' => $name,
                ];
            }
            if ($twoLsModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed 2 Loksabha
        if (!empty($twoLsToDelete)) {
            $twoLsModel->where('3ls_id', $threeLsId)->whereIn('Name', $twoLsToDelete)->delete();
            $deletedCount = count($twoLsToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount 2 Loksabha added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount 2 Loksabha deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }

    /**
     * Add/Update/Delete 1 Loksabha constituencies for a 2 Loksabha
     */
    public function add1Ls()
    {
        $twoLsId = $this->request->getJsonVar('2ls_id');
        $oneLsNames = $this->request->getJsonVar('names');

        if ($twoLsId === null || $twoLsId === '') {
            return $this->response->setJSON(['success' => false, 'message' => '2 Loksabha ID is required.']);
        }

        $oneLsModel = new \App\Models\OneLsModel();

        // If no 1 Loksabha names provided, delete all 1 Loksabha for this 2 Loksabha
        if (empty($oneLsNames)) {
            $oneLsModel->where('2ls_id', $twoLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 1 Loksabha removed.']);
        }

        // Sanitize 1 Loksabha names
        $sanitized1LsNames = array_map('trim', $oneLsNames);
        $sanitized1LsNames = array_filter($sanitized1LsNames); // Remove empty names

        if (empty($sanitized1LsNames)) {
            $oneLsModel->where('2ls_id', $twoLsId)->delete();
            return $this->response->setJSON(['success' => true, 'message' => 'All 1 Loksabha removed.']);
        }

        // Get existing 1 Loksabha for this 2 Loksabha
        $existing1Ls = $oneLsModel->where('2ls_id', $twoLsId)->findAll();
        $existing1LsNames = array_map(function($ls) { return $ls['name']; }, $existing1Ls);

        // Find 1 Loksabha to add (in submitted list but not in database)
        $oneLsToAdd = array_diff($sanitized1LsNames, $existing1LsNames);

        // Find 1 Loksabha to delete (in database but not in submitted list)
        $oneLsToDelete = array_diff($existing1LsNames, $sanitized1LsNames);

        $addedCount = 0;
        $deletedCount = 0;

        // Add new 1 Loksabha
        if (!empty($oneLsToAdd)) {
            $dataToInsert = [];
            foreach ($oneLsToAdd as $name) {
                $dataToInsert[] = [
                    '2ls_id' => $twoLsId,
                    'name' => $name,
                ];
            }
            if ($oneLsModel->insertBatch($dataToInsert)) {
                $addedCount = count($dataToInsert);
            }
        }

        // Delete removed 1 Loksabha
        if (!empty($oneLsToDelete)) {
            $oneLsModel->where('2ls_id', $twoLsId)->whereIn('name', $oneLsToDelete)->delete();
            $deletedCount = count($oneLsToDelete);
        }

        // Build response message
        $messages = [];
        if ($addedCount > 0) {
            $messages[] = "$addedCount 1 Loksabha added";
        }
        if ($deletedCount > 0) {
            $messages[] = "$deletedCount 1 Loksabha deleted";
        }
        if (empty($messages)) {
            $messages[] = "No changes made";
        }

        return $this->response->setJSON(['success' => true, 'message' => implode(', ', $messages) . '.']);
    }


    /**
     * Export Active Office Bearers to CSV
     */
    public function exportActiveUsers()
    {
        $filename = 'active_office_bearers_' . date('Ymd') . '.csv';
        
        // Output headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for proper Excel encoding
        fputs($output, "\xEF\xBB\xBF");

        // output the column headings
        fputcsv($output, array(
            'ID', 'First Name', 'Last Name', 'Email', 'Mobile', 'Alternate Mobile',
            'Date of Birth', 'Gender', 'Father Name', 'Mother Name', 'Aadhaar Number',
            // Appointment Location
            'Appointment State', 'Appointment District', 'Appointment MLA Area', 'Appointment Block', 'Appointment Sector',
            'Appointment 1LS', 'Appointment 2LS', 'Appointment 3LS', 'Appointment 4LS',
            // Personal Address
            'Personal State', 'Personal District', 'Address Line 1', 'Address Line 2', 'Pin Code',
            'Organ', 'Level', 'Post',
            'Status', 'Added By', 'Created At', 'Updated At'
        ));

        // fetch the data
        $builder = $this->userModel;
        // Join Appointments
        $builder->join('appointments', 'appointments.user_id = users.id', 'inner');
        // Join admins to exclude them
        $builder->join('admins', 'admins.mobile = users.mobile', 'left');

        $builder->select('
            users.id, users.first_name, users.last_name, users.email, users.mobile, users.alternate_mobile,
            users.date_of_birth, users.gender, users.father_name, users.mother_name, users.aadhaar_number,
            
            app_states.name as app_state_name,
            app_districts.name as app_district_name,
            app_mla_area.name as mla_area_name,
            app_blocks.name as block_name,
            app_sectors.name as sector_name,
            app_ls.name as ls_name,
            app_2ls.Name as 2ls_name,
            app_3ls.name as 3ls_name,
            app_4ls.Name as 4ls_name,
 
            personal_states.name as personal_state_name,
            personal_districts.name as personal_district_name,
            users.address_line1, users.address_line2, users.pin_code,
            
            organs.name as organ_name,
            levels.name as level_name,
            COALESCE(alp.name, clp.name, glp.name, mlp.name) as post_name,
            appointments.status, users.added_by_name, appointments.created_at, appointments.updated_at
        ');

        // Joins for APPOINTMENT locations (from appointments table)
        $builder->join('states as app_states', 'app_states.id = appointments.state_id', 'left');
        $builder->join('districts as app_districts', 'app_districts.id = appointments.district_id', 'left');
        $builder->join('mla_area as app_mla_area', 'app_mla_area.id = appointments.mla_area_id', 'left');
        $builder->join('blocks as app_blocks', 'app_blocks.id = appointments.block_id', 'left');
        $builder->join('sectors as app_sectors', 'app_sectors.id = appointments.sector_id', 'left');
        
        $builder->join('ls as app_ls', 'app_ls.id = appointments.ls_id', 'left');
        $builder->join('2ls as app_2ls', 'app_2ls.id = appointments.2ls_id', 'left');
        $builder->join('3ls as app_3ls', 'app_3ls.id = appointments.3ls_id', 'left');
        $builder->join('4ls as app_4ls', 'app_4ls.id = appointments.4ls_id', 'left');

        // Joins for PERSONAL locations (from users table)
        $builder->join('states as personal_states', 'personal_states.id = users.state_id', 'left');
        $builder->join('districts as personal_districts', 'personal_districts.id = users.district_id', 'left');

        $builder->join('organs', 'organs.id = appointments.organ_id', 'left');
        $builder->join('levels', 'levels.id = appointments.level_id', 'left');

        $builder->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left');
        $builder->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 15', 'left');
        $builder->join('managerial_level_posts as mlp', 'mlp.id = appointments.post_id AND appointments.level_id BETWEEN 16 AND 17', 'left');

        $builder->where('appointments.status', 'approved')
                ->where('admins.id IS NULL');
        
        $users = $builder->get()->getResultArray();

        // loop over the rows, outputting them
        foreach ($users as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}