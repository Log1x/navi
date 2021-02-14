<?php

namespace App\Controllers;

use Sober\Controller\Controller;
use Log1x\Navi\Navi;

class App extends Controller
{
    public function navigation()
    {
        return new Navi()->build('primary_navigation');
    }
    
    # ...
}
