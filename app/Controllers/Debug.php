<?php
namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Debug extends Controller {
    public function lastUserId() {
        $userModel = new UserModel();
        $user = $userModel->orderBy('id', 'DESC')->first();
        echo json_encode($user);
    }
}
