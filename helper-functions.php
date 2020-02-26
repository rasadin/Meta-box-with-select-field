/**
 * Custom Post -- Add meta field for testimonials designation
 * @author Rasadin
 * @since 1.0.0
 */
function add_metabox_testimonials_designation() {
	add_meta_box(
		'testimonials_designation_metabox', // metabox ID, it also will be the HTML id attribute
		'Testimonal Information', // title
		'testimonials_designation_display_metabox', // this is a callback function, which will print HTML of our metabox
		'testimonials', // post type or post types in array
		'normal', // position on the screen where metabox should be displayed (normal, side, advanced)
		'default' // priority over another metaboxes on this page (default, low, high, core)
	);
}
add_action( 'admin_menu', 'add_metabox_testimonials_designation' );


/**
 * Custom Post --  Member Designation Input Field
 * @author Rasadin
 * @since 1.0.0
 */
function testimonials_designation_display_metabox( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'testimonials_designation_metabox_nonce' ); //needed for security reasons

	//text field
	//$designation = '<p><label>Designation: <input type="text" name="testimonials_designation_title" value="' . esc_attr( get_post_meta($post->ID, 'testimonials_designation_title',true) )  . '" /></label></p>';
	$company_name = '<p><label>Company: <input type="text" name="testimonials_company_name" value="' . esc_attr( get_post_meta($post->ID, 'testimonials_company_name',true) )  . '" /></label></p>';
	//$description = '<p><label>Description: <textarea  name="testimonials_description"> '.esc_attr( get_post_meta($post->ID, 'testimonials_description',true) ) .' </textarea></label></p>';
    
    ?>
    <p><label>Show/Hide?
    <select name="show_hide" id="show_hide">
      <option value="show"<?php selected( get_post_meta($post->ID,'show_hide',true ), 'show')?>>Show</option>
      <option value="hide"<?php selected( get_post_meta($post->ID,'show_hide',true), 'hide')?>>Hide</option>
    </select></label></p>
    <?php 

    // print all of this
	//echo $designation;
	echo $company_name;
   // echo $description;
 
	


}

/**
 * Custom Post --  Member Designation Input Field Value Save
 * @author Rasadin
 * @since 1.0.0
 */
function testimonials_designation_save_post_meta( $post_id, $post ) {
	// Security checks
	if ( !isset( $_POST['testimonials_designation_metabox_nonce'] ) 
	|| !wp_verify_nonce( $_POST['testimonials_designation_metabox_nonce'], basename( __FILE__ ) ) )
		return $post_id;
	
	//Check current user permissions
	$post_type = get_post_type_object( $post->post_type );
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
	
	// Do not save the data if autosave
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;
 
	if ($post->post_type == 'testimonials') { // define your own post type here
		//update_post_meta($post_id, 'testimonials_designation_title', sanitize_text_field( $_POST['testimonials_designation_title'] ) );
		update_post_meta($post_id, 'testimonials_designation_noindex', $_POST['testimonials_designation_noindex']);	
		update_post_meta($post_id, 'testimonials_company_name', sanitize_text_field( $_POST['testimonials_company_name'] ) );
		//update_post_meta($post_id, 'testimonials_description', sanitize_text_field( $_POST['testimonials_description'] ) );
        update_post_meta($post_id, 'show_hide', sanitize_text_field( $_POST['show_hide'] ) );

	}
	return $post_id;
}
add_action( 'save_post', 'testimonials_designation_save_post_meta', 10, 2 );



    /**
	 * Display all testimonials
	 * @author Rasadin
	 * @since 1.0.0
	 */
    function show_all_testimonials() {
		$the_query = new WP_Query( array(
			'post_type' => 'testimonials',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => 'show_hide',
                    'value' => 'show',
                )
        ) ,
        ));
        $return_html='';
		if ( $the_query->have_posts() ) : 
			$return_html = '<div class="testimonials-list">';
            foreach( $the_query->posts as $post ): 
                $my_post_content = apply_filters('the_content', get_post_field('post_content', $post->ID));

                if(get_the_post_thumbnail($post->ID) != ''){
                            $return_html .= '	
                                    <div class="webalive-testimonials-detail">
                                        <p> '. $my_post_content.'</p>
                                            <div class="webalive-testimonials-picture">'.get_the_post_thumbnail($post->ID).'</div>
                                        <div class="name-company">
                                            <p> '. get_the_title($post->ID).'</p>
                                            <span> '. get_post_meta($post->ID, 'testimonials_company_name', true ).'</span>
                                        </div>
                                    </div>
                            '; 
                }else{
                    $return_html .= '	
                    <div class="webalive-testimonials-detail">
                        <p> '. $my_post_content.'</p>
                            <div class="webalive-testimonials-picture"> 
                                 <img src="'.home_url('/wp-content/plugins/medival-core/assets/images/avatar.jpg').'">  
                            </div>
                        <div class="name-company">
                            <p> '. get_the_title($post->ID).'</p>
                            <span> '. get_post_meta($post->ID, 'testimonials_company_name', true ).'</span>
                        </div>
                    </div>
                '; 
                }         
		endforeach;
		$return_html .= '</div>';
		endif;
		// wp_reset_postdata(); 
		return $return_html;
    }
    add_shortcode('display_all_testimonials_shortcode','show_all_testimonials');	
