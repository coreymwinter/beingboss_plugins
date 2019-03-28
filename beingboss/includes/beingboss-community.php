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
		'name'    => __( 'Member', 'cmb2' ),
		'desc'    => __( '', 'cmb2' ),
		'id'      => $prefix . 'member_tag',
		'type'    => 'checkbox',
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