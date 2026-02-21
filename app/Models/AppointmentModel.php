<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'user_id', 'level_id', 'post_id', 'organ_id', 'front_id',
        'state_id', 'district_id', 'block_id', 'sector_id',
        'village_id', 'circle_id',
        'mla_area_id', 'ls_id', '2ls_id', '3ls_id', '4ls_id',
        'status', 'added_by', 'added_by_name', 'edited_by', 'edited_by_name'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get appointments for a user with details
     */
    /**
     * Get appointments for a user with details
     */
    public function getUserAppointments($userId)
    {
        // Join logic can be added here if needed to get names directly
        return $this->where('user_id', $userId)->findAll();
    }

    /**
     * Get validated appointment details (names) for a user
     */
    public function getDetailsByUserId($userId)
    {
        $builder = $this->builder();
        $builder->select('
            appointments.id as appointment_id,
            appointments.status as appointment_status,
            
            app_states.name as committee_state_name,
            app_districts.name as committee_district_name,
            app_blocks.name as committee_block_name,
            
            levels.name as level_name,
            organs.name as organ_name,
            COALESCE(alp.name, clp.name, glp.name) as post_name
        ');
        
        // Joins for Appointment Location
        $builder->join('states as app_states', 'app_states.id = appointments.state_id', 'left');
        $builder->join('districts as app_districts', 'app_districts.id = appointments.district_id', 'left');
        $builder->join('blocks as app_blocks', 'app_blocks.id = appointments.block_id', 'left');
        
        // Joins for Organ/Level/Post
        $builder->join('levels', 'levels.id = appointments.level_id', 'left');
        $builder->join('organs', 'organs.id = appointments.organ_id', 'left');
        $builder->join('action_level_posts as alp', 'alp.id = appointments.post_id AND appointments.level_id BETWEEN 1 AND 5', 'left');
        $builder->join('constituency_level_posts as clp', 'clp.id = appointments.post_id AND appointments.level_id BETWEEN 6 AND 10', 'left');
        $builder->join('governing_level_posts as glp', 'glp.id = appointments.post_id AND appointments.level_id BETWEEN 11 AND 16', 'left');

        // We ideally want the 'approved' appointment, or the latest one if not approved yet (for pending payment etc)
        // Adjust logic based on context. For ID cards etc we want approved. For payment we want pending.
        // Let's return the latest one and let controller filter by status if needed, 
        // OR better yet, just return the latest one and let the caller check status.
        return $builder->where('user_id', $userId)
                       ->orderBy('appointments.created_at', 'DESC')
                       ->get()->getRowArray();
    }
}
