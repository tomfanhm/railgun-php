<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class GeneralController extends Controller
{
    public function home(): void
    {
        echo 'Home page';
    }
}
