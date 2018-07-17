<?php


add_action( 'cmb2_admin_init', 'cmb2_bbpage_metaboxes' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_bbpage_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = 'bbpage_';

	/**
	 * Initiate the metabox
	 */
	$bbpage = new_cmb2_box( array(
		'id'            => 'bbpage_metabox',
		'title'         => __( 'Being Boss - Page Specific Options', 'cmb2' ),
		'object_types'  => array( 'page', ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // Keep the metabox closed by default
	) );

	$bbpage->add_field( array(
    		'name'    => 'Header Image',
    		'desc'    => 'Upload an image or enter an URL.',
    		'id'      => $prefix . 'header_image',
    		'type'    => 'file',
    		// Optional:
    		'options' => array(
        		'url' => false, // Hide the text input for the url
    		),
    		'text'    => array(
        		'add_upload_file_text' => 'Add File' // Change upload button text. Default: "Add or Upload File"
    		),
    		// query_args are passed to wp.media's library query.
    		'query_args' => array(
        		'type' => 'application/pdf', // Make library only display PDFs.
    		),
	) );

	$bbpage->add_field( array(
    		'name' => 'Header Text Overlay',
    		'desc' => 'Text overlay for page header image (HTML-enabled)',
    		'default' => '',
    		'id' => $prefix . 'header_text',
    		'type' => 'textarea_small'
	) );

	$bbpage->add_field( array(
    		'name' => 'Hide Footer Subscribe Section',
    		'desc' => 'Check box to hide footer subscribe section',
    		'id'   => $prefix . 'hide_subscribe',
    		'type' => 'checkbox',
	) );

	$bbpage->add_field( array(
    		'name'    => 'Page Top Padding',
    		'desc'    => 'Optional padding for top of content area',
    		'default' => '0',
    		'id'      => $prefix . 'top_padding',
    		'type'    => 'text',
	) );

	$bbpage->add_field( array(
    		'name' => 'Page Specific CSS',
    		'desc' => 'Optional custom CSS for this individual page',
    		'default' => '',
    		'id' => $prefix . 'page_css',
    		'type' => 'textarea_small'
	) );

}






add_action( 'after_setup_theme', 'new_image_sizes' );
function new_image_sizes() {
    add_image_size( 'archive-thumb', 350 ); // 350 pixels wide (and unlimited height)
}




/**
 * Add User Profile Fields
 */

$extra_fields =  array( 
	array( 'bb_facebook', __( 'BB Facebook', 'rc_cucm' ), true ),
	array( 'bb_twitter', __( 'BB Twitter', 'rc_cucm' ), true ),
	array( 'bb_googleplus', __( 'BB Google+', 'rc_cucm' ), true ),
	array( 'bb_linkedin', __( 'BB LinkedIn', 'rc_cucm' ), false ),
	array( 'bb_pinterest', __( 'BB Pinterest', 'rc_cucm' ), false ),
	array( 'bb_instagram', __( 'BB Instagram', 'rc_cucm' ), false ),
	array( 'bb_youtube', __( 'BB Youtube', 'rc_cucm' ), false ),
	array( 'bb_website', __( 'BB Website', 'rc_cucm' ), false ),
	array( 'bb_customavatar', __( 'BB Custom Avatar', 'rc_cucm' ), false )
);

// Use the user_contactmethods to add new fields
add_filter( 'user_contactmethods', 'rc_add_user_contactmethods' );


/**
 * Add custom users custom contact methods
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function rc_add_user_contactmethods( $user_contactmethods ) {

	// Get fields
	global $extra_fields;
	
	// Display each fields
	foreach( $extra_fields as $field ) {
		if ( !isset( $contactmethods[ $field[0] ] ) )
    		$user_contactmethods[ $field[0] ] = $field[1];
	}

    // Returns the contact methods
    return $user_contactmethods;
}














/**
* Display custom post count in User dashboard list
*/
add_action('manage_users_columns','yoursite_manage_users_columns');
function yoursite_manage_users_columns($column_headers) {
    unset($column_headers['posts']);
    $column_headers['custom_posts'] = 'Assets';
    return $column_headers;
}

add_action('manage_users_custom_column','yoursite_manage_users_custom_column',10,3);
function yoursite_manage_users_custom_column($custom_column,$column_name,$user_id) {
    if ($column_name=='custom_posts') {
        $counts = _yoursite_get_author_post_type_counts();
        $custom_column = array();
        if (isset($counts[$user_id]) && is_array($counts[$user_id]))
            foreach($counts[$user_id] as $count) {
                $link = admin_url() . "edit.php?post_type=" . $count['type']. "&author=".$user_id;
                // admin_url() . "edit.php?author=" . $user->ID;
                $custom_column[] = "\t<tr><th><a href={$link}>{$count['label']}</a></th><td>{$count['count']}</td></tr>";
            }
        $custom_column = implode("\n",$custom_column);
        if (empty($custom_column))
            $custom_column = "<th>[none]</th>";
        $custom_column = "<table>\n{$custom_column}\n</table>";
    }
    return $custom_column;
}

function _yoursite_get_author_post_type_counts() {
    static $counts;
    if (!isset($counts)) {
        global $wpdb;
        global $wp_post_types;
        $sql = <<<SQL
        SELECT
        post_type,
        post_author,
        COUNT(*) AS post_count
        FROM
        {$wpdb->posts}
        WHERE 1=1
        AND post_type IN ('post','articles', 'events', 'library', 'optins', 'resources', 'sponsors', 'webinars')
        AND post_status IN ('publish','pending', 'draft')
        GROUP BY
        post_type,
        post_author
SQL;
        $posts = $wpdb->get_results($sql);
        foreach($posts as $post) {
            $post_type_object = $wp_post_types[$post_type = $post->post_type];
            if (!empty($post_type_object->label))
                $label = $post_type_object->label;
            else if (!empty($post_type_object->labels->name))
                $label = $post_type_object->labels->name;
            else
                $label = ucfirst(str_replace(array('-','_'),' ',$post_type));
            if (!isset($counts[$post_author = $post->post_author]))
                $counts[$post_author] = array();
            $counts[$post_author][] = array(
                'label' => $label,
                'count' => $post->post_count,
                'type' => $post->post_type,
                );
        }
    }
    return $counts;
}












/**
* Include Custom Post Types on author pages
*/

function author_custom_post_types( $query ) {
  if( is_author() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'articles'
		));
	  return $query;
	}
}
add_filter( 'pre_get_posts', 'author_custom_post_types' );









