<?php 
/**
 * ==============================================
 * Custom Post Types
 * ==============================================
 */

class Medival_Custom_Post_Type {
/**
 * Construct Function
 */
public function __construct() {
    add_action('init', array($this, 'medival_serices_custom_post'));
    // add_action('init', array($this, 'medival_members_custom_post'));
}


/**
 * Custom Post -- Testimonials
 * @author Rasadin
 * @since 1.0.0
 */
public function medival_serices_custom_post() {
	$labels = array(
	  'name'               => _x( 'Testimonials', 'post type general name' ),
	  'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
	  'add_new'            => _x( 'Add New', 'testmonial' ),
	  'add_new_item'       => __( 'Add New Testimonial' ),
	  'edit_item'          => __( 'Edit Testimonial' ),
	  'new_item'           => __( 'New Testimonial' ),
	  'all_items'          => __( 'All Testimonials' ),
	  'view_item'          => __( 'View Testimonial' ),
	  'search_items'       => __( 'Search Testimonials' ),
	  'not_found'          => __( 'No testimonial found' ),
	  'not_found_in_trash' => __( 'No testimonial found in the Trash' ), 
	  'parent_item_colon'  => '',
	  'menu_name'          => 'Testimonials'
	);
	$args = array(
	  'labels'        => $labels,
	  'description'   => 'Holds testimonials and testimonial specific data',
	  'public'        => true,
	  'menu_position' => 5,
	  'supports'      => array( 'title', 'editor','taxonomies', 'thumbnail', 'excerpt', 'comments' ),
	  'has_archive'   => true,
	);
	register_post_type( 'testimonials', $args ); 
}
}
new Medival_Custom_Post_Type();
