// Register Hook
add_filter( 'mycred_setup_hooks', 'register_my_custom_hook' );
function register_my_custom_hook( $installed )
{
	$installed['complete_profile'] = array(
		'title'       => __( '%plural% for Profile Completion', 'textdomain' ),
		'description' => __( 'This hook award / deducts points from users who fill out their first and last name.', 'textdomain' ),
		'callback'    => array( 'my_custom_hook_class' )
	);
	return $installed;
}

// myCRED Custom Hook Class
class my_custom_hook_class extends myCRED_Hook {

	/**
	 * Construct
	 */
	function __construct( $hook_prefs, $type = 'mycred_default' ) {
		parent::__construct( array(
			'id'       => 'complete_profile',
			'defaults' => array(
				'creds'   => 1,
				'log'     => '%plural% for completing your profile'
			)
		), $hook_prefs, $type );
	}

	/**
	 * Hook into WordPress
	 */
	public function run() {
		// Since we are running a single instance, we do not need to check
		// if points are set to zero (disable). myCRED will check if this
		// hook has been enabled before calling this method so no need to check
		// that either.
		add_action( 'personal_options_update',  array( $this, 'profile_update' ) );
		add_action( 'edit_user_profile_update', array( $this, 'profile_update' ) );
	}

	/**
	 * Check if the user qualifies for points
	 */
	public function profile_update( $user_id ) {
		// Check if user is excluded (required)
		if ( $this->core->exclude_user( $user_id ) ) return;

		// Check to see if user has filled in their first and last name
		if ( empty( $_POST['first_name'] ) || empty( $_POST['last_name'] ) ) return;

		// Make sure this is a unique event
		if ( $this->has_entry( 'completing_profile', '', $user_id ) ) return;

		// Execute
		$this->core->add_creds(
			'completing_profile',
			$user_id,
			$this->prefs['creds'],
			$this->prefs['log'],
			'',
			'',
			$this->mycred_type
		);
	}

	/**
	 * Add Settings
	 */
	 public function preferences() {
		// Our settings are available under $this->prefs
		$prefs = $this->prefs; ?>

<!-- First we set the amount -->
<label class="subheader"><?php echo $this->core->plural(); ?></label>
<ol>
	<li>
		<div class="h2"><input type="text" name="<?php echo $this->field_name( 'creds' ); ?>" id="<?php echo $this->field_id( 'creds' ); ?>" value="<?php echo esc_attr( $prefs['creds'] ); ?>" size="8" /></div>
	</li>
</ol>
<!-- Then the log template -->
<label class="subheader"><?php _e( 'Log template', 'mycred' ); ?></label>
<ol>
	<li>
		<div class="h2"><input type="text" name="<?php echo $this->field_name( 'log' ); ?>" id="<?php echo $this->field_id( 'log' ); ?>" value="<?php echo esc_attr( $prefs['log'] ); ?>" class="long" /></div>
	</li>
</ol>
<?php
	}

	/**
	 * Sanitize Preferences
	 */
	public function sanitise_preferences( $data ) {
		$new_data = $data;

		// Apply defaults if any field is left empty
		$new_data['creds'] = ( !empty( $data['creds'] ) ) ? $data['creds'] : $this->defaults['creds'];
		$new_data['log'] = ( !empty( $data['log'] ) ) ? sanitize_text_field( $data['log'] ) : $this->defaults['log'];

		return $new_data;
	}
}










/**
	 * BEING BOSS QUESTIONAIRE
	 */





