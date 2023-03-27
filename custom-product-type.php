<?php
/*
Plugin Name: Plugin for Custom Post Types
Plugin URI: example.com
Description: This is a Plugin to Create custom post type
Version: 1.0
Author: Moshood Bolaji Salaudeen
Author URI:example.com
Textdomain: Moscharito
License: GPLv2
*/

// Register the Products post type
function products_post_type() {
    $labels = array(
      'name' => 'Products',
      'singular_name' => 'Product',
      'add_new' => 'Add New',
      'add_new_item' => 'Add New Product',
      'edit_item' => 'Edit Product',
      'new_item' => 'New Product',
      'all_items' => 'All Products',
      'view_item' => 'View Product',
      'search_items' => 'Search Products',
      'not_found' => 'No Products found',
      'not_found_in_trash' => 'No Products found in Trash',
      'parent_item_colon' => '',
      'menu_name' => 'Products'
    );
  
    $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'product'),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title', 'editor', 'thumbnail', 'custom-fields')
    );
  
    register_post_type('products', array(
        'has_archive' => true
      ));  
    // Register Brand taxonomy
    $labels = array(
      'name' => 'Brands',
      'singular_name' => 'Brand',
      'search_items' => 'Search Brands',
      'all_items' => 'All Brands',
      'parent_item' => 'Parent Brand',
      'parent_item_colon' => 'Parent Brand:',
      'edit_item' => 'Edit Brand',
      'update_item' => 'Update Brand',
      'add_new_item' => 'Add New Brand',
      'new_item_name' => 'New Brand Name',
      'menu_name' => 'Brand'
    );
  
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      'rewrite' => array('slug' => 'brand')
    );
  
    register_taxonomy('brand', 'products', $args);
  
    // Register Category taxonomy
    $labels = array(
      'name' => 'Categories',
      'singular_name' => 'Category',
      'search_items' => 'Search Categories',
      'all_items' => 'All Categories',
      'parent_item' => 'Parent Category',
      'parent_item_colon' => 'Parent Category:',
      'edit_item' => 'Edit Category',
      'update_item' => 'Update Category',
      'add_new_item' => 'Add New Category',
      'new_item_name' => 'New Category Name',
      'menu_name' => 'Category'
    );
  
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
      'rewrite' => array('slug' => 'category')
    );
    
    register_taxonomy('category', 'products', $args);
    
    // Register API ID meta field
    register_meta('post', 'api_id', array(
    'type' => 'string',
    'description' => 'API ID'
    ));
    
    // Register Price meta field
    register_meta('post', 'price', array(
    'type' => 'string',
    'description' => 'Price'
    ));
    
    // Register Discount Percentage meta field
    register_meta('post', 'discount_percentage', array(
    'type' => 'string',
    'description' => 'Discount Percentage'
    ));
    
    // Register Rating meta field
    register_meta('post', 'rating', array(
    'type' => 'string',
    'description' => 'Rating'
    ));
    
    // Register Stock meta field
    register_meta('post', 'stock', array(
    'type' => 'string',
    'description' => 'Stock'
    ));
    }
    add_action('init', 'products_post_type');
    
    // Pull data from API and save as posts
    function pull_data_from_api() {
    $url = 'https://dummyjson.com/products';
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
    return;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);
    
    foreach ($data as $product) {
    $post_args = array(
    'post_type' => 'products',
    'post_title' => $product->title,
    'post_content' => $product->description,
    'post_status' => 'publish',
    'tax_input' => array(
    'brand' => array($product->brand),
    'category' => array($product->category)
    )
    ); 
    $post_id = wp_insert_post($post_args);

if (!$post_id) {
  continue;
}

update_post_meta($post_id, 'api_id', $product->api_id);
update_post_meta($post_id, 'price', $product->price);
update_post_meta($post_id, 'discount_percentage', $product->discount_percentage);
update_post_meta($post_id, 'rating', $product->rating);
update_post_meta($post_id, 'stock', $product->stock); 
}
}
add_action('wp_loaded', 'pull_data_from_api');
    ?>

