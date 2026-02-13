<?php namespace App\Models;

use CodeIgniter\Model;

class UserPostApplicationModel extends Model
{
    protected $table = 'user_post_applications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 
        'level_id', 
        'post_id', 
        'application_status', // e.g., submitted, approved, rejected, payment_pending
        'applied_at',         // Timestamp of application
        'reviewed_by',        // Admin user_id who reviewed
        'reviewed_at',        // Timestamp of review
        'remarks'             // Admin remarks
    ];
    protected $useTimestamps = true; // Assumes created_at and updated_at fields exist
}