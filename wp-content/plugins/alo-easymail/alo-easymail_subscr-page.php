<?php
global $wpdb;

// example:
// http://{blog_url}/?page_id=4&ac=unsubscribe&em1=email_account&em2=domain.ltd&uk={uniquekey}

$concat_email = ( isset($_REQUEST['em1']) && isset($_REQUEST['em2']) ) ? $_REQUEST['em1'] . "@" . $_REQUEST['em2'] : false; 

$email  = ( $concat_email ) ? stripslashes($wpdb->escape($concat_email)) : false; 
$unikey = ( isset($_REQUEST['uk']) ) ? stripslashes($wpdb->escape($_REQUEST['uk'])) : false;
$action = ( isset($_REQUEST['ac']) ) ? stripslashes($wpdb->escape($_REQUEST['ac'])) : false;


// If there is not an activation/unsubscribe request
if (alo_em_can_access_subscrpage ($email, $unikey) == false ) : // if cannot
	// if there is action show error msg
	if(isset($_REQUEST['ac'])) echo "<p>".__("Error during operation.", "alo-easymail") ."</p>";
	
	$optin_txt = ( alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_optin_msg', false) !="") ? alo_em_translate_option ( alo_em_get_language (), 'alo_em_custom_optin_msg', false) : __("Yes, I would like to receive the Newsletter", "alo-easymail"); 
    echo "<div id='alo_easymail_page'>";
	echo alo_em_show_widget_form();
	echo "</div>";
	
else: // if can go on
 

// Activate
if ($action == 'activate') {
    if (alo_em_edit_subscriber_state_by_email($email, "1", $unikey) === FALSE) {
        echo "<p>".__("Error during activation. Please check the activation link.", "alo-easymail")."</p>";
    } else {
        echo "<p>".__("Your subscription was successfully activated. You will receive the next newsletter. Thank you.", "alo-easymail")."</p>";
        do_action ( 'alo_easymail_subscriber_updated', $email, $email );
    }
}
    
// Request unsubscribe/modify subsription (step #1)
if ($action == 'unsubscribe') {
	$mailinglists = alo_em_get_mailinglists( 'public' );
	if ($mailinglists) { // only if there are public lists
		echo '<form method="post" action="'. get_permalink() .'" class="alo_easymail_manage_subscriptions">';
		echo "<p>".__("To modify your subscription to mailing lists use this form", "alo-easymail") . "</p>";
		echo '<div class="alo_easymail_lists_table">';
		echo alo_em_html_mailinglists_table_to_edit ( $email, "" );
		echo '</div>';
	   	echo '<input type="hidden" name="ac" value="do_editlists" />';
		echo '<input type="hidden" name="em1" value="'. $_REQUEST['em1']. '" />';
		echo '<input type="hidden" name="em2" value="'. $_REQUEST['em2'] .'" />';
		echo '<input type="hidden" name="uk" value="'. $unikey .'" />';
		echo '<input type="submit" name="submit" value="'. __('Edit'). '" />';
		echo '</form>'; 
    }
    
    echo '<form method="post" action="'. get_permalink() .'" class="alo_easymail_unsubscribe_form">';
    echo "<p>".__("To unsubscribe the newsletter for good click this button", "alo-easymail") . "</p>";
 	echo '<input type="hidden" name="ac" value="do_unsubscribe" />';
    echo '<input type="hidden" name="em1" value="'. $_REQUEST['em1']. '" />';
    echo '<input type="hidden" name="em2" value="'. $_REQUEST['em2'] .'" />';
    echo '<input type="hidden" name="uk" value="'. $unikey .'" />';
    echo '<input type="submit" name="submit" value="'. __('Unsubscribe me', 'alo-easymail'). '" />';
    echo '</form>'; 
}

// Confirm unsubscribe and do it! (step #2a)
if ($action == 'do_unsubscribe' && isset($_POST['submit']) ) {
    if (alo_em_delete_subscriber_by_email($email, $unikey)) {
        echo "<p>".__("Your subscription was successfully deleted. Bye bye.", "alo-easymail")."</p>";
        do_action ( 'alo_easymail_subscriber_deleted', $email, false );
    } else {
        echo "<p>".__("Error during unsubscription.", "alo-easymail")." ". __("Try again.", "alo-easymail"). "</p>";
        echo "<p>".__("If it fails again you can contact the administrator", "alo-easymail").": <a href='mailto:".get_option('admin_email')."?Subject=Unsubscribe'>".get_option('admin_email')."</a></p>";
    }
}

// Modify lists subscription and save it! (step #2b)
if ($action == 'do_editlists' && isset($_POST['submit']) ) {
	$mailinglists = alo_em_get_mailinglists( 'public' );
	if ($mailinglists) {
		$subscriber_id = alo_em_is_subscriber( $email );
		foreach ( $mailinglists as $mailinglist => $val) {					
			if ( isset ($_POST['alo_em_profile_lists']) && is_array ($_POST['alo_em_profile_lists']) && in_array ( $mailinglist, $_POST['alo_em_profile_lists'] ) ) {
				alo_em_add_subscriber_to_list ( $subscriber_id, $mailinglist );	  // add to list
			} else {
				alo_em_delete_subscriber_from_list ( $subscriber_id, $mailinglist ); // remove from list
			}
		}
	}
	echo "<p>" . __("Your subscription to mailing lists successfully updated", "alo-easymail") . ".</p>";				
}


endif; //  end CHECK IF CAN ACCESS
?>
