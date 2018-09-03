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









/*
* Initializing the Community Notice custom post type
*/
 
function community_notice_post_type() {
 
// Set UI labels for Home page post type
    $labels = array(
        'name'                => _x( 'Community Notices', 'Post Type General Name' ),
        'singular_name'       => _x( 'Notice', 'Post Type Singular Name' ),
        'menu_name'           => __( 'Notices' ),
        'parent_item_colon'   => __( 'Parent Notice' ),
        'all_items'           => __( 'All Notices' ),
        'view_item'           => __( 'View Notice' ),
        'add_new_item'        => __( 'Add New Notice' ),
        'add_new'             => __( 'Add New' ),
        'edit_item'           => __( 'Edit Notice' ),
        'update_item'         => __( 'Update Notice' ),
        'search_items'        => __( 'Search Notices' ),
        'not_found'           => __( 'Not Found' ),
        'not_found_in_trash'  => __( 'Not found in Trash' ),
    );
     
// Set other options for Community Notices post type
     
    $args = array(
        'label'               => __( 'communitynotices' ),
        'description'         => __( 'Being Boss Community Notices' ),
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
    register_post_type( 'communitynotices', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'community_notice_post_type', 0 );
add_action( 'cmb2_admin_init', 'cmb2_communitynotices_metabox' );
/**
 * Define the metabox and field configurations.
 */
function cmb2_communitynotices_metabox() {
    // Start with an underscore to hide fields from custom fields list
    $prefix = 'bbcn_';
    /**
     * Initiate the metabox
     */
    $bbcn = new_cmb2_box( array(
        'id'            => 'bbcn_metabox',
        'title'         => __( 'Community Notice Details', 'cmb2' ),
        'object_types'  => array( 'communitynotices', ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );
    $bbcn->add_field( array(
            'name'    => 'Short summary',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'short_summary',
            'type'    => 'textarea_small',
    ) );
    $bbcn->add_field( array(
            'name'    => 'Header text',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'header_text',
            'type'    => 'text',
    ) );
    $bbcn->add_field( array(
            'name'    => 'Link',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'link',
            'type'    => 'text',
    ) );
    $bbcn->add_field( array(
            'name'    => 'Link Label',
            'desc'    => '',
            'default' => '',
            'id'      => $prefix . 'link_label',
            'type'    => 'text',
    ) );
    $bbcn->add_field( array(
        'name'    => 'BG Color Picker',
        'id'      => $prefix . 'bg_color',
        'type'    => 'colorpicker',
        'default' => '#ffffff',
        'attributes' => array(
            'data-colorpicker' => json_encode( array(
                // Iris Options set here as values in the 'data-colorpicker' array
                'palettes' => array( '#FFF200', '#EC008C', '#00AEEF', '#7C7C7C', '#252525', '#D3D3D3' ),
            ) ),
        ),
    ) );
    $bbcn->add_field( array(
        'name'    => 'Text Color Picker',
        'id'      => $prefix . 'text_color',
        'type'    => 'colorpicker',
        'default' => '#252525',
        'attributes' => array(
            'data-colorpicker' => json_encode( array(
                // Iris Options set here as values in the 'data-colorpicker' array
                'palettes' => array( '#FFF200', '#EC008C', '#00AEEF', '#7C7C7C', '#252525', '#D3D3D3' ),
            ) ),
        ),
    ) );

    $bbcn->add_field( array(
        'name'             => 'Button Color',
        'desc'             => 'Select an option',
        'id'               => $prefix . 'button_color',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => 'yellow-pink',
        'options'          => array(
            'yellow-pink' => __( 'Yellow / Pink Hover', 'cmb2' ),
            'yellow-blue'   => __( 'Yellow / Blue Hover', 'cmb2' ),
            'black-pink'     => __( 'Black / Pink Hover', 'cmb2' ),
            'pink-black'   => __( 'Pink / Black Hover', 'cmb2' ),
            'blue-black'     => __( 'Blue / Black Hover', 'cmb2' ),
        ),
    ) );

}







/*
    Hide Display Name field on profile page.
*/
function ffl_show_user_profile($user)
{
?>
<script>
    jQuery(document).ready(function() {
        jQuery('#display_name').parent().parent().hide();
    });
</script>
<?php
}
add_action( 'show_user_profile', 'ffl_show_user_profile' );
add_action( 'edit_user_profile', 'ffl_show_user_profile' );

/*
    Fix first last on profile saves.
*/
function ffl_save_extra_profile_fields( $user_id ) 
{
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    //set the display name
    $display_name = trim($_POST['first_name'] . " " . $_POST['last_name']);
    if(!$display_name)
        $display_name = $_POST['user_login'];
        
    $_POST['display_name'] = $display_name;
    
    $args = array(
            'ID' => $user_id,
            'display_name' => $display_name
    );   
    wp_update_user( $args ) ;
}
add_action( 'personal_options_update', 'ffl_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'ffl_save_extra_profile_fields' );

/*
    Fix first last on register.
*/
function ffl_fix_user_display_name($user_id)
{
    //set the display name
    $info = get_userdata( $user_id );
               
    $display_name = trim($info->first_name . ' ' . $info->last_name);
    if(!$display_name)
        $display_name = $info->user_login;
               
    $args = array(
            'ID' => $user_id,
            'display_name' => $display_name
    );
   
    wp_update_user( $args ) ;
}
add_action("user_register", "ffl_fix_user_display_name", 20);

/*
    Settings Page
*/
function ffl_settings_menu_item()
{
    add_options_page('Force First Last', 'Force First Last', 'manage_options', 'ffl_settings', 'ffl_settings_page');
}
add_action('admin_menu', 'ffl_settings_menu_item', 20);

//affiliates page (add new)
function ffl_settings_page()
{
    if(!empty($_REQUEST['updateusers']) && current_user_can("manage_options"))
    {
        global $wpdb;
        $user_ids = $wpdb->get_col("SELECT ID FROM $wpdb->users");
        
        foreach($user_ids as $user_id)
        {
            ffl_fix_user_display_name($user_id);         
            set_time_limit(30);         
        }
        
        ?>
        <p><?php echo count($user_ids);?> users(s) fixed.</p>
        <?php
    }
    
    ?>
    <p>The <em>Force First and Last Name as Display Name</em> plugin will only fix display names at registration or when a profile is updated.</p>
    <p>If you just activated this plugin, please click on the button below to update the display names of your existing users.</p>
    <p><a href="?page=ffl_settings&updateusers=1" class="button-primary">Update Existing Users</a></p>
    <p><strong>WARNING:</strong> This may take a while! If you have a bunch of users or a slow server, <strong>this may hang up or cause other issues with your site</strong>. Use at your own risk.</p>    
    <?php
}




?>
