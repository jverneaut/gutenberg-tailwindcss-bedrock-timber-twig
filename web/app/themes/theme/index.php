<?php

use Timber\Timber;

$context = Timber::context();

$templates = ['templates/index.twig'];

if (is_home()) {
    array_unshift($templates, 'templates/front-page.twig', 'templates/home.twig');
}

Timber::render($templates, $context);
