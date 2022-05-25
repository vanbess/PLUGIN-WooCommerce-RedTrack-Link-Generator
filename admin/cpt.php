<?php

/**
 * RedTrack link CPT
 */
function sbwc_register_redtrack_link_cpt()
{

    /**
     * Post Type: RedTrack Links.
     */

    $labels = [
        "name"                     => __("RedTrack Links", "woocommerce"),
        "singular_name"            => __("RedTrack Link", "woocommerce"),
        "menu_name"                => __("RedTrack Links", "woocommerce"),
        "all_items"                => __("All RedTrack Links", "woocommerce"),
        "add_new"                  => __("Add new", "woocommerce"),
        "add_new_item"             => __("Add new RedTrack Link", "woocommerce"),
        "edit_item"                => __("Edit RedTrack Link", "woocommerce"),
        "new_item"                 => __("New RedTrack Link", "woocommerce"),
        "view_item"                => __("View RedTrack Link", "woocommerce"),
        "view_items"               => __("View RedTrack Links", "woocommerce"),
        "search_items"             => __("Search RedTrack Links", "woocommerce"),
        "not_found"                => __("No RedTrack Links found", "woocommerce"),
        "not_found_in_trash"       => __("No RedTrack Links found in trash", "woocommerce"),
        "parent"                   => __("Parent RedTrack Link: ", "woocommerce"),
        "featured_image"           => __("Featured image for this RedTrack Link", "woocommerce"),
        "set_featured_image"       => __("Set featured image for this RedTrack Link", "woocommerce"),
        "remove_featured_image"    => __("Remove featured image for this RedTrack Link", "woocommerce"),
        "use_featured_image"       => __("Use as featured image for this RedTrack Link", "woocommerce"),
        "archives"                 => __("RedTrack Link archives", "woocommerce"),
        "insert_into_item"         => __("Insert into RedTrack Link", "woocommerce"),
        "uploaded_to_this_item"    => __("Upload to this RedTrack Link", "woocommerce"),
        "filter_items_list"        => __("Filter RedTrack Links list", "woocommerce"),
        "items_list_navigation"    => __("RedTrack Links list navigation", "woocommerce"),
        "items_list"               => __("RedTrack Links list", "woocommerce"),
        "attributes"               => __("RedTrack Links attributes", "woocommerce"),
        "name_admin_bar"           => __("RedTrack Link", "woocommerce"),
        "item_published"           => __("RedTrack Link published", "woocommerce"),
        "item_published_privately" => __("RedTrack Link published privately.", "woocommerce"),
        "item_reverted_to_draft"   => __("RedTrack Link reverted to draft.", "woocommerce"),
        "item_scheduled"           => __("RedTrack Link scheduled", "woocommerce"),
        "item_updated"             => __("RedTrack Link updated.", "woocommerce"),
        "parent_item_colon"        => __("Parent RedTrack Link:", "woocommerce"),
    ];

    $args = [
        "label"                 => __("RedTrack Links", "woocommerce"),
        "labels"                => $labels,
        "description"           => "CPT to list/display generated RedTrack Links",
        "public"                => true,
        "publicly_queryable"    => true,
        "show_ui"               => true,
        "show_in_rest"          => true,
        "rest_base"             => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace"        => "wp/v2",
        "has_archive"           => false,
        "show_in_menu"          => "redtrack-settings",
        "show_in_nav_menus"     => true,
        "delete_with_user"      => false,
        "exclude_from_search"   => false,
        "capability_type"       => "post",
        "map_meta_cap"          => true,
        "hierarchical"          => false,
        "can_export"            => false,
        "rewrite"               => ["slug" => "redtrack-link", "with_front" => true],
        "query_var"             => true,
        "supports"              => ["title"],
        "show_in_graphql"       => false,
    ];

    register_post_type("redtrack-link", $args);
}

add_action('init', 'sbwc_register_redtrack_link_cpt');
