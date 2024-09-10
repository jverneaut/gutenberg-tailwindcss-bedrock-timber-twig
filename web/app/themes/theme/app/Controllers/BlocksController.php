<?php

namespace App\Controllers;

use Timber\Timber;

class BlocksController
{
    public function __construct()
    {
        $this->registerBlocks();
    }

    private function registerBlocks()
    {
        add_action('init', function () {
            $blocks = array_filter(glob(__DIR__ . '/../../public/blocks/**/*'), 'is_dir');

            foreach ($blocks as $block) {
                register_block_type($block);
            }
        });
    }
}