function my_search_filter($query) {
  if ( !is_admin() && $query->is_main_query() ) {
    if ($query->is_search) {
      $query->set('post_type', array( 'post', 'articles' ) );
    }
  }
}
add_action('pre_get_posts','my_search_filter');






add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );








/**
* Add the images to the special submenu -> the submenu items with the parent with 'pt-special-dropdown' class.
*
* @param array $items List of menu objects (WP_Post).
* @param array $args  Array of menu settings.
* @return array
*/
function add_images_to_special_submenu( $items ) {
    $special_menu_parent_ids = array();

    foreach ( $items as $item ) {
        if ( in_array( 'bb-special-dropdown', $item->classes, true ) && isset( $item->ID ) ) {
            $special_menu_parent_ids[] = $item->ID;
        }

        if ( in_array( $item->menu_item_parent, $special_menu_parent_ids ) && has_post_thumbnail( $item->object_id ) ) {
            $item->title = sprintf(
                '%1$s %2$s',
                get_the_post_thumbnail( $item->object_id, 'medium', array( 'alt' => esc_attr( $item->title ) ) ),
                $item->title
            );
        }
    }

    return $items;
}

add_filter( 'wp_nav_menu_objects', 'add_images_to_special_submenu' );







/**
* Woocommerce + Drip - send name as two separate fields
*/
add_filter( 'wcdrip_custom_fields', 'drip_add_first_last_fields', 10, 5 );
function drip_add_first_last_fields( $filters, $email, $lifetime_value, $products, $order ) {
    unset( $filters['name'] );
    $filters['first_name'] = $order->billing_first_name;
    $filters['last_name'] = $order->billing_last_name;
    return $filters;
}









/*
* Initializing the Home Page custom post type
*/
 
function home_post_type() {
 
// Set UI labels for Home page post type
    $labels = array(
        'name'                => _x( 'Home Posts', 'Post Type General Name' ),
        'singular_name'       => _x( 'Home Post', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Home Posts' ),
        'parent_item_colon'   => __( 'Parent Home Post' ),
        'all_items'           => __( 'All Home Posts' ),
        'view_item'           => __( 'View Home Post' ),
        'add_new_item'        => __( 'Add New Home Post' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Home Post' ),
        'update_item'         => __( 'Update Home Post' ),
        'search_items'        => __( 'Search Home Posts' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Home page post type
     
    $args = array(
        'label'               => __( 'homeposts' ),
        'description'         => __( 'Being Boss Home Posts' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 25,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'homeposts', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'home_post_type', 0 );
add_action( 'cmb2_admin_init', 'cmb2_homeposts_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_homeposts_metabox() {
    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbhome_';
    /**
     * Initiate the metabox
     */
    $bbhome = new_cmb2_box( array(
        'id'            => 'bbhome_metabox',
        'title'         => __( 'Home Post Details', 'cmb2' ),
        'object_types'  => array( 'homeposts', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
    $bbhome->add_field( array(
            'name'    => 'Link',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'link',
            'type'    => 'text',
    ) );
    $bbhome->add_field( array(
            'name'    => 'Top Label',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'top_label',
            'type'    => 'text',
    ) );
    $bbhome->add_field( array(
            'name'    => 'Link Label',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'link_label',
            'type'    => 'text',
    ) );
    $bbhome->add_field( array(
            'name'    => 'Order',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'order',
            'type'    => 'text',
    ) );
}










?>
