<?php

namespace App\Services;

class CPTBuilder
{
    public function __construct()
    {
        return $this;
    }

    public function addCPT(string $singular, string $plural, $args = [], $labels = []): CPTBuilder
    {
        add_action('init', function () use ($singular, $plural, $args, $labels) {
            $default_labels = [
              'name'                  => _x("{$plural}", 'Post type general name', 'textdomain'),
              'singular_name'         => _x("{$singular}", 'Post type singular name', 'textdomain'),
              'menu_name'             => _x("{$plural}", 'Admin Menu text', 'textdomain'),
              'name_admin_bar'        => _x("{$singular}", 'Add New on Toolbar', 'textdomain'),
              'add_new'               => __("Add New", 'textdomain'),
              'add_new_item'          => __("Add New {$singular}", 'textdomain'),
              'new_item'              => __("New {$singular}", 'textdomain'),
              'edit_item'             => __("Edit {$singular}", 'textdomain'),
              'view_item'             => __("View {$singular}", 'textdomain'),
              'all_items'             => __("All {$singular}s", 'textdomain'),
              'search_items'          => __("Search {$singular}s", 'textdomain'),
              'parent_item_colon'     => __("Parent {$singular}s:", 'textdomain'),
              'not_found'             => __("No {$singular}s found.", 'textdomain'),
              'not_found_in_trash'    => __("No {$singular}s found in Trash.", 'textdomain'),
              'featured_image'        => _x("{$singular} Cover Image", 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'textdomain'),
              'set_featured_image'    => _x("Set cover image", 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'textdomain'),
              'remove_featured_image' => _x("Remove cover image", 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'textdomain'),
              'use_featured_image'    => _x("Use as cover image", 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'textdomain'),
              'archives'              => _x("{$singular} archives", 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'textdomain'),
              'insert_into_item'      => _x("Insert into {$singular}", 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'textdomain'),
              'uploaded_to_this_item' => _x("Uploaded to this {$singular}", 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'textdomain'),
              'filter_items_list'     => _x("Filter {$singular}s list", 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'textdomain'),
              'items_list_navigation' => _x("{$plural} list navigation", 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'textdomain'),
              'items_list'            => _x("{$plural} list", 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'textdomain'),
            ];

            $labels = array_merge($default_labels, $labels);

            $default_args = [
              'labels' => $labels,
              'public'             => true,
              'publicly_queryable' => true,
              'show_ui'            => true,
              'show_in_menu'       => true,
              'query_var'          => true,
              'capability_type'    => 'post',
              'has_archive'        => true,
              'hierarchical'       => false,
              'menu_position'      => null,
              'show_in_rest'       => true,
              'supports'           => ['title', 'editor', 'author'],
              'menu_icon'          => 'dashicons-admin-post',
            ];

            $args = array_merge($default_args, $args);

            register_post_type($singular, $args);
        });

        return $this;
    }
}
