<?php

namespace App\Services;

use Timber\Timber;

class MenuBuilder
{
    public function __construct()
    {
        return $this;
    }

    public function addMenu(string $location, string $description)
    {
        add_action('init', function () use ($location, $description) {
            register_nav_menu($location, $description);

            add_filter('timber/context', function (array $context) use ($location) {
                $context['menus'][$location] = Timber::get_menu($location);
                return $context;
            });
        });

        return $this;
    }
}
