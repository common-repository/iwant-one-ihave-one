<?php
require("../../../wp-blog-header.php");

	function incrementTickerAJAX($wantORAdd, $postID) {
		
		//define the wpdb and table
		global $wpdb;
		$table_name = $wpdb->prefix . "IWantOne";

		//get current results.
		$sql = "SELECT * FROM " . $table_name . " WHERE postid=" . $postID;
		$results = $wpdb->get_results( $sql );

		foreach ($results as $result) { 
				  $curCountWant = $result->countwant;
				  $curCountHave = $result->counthave;
				  $curIPList = $result->useriplist;
		}
                $curCountNumWant = (int)$curCountWant;
                $curCountNumHave = (int)$curCountHave;

		//get the users IP address and make sure that they haven't already voted.
		$thisIP = $_SERVER['REMOTE_ADDR'];
		$strMatch =(int)strpos($curIPList,$thisIP);
		//if we have a match then the ip has already been logged, so exit the function and return an error.
		if ($strMatch>0) { return "alert('You have already voted');"; }

		//if we are here then we can go ahead and enter the vote, first see if it's a WANT or HAVE vote.
		switch ($wantORAdd) { 
			case "want":
				$curCountNumWant = $curCountNumWant + 1;
				$update = "UPDATE " . $table_name ." SET countwant=" . $curCountNumWant . " WHERE postid=" . $postID;
			break;
				
			case "have":
				$curCountNumHave = $curCountNumHave + 1;
				$update = "UPDATE " . $table_name ." SET counthave=" . $curCountNumHave . " WHERE postid=" . $postID;
			break;
                        default:
                                return "no option specified!";

		} //end switch

		//run the query as generated above.
		$results = $wpdb->query( $update ); 

		//next we want to update the record to include the users IP so they can't vote again.
		$update2 = "UPDATE " . $table_name ." SET useriplist='" . $curIPList . "_" . $thisIP . "' WHERE postid=" . $postID;
		$results = $wpdb->query( $update2 );

        

        //DIV ID Will be alreadyVoted_$POSTID
        $DivPostMessage = "alreadyVoted_" . $postID;
		return "document.getElementById('" . $DivPostMessage . "').innerHTML='" . $postVoteMessage . "'; document.getElementById('wantCount_" . $postID . "').innerHTML='" . $curCountNumWant . "'; document.getElementById('wantLink_" . $postID . "').innerHTML='want<br />this'; document.getElementById('haveCount_" . $postID . "').innerHTML='" . $curCountNumHave . "'; document.getElementById('haveLink_" . $postID . "').innerHTML='have<br />this';";

	} //end function IncrementTickerAJAX

	//get the posted variables/query string variables.
	$typeOfVote = $_POST["add"];
	$postID = $_POST["post_id"];
	$postVoteMessage = $_POST["postVoteMessage"];

	if ($typeOfVote==''&&$postID=='') { 
		$typeOfVote = $_GET["add"];
		$postID = $_GET["post_id"];
                $postVoteMessage = $_GET["postVoteMessage"];
	}
	
	
	//run the function and return the result.
	die (incrementTickerAJAX($typeOfVote, $postID));

?>