<?php

namespace App\Controllers;

class ThemeController
{
    public function __construct()
    {
        $this->addThemeSupports();
        $this->enqueueThemeAssets();
    }

    private function addThemeSupports()
    {
        $supportedFeatures = [
          'title-tag',
          'menus'
        ];

        foreach ($supportedFeatures as $supportedFeature) {
            add_theme_support($supportedFeature);
        }
    }

    private function enqueueThemeAssets()
    {
        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('theme-styles', get_template_directory_uri() . '/public/app.css');
            wp_enqueue_script('theme-runtime', get_template_directory_uri() . '/public/runtime.js', [], null, true);
            wp_enqueue_script('theme-scripts', get_template_directory_uri() . '/public/app.js', [], null, true);
        });
    }
}
