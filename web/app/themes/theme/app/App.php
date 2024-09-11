<?php

namespace App;

use App\Services\MenuBuilder;
use App\Services\CPTBuilder;

class App
{
    public function __construct(
        MenuBuilder $menuBuilder,
        CPTBuilder $cptBuilder,
    ) {
        $menuBuilder
            ->addMenu('primary', 'Primary Navigation')
            ->addMenu('footer', 'Footer Navigation');

        // $cptBuilder
        //     ->addCPT('Employee', 'Employees');
    }
}
