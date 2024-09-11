<?php

namespace App;

use App\Services\MenuBuilder;

class App
{
    public function __construct(
        MenuBuilder $menuBuilder,
    ) {
        $menuBuilder
            ->addMenu('primary', 'Primary Navigation')
            ->addMenu('footer', 'Footer Navigation');
    }
}
