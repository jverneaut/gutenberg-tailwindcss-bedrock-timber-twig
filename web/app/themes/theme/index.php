<?php

use Timber\Timber;

$context = Timber::context();

$context['post'] = Timber::get_post();
$context['posts'] = Timber::get_posts();
$context['term'] = Timber::get_term();
$context['user'] = Timber::get_user();

$templates = ['templates/index.twig'];

if (is_front_page()) {
    array_unshift($templates, 'templates/front-page.twig');
}

if (is_home()) {
    array_unshift($templates, 'templates/home.twig');
}

if (is_singular()) {
    $post_type = get_post_type();
    array_unshift($templates, "templates/single-{$post_type}.twig", 'templates/single.twig');
}

if (is_archive()) {
    $post_type = get_post_type();
    array_unshift($templates, "templates/archive-{$post_type}.twig", 'templates/archive.twig');
}

if (is_search()) {
    array_unshift($templates, 'templates/search.twig');
}

if (is_404()) {
    array_unshift($templates, 'templates/404.twig');
}

if (is_page()) {
    $page_template = get_page_template_slug();
    if ($page_template) {
        array_unshift($templates, "templates/{$page_template}");
    }
    array_unshift($templates, 'templates/page.twig');
}

if (is_category()) {
    $category = get_queried_object();
    array_unshift($templates, "templates/category-{$category->slug}.twig", 'templates/category.twig');
}

if (is_tag()) {
    $tag = get_queried_object();
    array_unshift($templates, "templates/tag-{$tag->slug}.twig", 'templates/tag.twig');
}

if (is_tax()) {
    $taxonomy = get_queried_object();
    array_unshift($templates, "templates/taxonomy-{$taxonomy->taxonomy}.twig", 'templates/taxonomy.twig');
}

if (is_author()) {
    array_unshift($templates, 'templates/author.twig');
}

Timber::render($templates, $context);
