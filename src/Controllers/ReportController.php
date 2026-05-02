<?php
namespace App\Controllers;

class ReportController 
{
    public function index() 
    {
        echo "Listing all reports...";
    }
    
    public function show($vars) 
    {
        echo "Viewing Report ID: " . htmlspecialchars($vars['reportId']);
    }
}