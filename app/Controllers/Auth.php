<?php

namespace App\Controllers;

use App\Models\BlockModel;
use App\Models\DistrictModel;
use App\Models\MlaAreaModel;
use App\Models\PostModel;
use App\Models\OrganModel;
use App\Models\StateModel;
use App\Models\LevelModel; // Added for LevelModel
use App\Models\MlaLsHierarchyModel;// Added for LS hierarchy
use App\Models\AdminModel; // Added for Admin Login

class Auth extends BaseController
{    protected $session;
    protected $userModel;
    protected $stateModel;
    protected $districtModel;
    protected $mlaAreaModel;
    protected $blockModel;
    protected $postModel;
    protected $organModel;
    protected $levelModel; // Added for LevelModel
    protected $mlaLsHierarchyModel;
    protected $adminModel;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = service('session');

        // Initialize models
        $this->userModel = new \App\Models\UserModel();
        $this->stateModel = new \App\Models\StateModel();
        $this->districtModel = new \App\Models\DistrictModel();
        $this->mlaAreaModel = new \App\Models\MlaAreaModel();
        $this->blockModel = new \App\Models\BlockModel();
        $this->postModel = new \App\Models\PostModel();
        $this->organModel = new \App\Models\OrganModel();
        $this->levelModel = new \App\Models\LevelModel(); // Initialize LevelModel
        $this->mlaLsHierarchyModel = new \App\Models\MlaLsHierarchyModel();
        $this->adminModel = new \App\Models\AdminModel();
    }    
    
    public function index()
    {
        return redirect()->to('auth/login');
    }

    public function login()
    {
        if ($this->session->get('isLoggedIn')) {
            $userId = $this->session->get('user_id');
            $user = $this->userModel->find($userId);
            if ($user) {
                return redirect()->to('dashboard');
            }
            $this->session->destroy();
        }
        
        return view('auth/login');
    }

    public function register()
    {
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('dashboard');
        }

        $requested_step_from_url = $this->request->getGet('step');
        $final_step_to_display = null;

        if ($requested_step_from_url !== null) {
           if (in_array($requested_step_from_url, ['1', '2', '3', '4'], true)) {
                $final_step_to_display = (int)$requested_step_from_url;
            } elseif ($requested_step_from_url === 'summary') {
                $final_step_to_display = 5; 
            } else {
                $final_step_to_display = 1;
            }
            $this->session->set('registration_step', $final_step_to_display);
        } else {
            $current_step_in_session = $this->session->get('registration_step');
            if ($current_step_in_session !== null) {
                 if (in_array($current_step_in_session, [1, 2, 3, 4, 5], true)) {
                    $final_step_to_display = (int)$current_step_in_session;
                } else {
                    $final_step_to_display = 1;
                    $this->session->set('registration_step', 1);
                    $this->session->remove('registration_data'); 
                }
            } else {
                $final_step_to_display = 1;
                $this->session->set('registration_step', 1);
            }
        }
        
        $data = [
            'step' => $final_step_to_display,
            'savedData' => $this->session->get('registration_data') ?? []
        ];
        
        if (session()->has('errors')) {
            $data['errors'] = session('errors');
        }

        switch ($final_step_to_display) {
            case 1:
                return view('auth/register_step1', $data);
            case 2:
                return view('auth/register_step2', $data);
            case 3:
                return view('auth/register_step3', $data);
            case 4: 
                return $this->applyForPostForm(); 
            case 5: 
               // Enhance data for summary view with names
                $summaryData = $data['savedData'];

                // Fetch Organ Name
                if (!empty($summaryData['organ_id'])) {
                    $organ = $this->organModel->find($summaryData['organ_id']);
                    $summaryData['organ_name'] = $organ ? $organ['name'] : 'N/A (ID: '.$summaryData['organ_id'].')';
                }

                // Fetch State Name
                if (!empty($summaryData['state_id'])) {
                    $state = $this->stateModel->find($summaryData['state_id']);
                    $summaryData['state_name'] = $state ? $state['name'] : 'N/A (ID: '.$summaryData['state_id'].')';
                }
                // Fetch District Name
                if (!empty($summaryData['district_id'])) {
                    $district = $this->districtModel->find($summaryData['district_id']);
                    $summaryData['district_name'] = $district ? $district['name'] : 'N/A (ID: '.$summaryData['district_id'].')';
                }
                // Fetch MLA Area Name
                if (!empty($summaryData['mla_area_id'])) {
                    $mlaArea = $this->mlaAreaModel->find($summaryData['mla_area_id']);
                    $summaryData['mla_area_name'] = $mlaArea ? $mlaArea['name'] : 'N/A (ID: '.$summaryData['mla_area_id'].')';
                }
                // Fetch Block Name
                if (!empty($summaryData['block_id'])) {
                    $block = $this->blockModel->find($summaryData['block_id']);
                    $summaryData['block_name'] = $block ? $block['name'] : 'N/A (ID: '.$summaryData['block_id'].')';
                }
                // Fetch LS Hierarchy Names if IDs are present
                // This assumes MlaLsHierarchyModel can fetch by individual LS IDs or you adjust logic
                // For simplicity, if mla_area_id was used to get these, we'd re-fetch or store names earlier.
                // Given the schema stores ls_id, 2ls_id etc., we'd ideally have separate tables for these
                // or use the MlaLsHierarchyModel to look up names by these IDs.
                // For now, we'll assume the names were also stored in session if needed for display,
                // or we show IDs. If names are crucial, MlaLsHierarchyModel needs methods to get names by these specific IDs.
                // A simpler approach if names were fetched with IDs from mla_area_id:
                if (!empty($summaryData['mla_area_id'])) {
                    $lsHierarchy = $this->mlaLsHierarchyModel->getHierarchyByMlaAreaId((int)$summaryData['mla_area_id']);
                    $summaryData['ls_name'] = $lsHierarchy['LS_Name'] ?? ($summaryData['ls_id'] ?? 'N/A');
                    $summaryData['2ls_name'] = $lsHierarchy['two_LS_Name'] ?? ($summaryData['2ls_id'] ?? 'N/A');
                    $summaryData['3ls_name'] = $lsHierarchy['three_LS_Name'] ?? ($summaryData['3ls_id'] ?? 'N/A');
                    $summaryData['4ls_name'] = $lsHierarchy['four_LS_Name'] ?? ($summaryData['4ls_id'] ?? 'N/A');
                }
                // Fetch Level Name
                if (!empty($summaryData['level_id'])) {
                    $level = $this->levelModel->find($summaryData['level_id']);
                    $summaryData['level_name'] = $level ? $level['name'] : 'N/A (ID: '.$summaryData['level_id'].')';
                    // Fetch Post Name only if level_id is valid
                    if ($level && !empty($summaryData['post_id'])) {
                        $post = $this->postModel->getPostByIdAndLevel($summaryData['post_id'], $summaryData['level_id']);
                        $summaryData['post_name'] = $post ? $post['name'] : 'N/A (ID: '.$summaryData['post_id'].')';
                    }
                }
                $data['savedData'] = $summaryData; // Update savedData with fetched names
                return view('auth/register_summary', $data);
            default:
                $this->session->set('registration_step', 1);
                $this->session->remove('registration_data');
                $data['step'] = 1; 
                return view('auth/register_step1', $data);
        }
    }

    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check Admins Table First
        $admin = $this->adminModel->where('email', $email)->first();

        if ($admin && password_verify($password, $admin['password'])) {
            $sessionData = [
                'admin_id'   => $admin['id'],
                'name'       => $admin['name'],
                'email'      => $admin['email'],
                'admin_type' => $admin['admin_type'],
                'isLoggedIn' => true,
                'isAdmin'    => true
            ];
            $this->session->set($sessionData);
            return redirect()->to('admin');
        }

        // If not admin, check Users Table
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email not found');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid password');
        }

        $sessionData = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'isLoggedIn' => true,
            'isAdmin' => false // Regular users are never admins now
        ];
        
        $this->session->set($sessionData);
        return redirect()->to('dashboard');
    }    
    
    public function authenticateMobile()
    {
        $mobile = $this->request->getPost('mobile');
        $password = $this->request->getPost('password');

        log_message('debug', '---------------------');
        log_message('debug', 'Login attempt started');
        log_message('debug', 'Mobile: ' . $mobile);
        // log_message('debug', 'Raw password length: ' . strlen($password)); // Be careful logging passwords
        // log_message('debug', 'Raw password first char: ' . substr($password, 0, 1));

        $rules = [
            'mobile' => 'required|min_length[10]',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            log_message('debug', 'Validation failed: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('error', 'Please check your credentials');
        }

        // Check Admins Table First for Mobile Login
        $admin = $this->adminModel->where('mobile', $mobile)->first();

        if ($admin && password_verify($password, $admin['password'])) {
             $sessionData = [
                'admin_id'   => $admin['id'],
                'name'       => $admin['name'],
                'mobile'     => $admin['mobile'],
                'email'      => $admin['email'],
                'admin_type' => $admin['admin_type'],
                'isLoggedIn' => true,
                'isAdmin'    => true
            ];
            $this->session->set($sessionData);
            return redirect()->to('admin');
        }

        $user = $this->userModel->where('mobile', $mobile)->first();

        if ($user === null) {
            log_message('debug', 'User not found with mobile: ' . $mobile);
            return redirect()->back()->withInput()->with('error', 'Mobile number not found');
        }

        // log_message('debug', 'Found user: ' . json_encode($user));
        // log_message('debug', 'Stored password hash: ' . $user['password']);
        
        $rawPassword = $password;
        $trimmedPassword = trim($password);
        
        $rawVerify = password_verify($rawPassword, $user['password']);
        $trimmedVerify = password_verify($trimmedPassword, $user['password']);
        
        // log_message('debug', 'Raw password verify result: ' . ($rawVerify ? 'true' : 'false'));
        // log_message('debug', 'Trimmed password verify result: ' . ($trimmedVerify ? 'true' : 'false'));

        if (!$rawVerify && !$trimmedVerify) {
            log_message('debug', 'Invalid password for user: ' . $mobile);
            return redirect()->back()->withInput()->with('error', 'Invalid password');
        }

        $sessionData = [
            'user_id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'mobile' => $user['mobile'],
            'email' => $user['email'],
            'isLoggedIn' => true,
            'isAdmin' => false
        ];
        
        $this->session->set($sessionData);
        log_message('debug', 'Session data set for user: ' . $user['id']);

        return redirect()->to('dashboard');
    }

    public function applyForPostForm()
    {
        $registrationData = $this->session->get('registration_data');

        if (!$registrationData || 
            !isset($registrationData['first_name']) || 
            !isset($registrationData['aadhaar_number']) || 
            !isset($registrationData['password'])) {       
           $this->session->setFlashdata('error', 'Please complete previous registration steps first.');
            return redirect()->to('auth/register?step=1');
        }        $data['savedData'] = $registrationData; 
        $data['organs'] = $this->organModel->orderBy('name', 'ASC')->findAll();
        $data['step'] = 4;

        helper('form');
        return view('auth/apply_for_post_form', $data);
    }    
    
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('auth/login')->with('success', 'Successfully logged out');
    }

    public function processStep1()
    {
        helper(['form', 'array']);

        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name'  => 'required|min_length[2]',
            'date_of_birth' => 'required|valid_date',
            'gender'     => 'required|in_list[male,female,other]',
            'photo'      => 'uploaded[photo]|max_size[photo,2048]|mime_in[photo,image/jpg,image/jpeg,image/png]',
            'mobile'     => 'required|min_length[10]|is_unique[users.mobile]',
            'alternate_mobile' => 'permit_empty|min_length[10]',
            'email'      => 'required|valid_email|is_unique[users.email]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $photo = $this->request->getFile('photo');
        $photoPath = null;
        
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            if ($photo->move(FCPATH . 'uploads/photos', $newName)) { 
                $photoPath = $newName;
            }
        }

        $stepData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
            'gender' => $this->request->getPost('gender'),
            'photo' => $photoPath,
            'mobile' => $this->request->getPost('mobile'),
            'alternate_mobile' => $this->request->getPost('alternate_mobile'),
            'email' => $this->request->getPost('email')
        ];

        $this->saveRegistrationData($stepData);
        $this->session->set('registration_step', 2);
        
        return redirect()->to('auth/register');
    }

    public function processStep2()
    {
        helper(['form', 'array']);
        // Log all POST data received in this step
        log_message('debug', 'Auth::processStep2 - POST Data: ' . json_encode($this->request->getPost()));


        $rules = [
           'state_id'    => 'required|numeric|is_not_unique[states.id]',
           'district_id' => 'required|numeric|is_not_unique[districts.id]', // Assuming districts table
           'mla_area_id' => 'required|numeric|is_not_unique[mla_area.id]', // Assuming mla_area table
           'block_id'    => 'required|numeric|is_not_unique[blocks.id]',    // Assuming blocks table
           'father_name' => 'required',
           'mother_name' => 'required',
           'aadhaar_number' => 'required|numeric|exact_length[12]|is_unique[users.aadhaar_number]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $stateId = $this->request->getPost('state_id');

        // Assuming the frontend (register_step2.php JavaScript) will be updated
        // to submit ls_id, 2ls_id, 3ls_id, 4ls_id, potentially from hidden fields
        // populated when an MLA area is selected.
        $stepData = [
            'state_id'       => $stateId,
            'district_id'    => $this->request->getPost('district_id'),
            'mla_area_id'    => $this->request->getPost('mla_area_id'),
            'block_id'       => $this->request->getPost('block_id'),
            'ls_id'          => $this->request->getPost('ls_id'),       // Expecting ls_id from form
            '2ls_id'         => $this->request->getPost('2ls_id'),      // Expecting 2ls_id from form
            '3ls_id'         => $this->request->getPost('3ls_id'),      // Expecting 3ls_id from form
            '4ls_id'         => $this->request->getPost('4ls_id'),      // Expecting 4ls_id from form
            'father_name' => $this->request->getPost('father_name'),
            'mother_name' => $this->request->getPost('mother_name'),
            'aadhaar_number' => $this->request->getPost('aadhaar_number')
        ];

        // If you also need to store the names for LS hierarchy for summary display without re-fetching,
        // you could add them here, e.g., $stepData['ls_name'] = $this->request->getPost('ls_name_from_hidden_field');
        // However, the user table schema only has IDs.

        $this->saveRegistrationData($stepData);
        $this->session->set('registration_step', 3);
        
        return redirect()->to('auth/register');
    }

    public function processStep3()
    {
        helper(['form', 'array']);

        $rules = [
            'address_line1' => 'required',
            'address_line2' => 'permit_empty',
            'pin_code' => 'required|numeric|min_length[6]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $stepData = [
            'address_line1' => $this->request->getPost('address_line1'),
            'address_line2' => $this->request->getPost('address_line2'),
            'pin_code' => $this->request->getPost('pin_code'),
            'password' => $this->request->getPost('password')
        ];

        $this->saveRegistrationData($stepData);
        $this->session->set('registration_step', 4);
        
        return redirect()->to('auth/register'); 
    }

    public function processPostApplication()
    {
        helper(['form', 'array']);

        // Log received data for debugging
        log_message('debug', 'processPostApplication POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', "processPostApplication - Raw level_id: " . $this->request->getPost('level_id') . ", Raw post_id: " . $this->request->getPost('post_id'));

        $registrationData = $this->session->get('registration_data');

        if (!$registrationData) {
            $this->session->setFlashdata('error', 'Your session has expired. Please start the registration again.');
            return redirect()->to('auth/register?step=1');
        }

        $rules = [
            'organ_id' => 'required|numeric|is_not_unique[organs.id]',
            'level_id' => 'required|numeric|is_not_unique[levels.id]',
            'post_id'  => 'required|numeric' // Basic validation for post_id initially
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Custom validation for post_id against the dynamic table
        $levelId = $this->request->getPost('level_id');
        $postId = $this->request->getPost('post_id');

        // Use the PostModel to determine the correct table for the given level_id
        // We need an instance of PostModel if not already available, or a method to get table name
        $postModel = new \App\Models\PostModel(); // Ensure PostModel is used
        $postTable = $postModel->getTableNameForLevel((int)$levelId);

        if (!$postTable) {
            return redirect()->back()->withInput()->with('errors', ['level_id' => 'Invalid level selected, cannot determine post table.']);
        }

        $db = \Config\Database::connect();
        $query = $db->table($postTable)->where('id', $postId)->get();
        if ($query->getNumRows() == 0) {
            return redirect()->back()->withInput()->with('errors', ['post_id' => 'The selected post does not exist for the chosen level.']);
        }

        $postData = [
            'organ_id' => $this->request->getPost('organ_id'),
            'level_id' => $this->request->getPost('level_id'),
            'post_id'  => $this->request->getPost('post_id')  // Ensure post_id is saved
        ];
        
        $this->saveRegistrationData($postData); 
        $this->session->set('registration_step', 5); 
        return redirect()->to('auth/register');
    }

    public function confirmRegistration()
    {
        $registrationData = $this->session->get('registration_data');
        
        if (!$registrationData) {
            return redirect()->to('auth/register');
        }

        // 1. Prepare User Data (Personal)
        $userData = [
            'first_name'       => $registrationData['first_name'] ?? null,
            'last_name'        => $registrationData['last_name'] ?? null,
            'date_of_birth'    => $registrationData['date_of_birth'] ?? null,
            'gender'           => $registrationData['gender'] ?? null,
            'photo'            => $registrationData['photo'] ?? null,
            'mobile'           => $registrationData['mobile'] ?? null,
            'alternate_mobile' => $registrationData['alternate_mobile'] ?? null,
            'email'            => $registrationData['email'] ?? null,
            'father_name'      => $registrationData['father_name'] ?? null,
            'mother_name'      => $registrationData['mother_name'] ?? null,
            'aadhaar_number'   => $registrationData['aadhaar_number'] ?? null,
            'address_line1'    => $registrationData['address_line1'] ?? null,
            'address_line2'    => $registrationData['address_line2'] ?? null,
            'pin_code'         => $registrationData['pin_code'] ?? null,
            // Personal address info
            'state_id'    => $registrationData['state_id'] ?? null,
            'district_id' => $registrationData['district_id'] ?? null,
            'mla_area_id' => $registrationData['mla_area_id'] ?? null,
            'block_id'    => $registrationData['block_id'] ?? null,
        ];

        // Hash Password
        if (isset($registrationData['password'])) {
            $userData['password'] = password_hash($registrationData['password'], PASSWORD_DEFAULT);
        }

        // 2. Prepare Appointment Data (Jurisdiction)
        $appointmentData = [
            'level_id'    => $registrationData['level_id'] ?? null,
            'post_id'     => $registrationData['post_id'] ?? null,
            'organ_id'    => $registrationData['organ_id'] ?? null,
            // Map Step 2 location data to Appointment
            'state_id'    => $registrationData['state_id'] ?? null,
            'district_id' => $registrationData['district_id'] ?? null,
            'mla_area_id' => $registrationData['mla_area_id'] ?? null,
            'block_id'    => $registrationData['block_id'] ?? null,
            'ls_id'       => $registrationData['ls_id'] ?? null,
            '2ls_id'      => $registrationData['2ls_id'] ?? null,
            '3ls_id'      => $registrationData['3ls_id'] ?? null,
            '4ls_id'      => $registrationData['4ls_id'] ?? null,
            'status'      => 'pending', 
        ];
        
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert User
            if ($this->userModel->insert($userData)) {
                $newUserId = $this->userModel->getInsertID();

                // Insert Appointment
                $appointmentData['user_id'] = $newUserId;
                $appointmentModel = new \App\Models\AppointmentModel();
                $appointmentModel->insert($appointmentData);

                $db->transComplete();

                if ($db->transStatus() === false) {
                     throw new \Exception("Transaction failed");
                }

                $this->session->remove('registration_step');
                $this->session->remove('registration_data');
                
                $user = $this->userModel->find($newUserId);

                if ($user) {
                    $sessionData = [
                        'user_id'    => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name'  => $user['last_name'],
                        'mobile'     => $user['mobile'],
                        'email'      => $user['email'],
                        'isLoggedIn' => true,
                        'isAdmin'    => (bool)($user['is_admin'] ?? false)
                    ];
                    $this->session->set($sessionData);
                    $this->session->set('application_details', ['user_id' => $user['id'], 'amount' => 500]); 
                    return redirect()->to('payment/initiate')->with('success', 'Registration successful! Please complete the payment.');
                } else {
                    log_message('error', 'Failed to fetch newly registered user for auto-login: ID ' . $newUserId);
                    $this->session->setFlashdata('error', 'Registration completed, but auto-login failed. Please try logging in manually.');
                    return redirect()->to('auth/login');
                }
            } else {
                 throw new \Exception(json_encode($this->userModel->errors()));
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Registration exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred during registration. Please try again.');
        }
    }

    private function saveRegistrationData($newData)
    {
        $existingData = $this->session->get('registration_data') ?? [];
        $registrationData = array_merge($existingData, $newData);
        $this->session->set('registration_data', $registrationData);
    }

    public function checkEmail($email = null)
    {
        if (!$email) {
            return $this->response->setJSON(['available' => false]);
        }
        $exists = $this->userModel->where('email', $email)->first();
        return $this->response->setJSON(['available' => !$exists]);
    }

    public function getDistricts($stateId = null)
    {
        if (!$stateId) { // Consistent empty response format
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }
        $districts = $this->districtModel
            ->where('state_id', $stateId)
           ->orderBy('name', 'ASC') // Ensure ordering
            ->select('id, name') // Select only necessary fields
            ->findAll() ?? [];
        return $this->response->setJSON(['success' => true, 'data' => $districts]);
    }

    public function getMlaAreas($districtId = null)
    {
        if (!$districtId) { // Consistent empty response format
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }
        $mlaAreas = $this->mlaAreaModel
            ->where('district_id', $districtId) // Corrected casing to match MlaAreaModel and schema
            ->orderBy('name', 'ASC') // Ensure ordering
            ->select('id, name') // Select only necessary fields
            ->findAll() ?? [];
        return $this->response->setJSON(['success' => true, 'data' => $mlaAreas]);
     }

    public function getBlocks($districtId = null) 
    {
        if (!$districtId) { // Consistent empty response format
            return $this->response->setJSON(['success' => true, 'data' => []]);
        }
        $blocks = $this->blockModel
            ->where('district_id', $districtId)
            ->orderBy('name', 'ASC') // Ensure ordering
            ->select('id, name') // Select only necessary fields
            ->findAll() ?? [];
        return $this->response->setJSON(['success' => true, 'data' => $blocks]);
    }
    public function getLsHierarchyByMlaArea($mlaAreaId = null)
    {
       log_message('debug', 'Auth::getLsHierarchyByMlaArea - Received mlaAreaId: ' . var_export($mlaAreaId, true) . ', isAJAX: ' . ($this->request->isAJAX() ? 'true' : 'false'));
        if (!$this->request->isAJAX() || empty($mlaAreaId)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'error' => 'Invalid request.']);
        }

        $hierarchyData = $this->mlaLsHierarchyModel->getHierarchyByMlaAreaId((int)$mlaAreaId);

        if ($hierarchyData) {
            $lsData = [
                'ls_id' => $hierarchyData['ls_id'] ?? null,
                'ls_name' => $hierarchyData['LS_Name'] ?? '',
                'two_ls_id' => $hierarchyData['two_ls_id'] ?? null,
                'two_ls_name' => $hierarchyData['two_LS_Name'] ?? '',
                'three_ls_id' => $hierarchyData['three_ls_id'] ?? null,
                'three_ls_name' => $hierarchyData['three_LS_Name'] ?? '',
                'four_ls_id' => $hierarchyData['four_ls_id'] ?? null,
                'four_ls_name' => $hierarchyData['four_LS_Name'] ?? '',
            ];
            return $this->response->setJSON(['success' => true, 'data' => $lsData]);
        }
        // Return a structure with null IDs and empty names if no data
        return $this->response->setJSON(['success' => true, 'data' => [
            'ls_id' => null, 'ls_name' => '',
            'two_ls_id' => null, 'two_ls_name' => '',
            'three_ls_id' => null, 'three_ls_name' => '',
            'four_ls_id' => null, 'four_ls_name' => ''
        ]]);
    }

    public function getStates()
    {
        try {
            log_message('debug', 'getStates() method called');
            $states = $this->stateModel
                ->orderBy('name', 'ASC')
                ->select('id, name')
                ->findAll();
            return $this->response->setJSON([
                'success' => true,
                'data' => $states ?? []
            ]);
        } catch (\Throwable $e) { 
            log_message('error', 'Error in getStates(): ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                                ->setJSON([
                                    'success' => false,
                                    'error' => 'Could not load states. ' . $e->getMessage()
                                ]);
        } 
    }

    public function getLevels()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Forbidden']);
        }
        // $this->levelModel is already initialized in __construct
        // $levels = $this->levelModel->orderBy('id', 'ASC')->select('id, name')->findAll();
        $levels = $this->levelModel
            // ->where('id >=', 5) // Temporarily remove or adjust filter for testing
            ->orderBy('id', 'ASC')
            ->select('id, name')->findAll();
        return $this->response->setJSON(['success' => true, 'data' => $levels ?? []]);
    }

    public function getPostsByLevel($levelId = null)
    {
        if (!$this->request->isAJAX() || empty($levelId)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid request. Level ID is required.']);
        }
        // $this->postModel is already initialized in __construct
        $posts = $this->postModel->getPostsByLevel((int)$levelId); // Changed to use getPostsByLevel
        if (empty($posts)) {
            return $this->response->setJSON(['success' => true, 'data' => [], 'message' => 'No posts found for this level.']);
        }
        return $this->response->setJSON(['success' => true, 'data' => $posts]);
    }


    // TODO: Add a method to check post availability if needed

}
