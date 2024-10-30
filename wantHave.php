<?php
/*
Plugin Name: IWantOneButton
Plugin URI: http://www.danielsands.co.cc
Description: Adds a I Want one / I Have one button to your blog posts, perfect for Gadget/Technology blogs
Version: 3.0.1
Author: Daniel Sands
Author URI: http://www.danielsands.co.cc
*/

/*  Copyright 2009  Daniel Sands  (email : daniel@durell.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$Version = "3.0.1";

require("wantHaveWidget.php");
require("wantHavePostAdmin.php");
require("wantHaveAdminSettings.php");
/*
---------------------------------------------------------------------------------

THINGS TO DO:

add options for: (loaded in initial setup)
after voted message
position of buttons: topleft, topright, bottomleft, bottomright, etc...

themes:
load style.css for the styling
load template.php for the layout of the buttons template.
load thumb.jpg for preview image of buttons.

create themes for different layouts.

load themes list from the /wantHave/themes directory on the fly, making it easier 
to add new themes. Each folder in themes should contain the above as mentioned.


FIX-------
done::: when choosing to have the buttons, then choosing not to, something goes wrong
try storing "off" instead of nothing in the option.

version history:
1.01: AJAX Voting now used, checkbox for showing boxes rather than shortcodes.
0.01: Initial release

DOING:
Admin page!
---------------------------------------------------------------------------------
*/


	function initial_setup_want() { 
		global $wpdb;
		$table_name = $wpdb->prefix . "IWantOne";
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

		
			$sql = "CREATE TABLE " . $table_name . " (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  postid bigint(11) DEFAULT '0' NOT NULL,
					  counthave bigint(11) DEFAULT '0' NOT NULL,
                                          countwant bigint(11) DEFAULT '0' NOT NULL,
                                          useriplist VARCHAR(1000),
					  UNIQUE KEY id (id)
					);";
				
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);

			add_option("wantHave_afterVoteText","Thank you for voting!");
			add_option("wantHave_Position","bottomleft");
			add_option("wantHave_Theme","themes/iWant_Black/");
			return true;
		} else { 
		return false;
	    }
}

/*
declare:function->getPostbuttons
this function is called as a filter to the get_content function whilst in the loop, it will print the buttons to the page.
*/

//temp testing loadTheme functions!

$GLOBALS["curTheme"] = get_option("wantHave_Theme"); //"themes/iWant_Black/";

