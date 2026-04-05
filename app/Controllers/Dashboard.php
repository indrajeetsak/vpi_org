<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OrganModel;
use App\Models\LevelModel;
use App\Models\PostModel;
use App\Models\BlockModel;
use App\Models\StateModel;
use App\Models\DistrictModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $organModel;
    protected $levelModel;
    protected $postModel;
    protected $blockModel;
    protected $stateModel;
    protected $districtModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->organModel = new OrganModel();
        $this->levelModel = new LevelModel();
        $this->postModel = new PostModel();
        $this->blockModel = new BlockModel();
        $this->stateModel = new StateModel();
        $this->districtModel = new DistrictModel();
    }    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('auth/login')->with('error', 'Session expired. Please login again.');
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('auth/login')->with('error', 'User not found. Please login again.');
        }

        $data = [
            'user' => $user,
            'title' => 'Dashboard',
            'activeMenu' => 'dashboard'
        ];

        // Fetch Organ Name
        $data['organ_name'] = 'Not Assigned'; // Default
        if (!empty($user['organ_id'])) {
            $organ = $this->organModel->find($user['organ_id']);
            if ($organ) {
                $data['organ_name'] = $organ['name'];
            }
        }

        // Fetch Level Name
        $data['level_name'] = 'Not Assigned'; // Default
        if (!empty($user['level_id'])) {
            $level = $this->levelModel->find($user['level_id']);
            if ($level) {
                $data['level_name'] = $level['name'];
                // Fetch Post Name only if level_id is valid and post_id exists
                if (!empty($user['post_id'])) {
                    $post = $this->postModel->getPostByIdAndLevel($user['post_id'], $user['level_id']);
                    $data['post_name'] = $post ? $post['name'] : 'Post Not Found';
                } else {
                    $data['post_name'] = 'Not Assigned';
                }
            }
        }
        return view('dashboard/index', $data);
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel
            ->select('users.*, appointments.organ_id, appointments.level_id, appointments.post_id, appointments.status as appointment_status')
            ->join('appointments', 'appointments.user_id = users.id', 'left')
            ->find($userId);

        if (!$user) {
            session()->destroy();
            return redirect()->to('auth/login')->with('error', 'User not found. Please login again.');
        }

        $data['user'] = $user;
        $data['level_name'] = 'Not Assigned';
        $data['post_name'] = 'Not Assigned';
        $data['organ_name'] = 'Not Assigned';

        // Fetch Organ Name
        if (!empty($user['organ_id'])) {
            $organ = $this->organModel->find($user['organ_id']);
            $data['organ_name'] = $organ ? $organ['name'] : 'Not Assigned';
        }

        // Fetch Level Name
        if (!empty($user['level_id'])) {
            $level = $this->levelModel->find($user['level_id']);
            if ($level) {
                $data['level_name'] = $level['name'];
                // Fetch Post Name only if level_id is valid and post_id exists
                if (!empty($user['post_id'])) {
                    $post = $this->postModel->getPostByIdAndLevel($user['post_id'], $user['level_id']);
                    $data['post_name'] = $post ? $post['name'] : 'Post Not Found';
                }
            }
        }
        helper('form');
        return view('dashboard/profile', $data);
    }

    public function updateProfile()
    {
        // Handle profile update logic, validation, file uploads
        // Similar to processRegistration but for existing user
        if ($this->request->getMethod() === 'post') {
            $userId = session()->get('user_id');
            // Validation rules...
            // $this->userModel->update($userId, $this->request->getPost());
            session()->setFlashdata('success', 'Profile updated successfully.');
            return redirect()->to('dashboard/profile');
        }
        return redirect()->to('dashboard/profile');
    }

    // Removed private getAppointmentData helper, using Model instead.

    public function idCard()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user) {
            // Personal Address Names
            if (!empty($user['state_id'])) {
                $state = $this->stateModel->find($user['state_id']);
                $user['state_name'] = $state ? $state['name'] : 'Unknown';
            } else {
                $user['state_name'] = 'Not Available';
            }

            if (!empty($user['district_id'])) {
                $district = $this->districtModel->find($user['district_id']);
                $user['district_name'] = $district ? $district['name'] : 'Unknown';
            } else {
                $user['district_name'] = 'Not Available';
            }
            
            // Appointment Data
            $appointmentModel = new \App\Models\AppointmentModel();
            $appt = $appointmentModel->getDetailsByUserId($userId);
            
            if ($appt) {
                // Ensure we only show details if approved or relevant? 
                // ID Card should probably only be for approved.
                if ($appt['appointment_status'] === 'approved') {
                    $user = array_merge($user, $appt);
                }
            }
        }

        $data['user'] = $user;
        return view('dashboard/id_card', $data);
    }

    public function visitingCard()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user) {
             // Personal Address Names
            if (!empty($user['state_id'])) {
                $state = $this->stateModel->find($user['state_id']);
                $user['state_name'] = $state ? $state['name'] : 'Unknown';
            } else {
                $user['state_name'] = 'Not Available';
            }

            if (!empty($user['district_id'])) {
                $district = $this->districtModel->find($user['district_id']);
                $user['district_name'] = $district ? $district['name'] : 'Unknown';
            } else {
                $user['district_name'] = 'Not Available';
            }

            if (!empty($user['block_id'])) {
                $block = $this->blockModel->find($user['block_id']);
                $user['block_name'] = $block ? $block['name'] : 'Unknown';
            } else {
                $user['block_name'] = 'Not Available';
            }
            
            // Appointment Data
            $appointmentModel = new \App\Models\AppointmentModel();
            $appt = $appointmentModel->getDetailsByUserId($userId);
            
            if ($appt) {
                 if ($appt['appointment_status'] === 'approved') {
                    $user = array_merge($user, $appt);
                }
            }
        }

        $data['user'] = $user;
        return view('dashboard/visiting_card', $data);
    }

    public function letterHead()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if ($user) {
             // Personal Address Names
            if (!empty($user['state_id'])) {
                $state = $this->stateModel->find($user['state_id']);
                $user['state_name'] = $state ? $state['name'] : 'Unknown';
            } else {
                $user['state_name'] = 'Not Available';
            }

            if (!empty($user['district_id'])) {
                $district = $this->districtModel->find($user['district_id']);
                $user['district_name'] = $district ? $district['name'] : 'Unknown';
            } else {
                $user['district_name'] = 'Not Available';
            }

            if (!empty($user['block_id'])) {
                $block = $this->blockModel->find($user['block_id']);
                $user['block_name'] = $block ? $block['name'] : 'Unknown';
            } else {
                $user['block_name'] = 'Not Available';
            }
             
            // Appointment Data
            $appointmentModel = new \App\Models\AppointmentModel();
            $appt = $appointmentModel->getDetailsByUserId($userId);
            
            if ($appt) {
                 if ($appt['appointment_status'] === 'approved') {
                    $user = array_merge($user, $appt);
                }
            }
            
            $data['user'] = $user;
        }

        return view('dashboard/letter_head', $data);
    }
    
    // Application for Post
    public function applyForPostShowForm()
    {
        $data['organs'] = $this->organModel->findAll();
        // Levels and Posts will be loaded via AJAX
        helper('form');
        return view('dashboard/apply_for_post', $data);
    }

    public function processPostApplication()
    {
        $rules = [
            'organ' => 'required',
            'level' => 'required',
            'post'  => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate input (organ, level, post)
        // Check post availability
        // Update user's record (applied_organ_id, applied_level_id, applied_post_id, application_status = 'pending_payment')
        // $this->userModel->update(session()->get('user_id'), $updateData);
        // Redirect to payment initiation.
        session()->set('application_details', ['post_id' => $this->request->getPost('post_id'), 'amount' => 500]); // Example amount
        return redirect()->to('payment/initiate');
    }

    public function getLevelsByOrgan() {
        $organId = $this->request->getGet('organ_id');
        // For now, assume all levels are available for all organs. Refine if needed.
        return $this->response->setJSON($this->levelModel->findAll());
    }
    public function getPostsByOrganLevel() {
        $organId = $this->request->getGet('organ_id');
        $levelId = $this->request->getGet('level_id');
        return $this->response->setJSON($this->postModel->where('organ_id', $organId)->where('level_id', $levelId)->findAll());
    }
}