add_action( 'cmb2_init', 'bbc_register_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function bbc_register_user_profile_metabox() {
	$prefix = 'bbc_user_';
	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => $prefix . 'edit',
		'title'            => __( 'User Profile Metabox', 'cmb2' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
	$cmb_user->add_field( array(
		'name'     => __( 'Extra Info', 'cmb2' ),
		'desc'     => __( 'field description (optional)', 'cmb2' ),
		'id'       => $prefix . 'extra_info',
		'type'     => 'title',
		'on_front' => false,
	) );
	$cmb_user->add_field( array(
		'name'     => __( 'Current Points Total', 'cmb2' ),
		'desc'     => __( '', 'cmb2' ),
		'id'       => $prefix . 'current_points',
		'type'     => 'text_small',
		'on_front' => false,
		'default'  => 0,
	) );
	$cmb_user->add_field( array(
		'name'    => __( 'Question 1', 'cmb2' ),
		'desc'    => __( 'field description (optional)', 'cmb2' ),
		'id'      => $prefix . 'question_1',
		'type'    => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 2', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_2',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 3', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_3',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 4', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_4',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 5', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_5',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 6', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_6',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 7', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_7',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 8', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_8',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 9', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_9',
		'type' => 'checkbox',
	) );
	$cmb_user->add_field( array(
		'name' => __( 'Question 10', 'cmb2' ),
		'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'question_10',
		'type' => 'checkbox',
	) );
}






function bp_custom_user_nav_item() {
    global $bp;
 
    $args = array(
            'name' => __('Questionnaire', 'buddypress'),
            'slug' => 'questionnaire',
            'default_subnav_slug' => 'questionnaire',
            'position' => 20,
            'show_for_displayed_user' => true,
            'screen_function' => 'bp_custom_user_nav_item_screen',
            'item_css_id' => 'questionnaire-item'
    );
 
    bp_core_new_nav_item( $args );
}
/*add_action( 'bp_setup_nav', 'bp_custom_user_nav_item', 99 );*/


function bp_custom_user_nav_item_screen() {
    add_action( 'bp_template_content', 'bp_custom_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


function bp_custom_screen_content() {

	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$club_check = get_user_meta($current_user_id, 'bbc_user_club_tag', true);
	$metabox_id = 'bbc_user_edit';

	/*<!-- if user has course but not community-->*/
	if ( empty($club_check) ) {
		get_template_part( '/template-parts/communitycomingsoon-large' );
	} else {

		cmb2_metabox_form( $metabox_id, $current_user_id );

		echo 'Total points: ';
	 	echo get_user_meta($current_user_id, 'bbc_user_current_points', true);
	 	
	 	if ( function_exists( 'mycred_get_users_rank' ) ) {
			// Get rank object
			$rank = mycred_get_users_rank( $current_user_id, 'bossness' );
			
			// If the user has a rank, $rank will be an object
			if ( is_object( $rank ) ) {
			
				// Show rank title
				echo '<br />Your current Boss Level is: ';
				echo $rank->title;
			
				// Show rank logo (if one exists)
				if ( $rank->has_logo )
					echo $rank->get_image( 'logo' );
			
			}
		}

		echo do_shortcode('[mycred_users_rank_progress]');
	}
}









// define the cmb2_save_field_<field_id> callback 
function action_cmb2_save_bbc_user_questions( $user_id, $cmb2_id, $this_updated, $instance ) { 
	
		$current_point_total = get_user_meta($user_id, 'bbc_user_current_points', true);
		$current_bossness_balance = mycred_get_users_balance( $user_id, 'bossness');
		$question_1 = get_user_meta($user_id, 'bbc_user_question_1', true);
		$question_2 = get_user_meta($user_id, 'bbc_user_question_2', true);
		$question_3 = get_user_meta($user_id, 'bbc_user_question_3', true);
		$question_4 = get_user_meta($user_id, 'bbc_user_question_4', true);
		$question_5 = get_user_meta($user_id, 'bbc_user_question_5', true);
		$question_6 = get_user_meta($user_id, 'bbc_user_question_6', true);
		$question_7 = get_user_meta($user_id, 'bbc_user_question_7', true);
		$question_8 = get_user_meta($user_id, 'bbc_user_question_8', true);
		$question_9 = get_user_meta($user_id, 'bbc_user_question_9', true);
		$question_10 = get_user_meta($user_id, 'bbc_user_question_10', true);
		$new_point_total = 0;

		mycred_subtract('bossness_reset', $user_id, $current_bossness_balance, 'Points reset before questionnaire', 1, 'test', 'bossness');

		//if question 1 field = check, add 2 to new_point_total
		if ( !empty ( $question_1 ) ) {
			$new_point_total += 1;
		}
		//if question 2 field = check, add 2 to new_point_total
		if ( !empty ( $question_2 ) ) {
			$new_point_total += 1;
		}
		//if question 3 field = check, add 2 to new_point_total
		if ( !empty ( $question_3 ) ) {
			$new_point_total += 1;
		}
		//if question 4 field = check, add 2 to new_point_total
		if ( !empty ( $question_4 ) ) {
			$new_point_total += 1;
		}
		//if question 5 field = check, add 2 to new_point_total
		if ( !empty ( $question_5 ) ) {
			$new_point_total += 1;
		}
		//if question 6 field = check, add 2 to new_point_total
		if ( !empty ( $question_6 ) ) {
			$new_point_total += 1;
		}
		//if question 7 field = check, add 2 to new_point_total
		if ( !empty ( $question_7 ) ) {
			$new_point_total += 1;
		}
		//if question 8 field = check, add 2 to new_point_total
		if ( !empty ( $question_8 ) ) {
			$new_point_total += 1;
		}
		//if question 9 field = check, add 2 to new_point_total
		if ( !empty ( $question_9 ) ) {
			$new_point_total += 1;
		}
		//if question 10 field = check, add 2 to new_point_total
		if ( !empty ( $question_10 ) ) {
			$new_point_total += 1;
		}

		//$points_to_add = $new_point_total - $current_point_total;
	    mycred_add( 'questionnaire_hook_test', $user_id, $new_point_total, 'hook test', 1, 'test', 'bossness' );
	    update_user_meta( $user_id, 'bbc_user_current_points', $new_point_total );

}

add_action( "cmb2_save_user_fields", 'action_cmb2_save_bbc_user_questions', 10, 4 ); 















// Get user's rank progress
function get_mycred_users_rank_progress( $user_id, $show_rank ) {
	global $wpdb;

	if ( ! function_exists( 'mycred' ) ) return '';
	
	// Change rank data to displayed user when on a user's profile
	/*if ( function_exists( 'bp_is_user' ) && bp_is_user() && empty( $user_id ) ) {
		$user_id = bp_displayed_user_id();
	}*/

	// Load myCRED
	//$mycred = mycred();
	
	// Check if user is excluded
	if ( mycred_exclude_user( $user_id ) ) return '';

	// Get Balance
	$users_balance = mycred_get_users_balance( $user_id, 'bossness' );
   
	// Rank Progress
   
	// Get the users current rank post ID
    $users_rank = mycred_get_users_rank_id( $user_id, 'bossness' );
	
	// Get the name of the users current rank
	$users_rank_name = $users_rank->title;
   
	// Get the ranks set max
	$max = get_post_meta( $users_rank, 'mycred_rank_max', true );
	
	$tabl_name = $wpdb->prefix . 'postmeta';
	
	// Get the users next rank post ID
	$next_ranks = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM {$tabl_name} WHERE meta_key = %s AND meta_value > %d ORDER BY meta_value * 1 LIMIT 1;", 'mycred_rank_min', $max ) );

    foreach( $next_ranks as $next_rank ) {

        $next_rank = $next_rank->post_id;
    }
	
	// Get the name of the users next rank
	$next_rank_name = $next_rank->title;
	
	// Get the ranks set min
	$next_rank_min = get_post_meta( $next_rank, 'mycred_rank_min', true );
   
	// Calculate progress. We need a percentage with 1 decimal
	$progress = number_format( ( ( $users_balance / $max ) * 100 ), 0 );

	// Display rank progress bar
	echo '<div class="mycred-rank-progress">';	
		/*echo '<h3 class="rank-progress-label" style="font-weight:bold;">Rank Progress ('. $progress .'%)</h3>';*/
		echo '<div class="rank-progress-bar"><span class="progress-width" style="width:'. $progress .'%;">';
		echo '</div>';	
		if( $show_rank == 'yes' ){
			echo '<span class="current-rank" style="float:left;padding-top:1%;font-weight:bold;">'. $users_rank_name .'</span>';	
			echo '<span class="next-rank" style="float:right;padding-top:1%;font-weight:bold;">'. $next_rank_name .'</span>';
			echo '<span class="points-progress" style="width:100%;float:left;margin-top: -4.5%;padding-top:1%;font-weight:bold;text-align:center;">'. $users_balance .' of '. $next_rank_min .'</span>';
		}
	echo '</div>';	
}

/**
 * myCRED Shortcode: mycred_users_rank_progress
 * @since 1.0
 * @version 1.0
 */
function mycred_users_rank_progress( $atts ){
	extract( shortcode_atts( array(
		'user_id' => get_current_user_id(),
		'show_rank' => 'no'
	), $atts ) );

	ob_start();
	
	get_mycred_users_rank_progress( $user_id, $show_rank );

	$output = ob_get_contents();
	ob_end_clean();

	return $output;

}
add_shortcode( 'mycred_users_rank_progress', 'mycred_users_rank_progress' );