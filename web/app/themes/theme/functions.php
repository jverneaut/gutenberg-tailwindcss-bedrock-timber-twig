<?php

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Timber\Timber;

use App\Controllers\ThemeController;
use App\Controllers\TwigController;
use App\Controllers\BlocksController;
use App\Controllers\ErrorHandlerController;

Timber::init();

add_action('after_setup_theme', function () {
    new ThemeController();
    new TwigController();
    new BlocksController();
    new ErrorHandlerController();
});
