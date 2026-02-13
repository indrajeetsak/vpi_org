<?php

namespace App\Controllers;

class Temp extends BaseController
{
    public function getStateId()
    {
        $db = \Config\Database::connect();
        $state = $db->table('states')->where('name', 'Uttar Pradesh')->get()->getRow();

        if ($state) {
            echo "The ID of Uttar Pradesh is: " . $state->id;
        } else {
            echo "Uttar Pradesh not found in the database.";
        }
    }
}
