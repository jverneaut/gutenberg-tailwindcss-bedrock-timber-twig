<?php

namespace App\Controllers;

use Symfony\Component\ErrorHandler\Debug;

class ErrorHandlerController
{
    public function __construct()
    {
        if (defined('WP_DEBUG') && WP_DEBUG && !is_admin()) {
            Debug::enable();
        }
    }
}
