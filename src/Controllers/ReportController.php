<?php
namespace App\Controllers;

class ReportController 
{
    public function index() 
    {
        echo "Listing all reports...";
    }
    
    public function show(array $urlVariables) 
    {
        echo "Viewing Report ID: " . htmlspecialchars($urlVariables['reportId']);
    }
}