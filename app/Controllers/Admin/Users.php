<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\UserPostApplicationModel;

class Users extends BaseController
{
    public function usersRequest()
    {
        // Ensure admin is logged in (typically handled by a filter)
        // if (!session()->get('isAdminLoggedIn')) {
        //     return redirect()->to('/admin/login');
        // }

        $userModel = new UserModel();
        $data['applications'] = $userModel->getPendingOfficeBearerApplications();
        
        return view('admin/users_request_list', $data);
    }

    public function viewApplication($applicationId)
    {
        $userModel = new UserModel(); // UserModel has the getApplicationDetails method
        $data['application'] = $userModel->getApplicationDetails($applicationId);

        if (!$data['application']) {
            session()->setFlashdata('error', 'Application not found.');
            return redirect()->to('admin/usersRequest');
        }
        // Create a detailed view, e.g., admin/application_detail
        // For now, just showing it can be fetched.
        // return view('admin/application_detail', $data); 
        echo "<h1>Application Details (ID: {$applicationId})</h1><pre>";
        print_r($data['application']);
        echo "</pre><a href='".site_url('admin/usersRequest')."'>Back to list</a>";
    }

    public function approveApplication($applicationId)
    {
        $applicationModel = new UserPostApplicationModel();
        if ($applicationModel->update($applicationId, ['application_status' => 'approved', 'reviewed_at' => date('Y-m-d H:i:s')])) {
            // Optionally update user's role in 'users' table
            session()->setFlashdata('success', "Application ID {$applicationId} approved successfully.");
        } else {
            session()->setFlashdata('error', "Failed to approve application ID {$applicationId}.");
        }
        return redirect()->to('admin/usersRequest');
    }

    public function rejectApplication($applicationId)
    {
        $applicationModel = new UserPostApplicationModel();
        // You might want to add a remarks field for rejection reason
        if ($applicationModel->update($applicationId, ['application_status' => 'rejected', 'reviewed_at' => date('Y-m-d H:i:s')])) {
            session()->setFlashdata('success', "Application ID {$applicationId} rejected successfully.");
        } else {
            session()->setFlashdata('error', "Failed to reject application ID {$applicationId}.");
        }
        return redirect()->to('admin/usersRequest');
    }
}