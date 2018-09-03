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



?>