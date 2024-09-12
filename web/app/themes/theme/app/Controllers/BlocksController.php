<?php

namespace App\Controllers;

use Timber\Timber;

class BlocksController
{
    public function __construct()
    {
        $this->registerBlocks();
        $this->setupTwigBlocksRenderer();
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

    private function setupTwigBlocksRenderer()
    {
        add_filter('block_type_metadata_settings', function ($settings, $metadata) {
            if (! empty($metadata['render'])) {
                $template_path = wp_normalize_path(
                    realpath(
                        dirname($metadata['file']) . '/' .
                        remove_block_asset_path_prefix($metadata['render'])
                    )
                );

                if (str_ends_with($template_path, '.twig')) {
                    $settings['render_callback'] = function ($attributes, $content, $block) use ($template_path) {
                        $context = Timber::context();

                        $content = Timber::compile($template_path, array_merge(
                            $context,
                            [
                                'attributes' => $attributes,
                                'content' => $content,
                                'block' => $block,
                            ]
                        ));

                        return $content;
                    };
                };
            };

            return $settings;
        }, 10, 2);
    }
}
