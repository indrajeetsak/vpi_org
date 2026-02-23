<?php
require 'public/index.php';
$db = \Config\Database::connect();
$block = $db->table('blocks')->like('name', 'baj')->get()->getResultArray();
print_r($block);

$users = $db->table('appointments')
    ->select('users.first_name, users.last_name, action_level_posts.name as post_name, blocks.name as block_name')
    ->join('users', 'users.id = appointments.user_id')
    ->join('blocks', 'blocks.id = appointments.block_id')
    ->join('action_level_posts', 'action_level_posts.id = appointments.post_id')
    ->like('blocks.name', 'baj')
    ->get()->getResultArray();
    
print_r($users);