function getPostbuttons($content) { 

	//firstly we need to retrive the posts id, we're in the loop so can just use get_the_id() for this.
	$postID = get_the_id();
	
	//see if the user wants buttons on this post, if not just return the content.
	$testingStuff = get_option('wantHaveButton_'.$postID);
	if ($testingStuff!="on") { return $content; }

	//load option for the post-vote message, button link, etc!
	$alreadyVotedMessage = get_option("wantHave_afterVoteText");
	$wantButtonLINK = "<a onclick=\"wantHave_cast_vote('want','" . $postID . "','" . $afterVotedMessage . "')\">want<br />this</a>";
	$haveButtonLINK = "<a onclick=\"wantHave_cast_vote('have','" . $postID . "','" . $afterVotedMessage . "')\">have<br />this</a>";
	$afterVotedMessage = get_option("wantHave_afterVoteText");


	//connect to the database to get the current results/see if the post has been viewed before.
	global $wpdb;
	$table_name = $wpdb->prefix . "IWantOne";
	$sql = "SELECT * FROM " . $table_name . " WHERE postid=" . $postID;
    $results = $wpdb->get_results( $sql );

		if ($results) {
		 
			foreach ($results as $result) { 
				$wantNo = $result->countwant;
				$haveNo = $result->counthave;
				$curIPList = $result->useriplist;
			} //end foreach loop.
			
			//get the users IP address, and iplist from db
			$thisIP = $_SERVER['REMOTE_ADDR'];
			$strMatch =(int)strpos($curIPList,$thisIP);
			

			//if we are here, then the buttons have been viewed for this post before,
			//but we don't know if the user has already voted, let's check that now.

			if ($strMatch>0) { 
				//user has voted before and an entry exists prevent the button from being printed to the page.
				$wantButtonLINK = "have<br />this";
				$haveButtonLINK = "want<br />this";
			} else {
				//user hasn't yet voted, so the text in the alreadyVoted div should be blank.
				$alreadyVotedMessage = "";
			}

        } else { 
			//post does not exist yet, so need to create an entry for it.
			$insert = "INSERT INTO " . $table_name ." (postid, counthave, countwant) " . "VALUES (" . $postID . ", 0, 0)";
			$results = $wpdb->query( $insert ); 

			$wantNo = 0;
			$haveNo = 0;
			$alreadyVotedMessage = "";
        }

	//we've got everything we need, replace the theme file attributes with the content loaded.
	
	require($GLOBALS["curTheme"] . "template.php");
	$voteTemplate = $GLOBALS["voteTemplate"]; //"<span class=\"wantButton\"><span class=\"wantCount\" id=\"wantCount_" . $postID . "\"><!--%WANTCOUNT%--></span><span class=\"wantLink\" id=\"wantLink_" . $postID . "\"><!--%WANTBUTTON%--></span></span><span class=\"haveButton\"><span class=\"haveCount\" id=\"haveCount_" . $postID . "\"><!--%HAVECOUNT%--></span><span class=\"haveLink\" id=\"haveLink_" . $postID . "\"><!--%HAVEBUTTON%--></span></span><span id=\"alreadyVoted_" . $postID . "\"><!--%ALREADYVOTED%--></span>";  

	$voteTemplate = str_replace("%POSTID%", $postID, $voteTemplate);
	$voteTemplate = str_replace("<!--%WANTCOUNT%-->", $wantNo, $voteTemplate);
	$voteTemplate = str_replace("<!--%HAVECOUNT%-->", $haveNo, $voteTemplate);
	$voteTemplate = str_replace("<!--%WANTBUTTON%-->", $wantButtonLINK, $voteTemplate);
	$voteTemplate = str_replace("<!--%HAVEBUTTON%-->", $haveButtonLINK, $voteTemplate);
	$voteTemplate = str_replace("<!--%ALREADYVOTED%-->", $alreadyVotedMessage, $voteTemplate);
	
	//add the template to the content.
	/*
		WORK TO BE DONE HERE:
		SEE WHERE THE USER WANTS THE BUTTON AND PLACE AS APPROPRIATE..
	*/
	$buttonsPosition = get_option("wantHave_Position");
	switch ($buttonsPosition) { 
		case "topleft":
			$content = "<span class=\"haveWantButtonHolder\" style=\"width:auto; float:left;\">" . $voteTemplate . "</span><span style=\"clear:both!important;width:100%;\">&nbsp;</span>" . $content;
		break;
		case "topright":
			$content = "<span class=\"haveWantButtonHolder\" style=\"width:auto; float:right;\">" . $voteTemplate . "</span><span style=\"clear:both!important;width:100%;\">&nbsp;</span>" . $content;
		break;
		case "bottomleft":
			$content .= "<span class=\"haveWantButtonHolder\" style=\"width:auto; float:left;\">" . $voteTemplate . "</span><span style=\"clear:both!important;width:100%;\">&nbsp;</span>";
		break;
		case "bottomright":
			$content .= "<span class=\"haveWantButtonHolder\" style=\"width:auto; float:right;\">" . $voteTemplate . "</span><span style=\"clear:both!important;width:100%;\">&nbsp;</span>";
		break;
	}
	
	
	$content .= "<br /><br />";
	
	//return the content to the page.
	return $content;
	
} //end function getPostbuttons();


function wantHave_css_header() { 
	//simply echo the required style to the page, this will be loaded from the theme file, soon...
	echo "<link rel=\"stylesheet\" href=\"/wp-content/plugins/wantHave/" . $GLOBALS["curTheme"] . "style.css\">";
}

function wantHave_js_header() {
	//we need to print the sack library for AJAX voting.
	  wp_print_scripts( array( 'sack' ));
		?>
		<script type="text/javascript">
		//<![CDATA[
			function wantHave_cast_vote(wantORhave, post_id, postMsg )
			{
				var mysack = new sack( 
				   "<?php bloginfo( 'wpurl' ); ?>/wp-content/plugins/wantHave/updateAJAX.php" );     
			
			  mysack.execute = 1;
			  mysack.method = 'POST';
			  mysack.setVar( "add", wantORhave);
			  mysack.setVar( "post_id", post_id);
			  mysack.setVar( "postVoteMessage", postMsg);
			
			  mysack.onError = function() { alert('Ajax error in voting' )};
			  mysack.runAJAX();
			
			  return true;
			 
			} 
		//]]>
		</script>
		<?php
} //end function: wantHave_js_header()

//add action, filter and activation hooks.
register_activation_hook(__FILE__,'initial_setup_want');
add_filter('the_content', 'getPostButtons');
add_action('wp_head', 'wantHave_js_header' );
add_action('wp_head', 'wantHave_css_header' );
add_action('admin_menu', 'wantHave_add_custom_box');
add_action('admin_menu', 'wantHave_AdminSettings');
add_action('save_post', 'wantHave_save_postdata');

?>