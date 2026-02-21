<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Debug extends Controller
{
    public function listColumns($table)
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($table);
        echo "<pre>";
        print_r($fields);
        echo "</pre>";
    }
}
