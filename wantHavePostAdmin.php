<?php
function wantHave_add_custom_box() {
	  if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'wantHave_sectionid', __( 'Show Want/Have Buttons for this post?', 'wantHave_textdomain' ), 
					'wantHave_inner_custom_box', 'post', 'advanced', 'high' );
		add_meta_box( 'wantHave_sectionid', __( 'Show Want/Have Buttons for this post?', 'wantHave_textdomain' ), 
					'wantHave_inner_custom_box', 'page', 'advanced', 'high' );
	   } else {
		add_action('dbx_post_advanced', 'wantHave_old_custom_box' );
		add_action('dbx_page_advanced', 'wantHave_old_custom_box' );
	  }
} //end function: wantHave_add_custom_box()
   
function wantHave_inner_custom_box($post) {

	// Use nonce for verification.
	echo '<input type="hidden" name="wantHave_noncename" id="wantHave_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

	// The actual fields for data entry.
	echo '<label for="wantHave_showThisPost">' . __("Tick this box if you want to show the 'I have this' and 'I want this' voting buttons to this post.", 'wantHave_textdomain' ) . '</label> ';
    
	//check if the option is selected.
	if (get_option("wantHaveButton_".$post->ID)=="on") { $showThisPost=" checked=\"checked\""; }
	echo '<input type="checkbox" name="wantHave_showThisPost" '.$showThisPost.'/>';
}

/* Prints the edit form for pre-WordPress 2.5 post/page */
function wantHave_old_custom_box() {

	echo '<div class="dbx-b-ox-wrapper">' . "\n";
	echo '<fieldset id="wantHave_fieldsetid" class="dbx-box">' . "\n";
	echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">' . __( 'My Post Section Title', 'wantHave_textdomain' ) . "</h3></div>";   
	echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';

	// output editing form
	wantHave_inner_custom_box();
	
	// end wrapper
	echo "</div></div></fieldset></div>\n";
} //end function: wantHave_old_custom_box()


function wantHave_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['wantHave_noncename'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ))
		return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ))
		return $post_id;
	}
	
	//if we're here then everything has been verified, so go ahead and save the option.
	$mydata = $_POST['wantHave_showThisPost'];
	if ($mydata=="") { $mydata = "off"; }



	if (get_option("wantHaveButton_" . $post_id)) { 
		//option exists, update it..
		update_option("wantHaveButton_" . $post_id, $mydata);
	} else {
		//create the option
		add_option("wantHaveButton_" . $post_id, $mydata, '', 'yes'); 
	}
	
	//return the data :)
	return $mydata;
	
} //end function: wantHave_save_postdata()


?>