<?php

namespace App;

use App\App;
use App\Controllers\ThemeController;
use App\Controllers\TwigController;
use App\Controllers\BlocksController;
use App\Controllers\ErrorHandlerController;
use Timber\Timber;

class Bootstrap
{
    public function __construct(
        ThemeController $themeController,
        TwigController $twigController,
        BlocksController $blocksController,
        ErrorHandlerController $errorHandlerController,
        App $app
    ) {
        Timber::init();
    }
}
