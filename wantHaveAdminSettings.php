<?php
//get_option("wantHave_Theme");
//get_option("wantHave_Theme");
//get_option("wantHave_Theme");

//get_option("wantHave_Theme");

//get_option("wantHave_Theme");
function wantHave_AdminSettings() {
  add_options_page('My Plugin Options', 'iWant iHave', 8, __FILE__, 'wantHave_Admin_Options');
}
function wantHave_Admin_Options() {
    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'

		if( $_POST["updateWantHaveSettings"] == 'Y' ) { 
		//change the settings!
                update_option("wantHave_afterVoteText", $_POST["wantHave_afterVoteText"]);
				update_option("wantHave_Position", $_POST["wantHave_Position"]);
				update_option("wantHave_Theme", $_POST["wantHave_Theme"]);

		//show an updated message
		?><div class="updated"><p><strong><?php _e('Settings saved.', 'mt_trans_domain' ); ?></strong></p></div><?php 
		} 
		
		//now show the options screen
		//wantHave_afterVoteText
		?>
		<div class="wrap">
		<?php //workinggggg here!! ?>
			<h2>iWant iHave Buttons by <a href="http://www.danielsands.co.cc">Daniel Sands</a></h2>
			<h3>Settings</h3>
			<p></p>
				<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
					<input type="hidden" name="updateWantHaveSettings" value="Y">
							<p>
							Button Position:<br />
							<?php
							$activeImage = get_option("wantHave_Position");
							$t_left = "";
							$t_right = "";
							$b_left = "";
							$b_right = "";
							switch ($activeImage) { 
								case "topleft":
									$t_left = "_hover";
								break;
								case "topright":
									$t_right = "_hover";
								break;
								case "bottomleft":
									$b_left = "_hover";	
								break;
								case "bottomright":
									$b_right = "_hover";
								break;
							}
							?>
							<img src="/wp-content/plugins/wantHave/images/bottomleft<?php echo $b_left; ?>.png" alt="bottomleft" id="bottomleft" style="float:left;cursor:pointer;" onclick="regHover(this);"/>
							<img src="/wp-content/plugins/wantHave/images/bottomright<?php echo $b_right; ?>.png" alt="bottomright" id="bottomright" style="float:left;cursor:pointer;" onclick="regHover(this);"/>
							<img src="/wp-content/plugins/wantHave/images/topleft<?php echo $t_left; ?>.png" alt="topleft" id="topleft" style="float:left;cursor:pointer;" onclick="regHover(this);"/>
							<img src="/wp-content/plugins/wantHave/images/topright<?php echo $t_right; ?>.png" alt="topright" id="topright" style="float:left;cursor:pointer;" onclick="regHover(this);"/>
							<script type="text/javascript">
							<!--
							function regHover(objHover) { 
								document.getElementById('bottomleft').src = '/wp-content/plugins/wantHave/images/bottomleft.png';
								document.getElementById('bottomright').src = '/wp-content/plugins/wantHave/images/bottomright.png';
								document.getElementById('topleft').src = '/wp-content/plugins/wantHave/images/topleft.png';
								document.getElementById('topright').src = '/wp-content/plugins/wantHave/images/topright.png';
								objHover.src = '/wp-content/plugins/wantHave/images/'+objHover.alt+'_hover.png';
								document.getElementById('wantHave_Position').value = objHover.alt;
							}
							function selectTheme(themeOrdName) { 
							//theme OrdName will just be iWant_Black or iWant_Red
							//set divid wantHave_Theme
							document.getElementById('wantHave_Theme').value = 'themes/'+themeOrdName+'/';
							document.getElementById('themeUpdated').style.display = 'block';
							}
							-->
							</script>
							<style type="text/css">
							.themeThumb { float:left;margin-left:10px;border:1px solid #DDDDDD;padding:5px 5px 5px 5px; background-color:none; width:254px;height:190px;display:block; }
							.themeThumb:hover { background-color:#FFFEEB; }
							.themeThumb:normal { background-color:none; }
							</style>
							<input type="hidden" name="wantHave_Position" id="wantHave_Position" value="<?php echo get_option("wantHave_Position"); ?>" size="40">
							<br /><br /><br /><br /><br />
							</p>
							<p>
								Message to show after vote has been submitted:<br />
								<input type="text" name="wantHave_afterVoteText" value="<?php echo get_option("wantHave_afterVoteText"); ?>" size="40">
							</p>
							<p><h3>Themes</h3></p>
							<p>
							<strong>Current Theme:</strong><br />
							<?php 
							require(ABSPATH . "wp-content/plugins/wantHave/" . get_option("wantHave_Theme") . "/template.php");
							echo "<span style=\"float:left;margin-left:10px;width:98%;\"><img src=\"/wp-content/plugins/wantHave/" . get_option("wantHave_Theme") . "/buttonBG.png\"/><br /><strong>" . $GLOBALS["thisThemeName"] . "</strong> by <a href=\"" . $GLOBALS["thisThemeAuthorLink"] . "\">" . $GLOBALS["thisThemeAuthor"] . "</a><br /></span>";
							?>
							</p>
							<p>
							<span style="width:98%;height:30px;display:block;">&nbsp;</span>
							<strong>Select a New Theme:</strong><br />
							<?php
							$curThemeMatch = get_option("wantHave_Theme");
							$curThemeMatch = str_replace("themes/", "", $curThemeMatch);
							$curThemeMatch = str_replace("/", "", $curThemeMatch);
							
							$dir = ABSPATH . "wp-content/plugins/wantHave/themes/";

							// Open a known directory, and proceed to read its contents
							if (is_dir($dir)) {
								if ($dh = opendir($dir)) {
									while (($file = readdir($dh)) !== false) {
										if ($file!=".."&&$file!="."&&filetype($dir . $file)=="dir"&&$file!=$curThemeMatch) {
										  require($dir . $file . "/template.php");
										  echo "<span class=\"themeThumb\"><center><img src=\"/wp-content/plugins/wantHave/themes/" . $file . "/buttonBG.png\" style=\"background-color:none!important;\"/></center><br /><strong>" . $GLOBALS["thisThemeName"] . "</strong> by <a href=\"" . $GLOBALS["thisThemeAuthorLink"] . "\">" . $GLOBALS["thisThemeAuthor"] . "</a><br /><a style=\"cursor:pointer;\" onclick=\"selectTheme('" . $file . "');\">Select</a></span>";
										}
									}
									closedir($dh);
								}
							}

							?>
							<span style="clear:both;width:90%;">&nbsp;</span>
							<input type="hidden" name="wantHave_Theme" id="wantHave_Theme" value="<?php echo get_option("wantHave_Theme"); ?>" size="40">
							
							<div class="updated" id="themeUpdated" style="display:none;"><p><strong><?php _e('Theme selected, please remember to choose "Update" to store the selection.', 'mt_trans_domain' ); ?></strong></p></div>
							</p>
							<p class="submit">
								<input type="submit" name="Submit" value="<?php _e('Update', 'mt_trans_domain' ); ?>" />
							</p>
						
				</form>
		</div>
<?php
} //end tag for function

?>