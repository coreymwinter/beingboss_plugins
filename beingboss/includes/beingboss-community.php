<?php

/**
 * Get the User Id in the current context
 * @param int $user_id
 * @return int user_id
 */
function hibuddy_get_context_user_id($user_id=false){
 
if ( bp_is_my_profile() || !is_user_logged_in() )
 return false;
 if( !$user_id )
 $user_id = bp_get_member_user_id();//for members loop
 if( !$user_id && bp_is_user() ) //for user profile
 $user_id = bp_displayed_user_id();
 
 return apply_filters( 'hibuddy_get_context_user_id', $user_id );
}

function hibuddy_get_send_private_message_url() {
 
$user_id = hibuddy_get_context_user_id();
 
if( !$user_id || $user_id == bp_loggedin_user_id() )
 return;
 
 if ( bp_is_my_profile() || !is_user_logged_in() )
 return false;
 
return apply_filters( 'hibuddy_get_send_private_message_url', wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) ) );
}

function hibuddy_get_send_private_message_button() {
 //get the user id to whom we are sending the message
 $user_id = hibuddy_get_context_user_id();
 
 //don't show the button if the user id is not present or the user id is same as logged in user id
 if( !$user_id || $user_id == bp_loggedin_user_id() )
 return;
$defaults = array(
 'id' => 'private_message-'.$user_id,
 'component' => 'messages',
 'must_be_logged_in' => true,
 'block_self' => true,
 'wrapper_id' => 'send-private-message-'.$user_id,
 'wrapper_class' =>'send-private-message',
 'link_href' => hibuddy_get_send_private_message_url(),
 'link_title' => __( 'Send a private message to this user.', 'buddypress' ),
 'link_text' => __( 'Private Message', 'buddypress' ),
 'link_class' => 'send-message',
 );
 
 $btn = bp_get_button( $defaults );
 
 return apply_filters( 'hibuddy_get_send_private_message_button', $btn );
}

function hibuddy_send_private_message_button() {
 echo hibuddy_get_send_private_message_button();
}


define( 'BP_MESSAGES_AUTOCOMPLETE_ALL', true );



add_filter('bp_nouveau_get_members_buttons', function( $buttons, $user_id, $type ){
  if(isset($buttons['private_message'])){
      $buttons['private_message']['button_attr']['href'] = trailingslashit( bp_loggedin_user_domain() . bp_get_messages_slug() ) . '/compose?r=' . bp_core_get_username( $user_id );
  }
  return $buttons;
}, 10, 3);




add_action( 'cmb2_init', 'bbc_user_member_tags' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function bbc_user_member_tags() {
	$prefix = 'bbc_user_';
	/**
	 * Metabox for the user profile screen
	 */
	$bbc_user = new_cmb2_box( array(
		'id'               => $prefix . 'tags_metabox',
		'title'            => __( 'Member Tags Metabox', 'cmb2' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );
	$bbc_user->add_field( array(
		'name'     => __( 'Membership Tags', 'cmb2' ),
		'desc'     => __( '', 'cmb2' ),
		'id'       => $prefix . 'membership_tags_title',
		'type'     => 'title',
		'on_front' => false,
	) );
	$bbc_user->add_field( array(
		'name'    => __( 'Student', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'student_tag',
		'type'    => 'checkbox',
	) );
	$bbc_user->add_field( array(
		'name'    => __( 'Club', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'club_tag',
		'type'    => 'checkbox',
	) );
}




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
add_action( 'bp_setup_nav', 'bp_custom_user_nav_item', 99 );

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






function shortcode_my_orders( $atts ) {
    extract( shortcode_atts( array(
        'order_count' => -1
    ), $atts ) );

    ob_start();
    wc_get_template( 'myaccount/my-orders.php', array(
        'current_user'  => get_user_by( 'id', get_current_user_id() ),
        'order_count'   => $order_count
    ) );
    return ob_get_clean();
}
add_shortcode('my_orders', 'shortcode_my_orders');






?>