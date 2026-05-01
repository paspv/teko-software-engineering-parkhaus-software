<?php
namespace App\Controllers;

class ParkingGarageController {
    public function entry($vars) {
        echo "Entering Garage: " . htmlspecialchars($vars['garageId']);
    }
    
    public function exit($vars) {
        echo "Exiting Garage: " . htmlspecialchars($vars['garageId']);
    }
}