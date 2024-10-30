<?php
function initHaveWidget() {

    function widget_wantHaveCount($args) {
      extract($args);
    ?>
        <?php if(get_option("showOwned")=="on") { ?>
        <?php echo $before_widget; ?>
            <?php echo $before_title
                . get_option("OwnedTitle")
                . $after_title . "<ul>"; ?>
           <?php
           global $wpdb;
           $table_name = $wpdb->prefix . "IWantOne";

           $sql = "SELECT * FROM " . $table_name . " WHERE counthave>0 ORDER BY counthave DESC LIMIT " . get_option("OwnedMax");
           $results = $wpdb->get_results( $sql );
           $showHaveCount = get_option("showOwnedCount");

           foreach ($results as $result) { 
              $curCountHave = $result->counthave;
              $curPostID = $result->postid;
              $thisPost = get_post($curPostID);
              $postTitle = $thisPost->post_title;
              $postPerma = $thisPost->guid;
              echo ("<li><a href=\"" . $postPerma . "\">".$postTitle."</a>");
              if ($showHaveCount=="on") { 
                echo (" (" . $curCountHave . ")");
              }
              echo ("</li>");
              
           }
        ?>
        <?php echo "</ul>" . $after_widget; ?>
        <?php } //end show owned ?>

        <?php if(get_option("showWanted")=="on") { ?>
        <?php echo $before_widget; ?>
            <?php echo $before_title
                . get_option("WantedTitle")
                . $after_title . "<ul>"; ?>
           <?php
           global $wpdb;
           $table_name = $wpdb->prefix . "IWantOne";

           $sql = "SELECT * FROM " . $table_name . " WHERE countwant>0 ORDER BY countwant DESC LIMIT " . get_option("WantedMax");
           $results = $wpdb->get_results( $sql );
           $showWantCount = get_option("showWantedCount");

           foreach ($results as $result) { 
              $curCountWant = $result->countwant;
              $curPostID = $result->postid;
              $thisPost = get_post($curPostID);
              $postTitle = $thisPost->post_title;
              $postPerma = $thisPost->guid;
              echo ("<li><a href=\"" . $postPerma . "\">".$postTitle."</a>");
              if ($showWantCount=="on") { 
                echo (" (" . $curCountWant . ")");
              }
              echo ("</li>");
              
           }
        ?>
        <?php echo "</ul>" . $after_widget; ?>
        <?php } //end show wanted ?>
    
<?php
    }
    
    function widget_wantHaveOptions() { 

    if (get_option("showWanted")) {
    } else {
         //no options exist, create them here. 
         add_option("showWanted", "on");
         add_option("WantedTitle", "Most Wanted Gadgets");
         add_option("WantedMax", "5");
         add_option("showWantedCount", "on");

         add_option("showOwned", "on");
         add_option("OwnedTitle", "Most Owned Gadgets");
         add_option("OwnedMax", "5");
         add_option("showOwnedCount", "on");
    } 

    if ($_POST["updateWantHaveOptions"]=="true") { 
       //user has submitted there options, save them here.
       update_option("showWanted",$_POST["showWanted"]);
       update_option("WantedTitle",$_POST["WantedTitle"]);
       update_option("WantedMax",$_POST["WantedMax"]);
       update_option("showWantedCount",$_POST["showWantedCount"]);

       update_option("showOwned",$_POST["showOwned"]);
       update_option("OwnedTitle",$_POST["OwnedTitle"]);
       update_option("OwnedMax",$_POST["OwnedMax"]);
       update_option("showOwnedCount",$_POST["showOwnedCount"]);
    }

    if (get_option("showWanted")=="on") { $wantedAddStr='checked="checked"'; }
    if (get_option("showWantedCount")=="on") { $wantedAddCountStr='checked="checked"'; }

    if (get_option("showOwned")=="on") { $ownedAddStr='checked="checked"'; }
    if (get_option("showOwnedCount")=="on") { $ownedAddCountStr='checked="checked"'; }


    ?> 
    <input type="hidden" name="updateWantHaveOptions" value="true" />

    <h2>Most Owned</h2>
    Show Most Owned List: <input type="checkbox" name="showOwned" <?php echo $ownedAddStr ?> /><br />
    Owned Title: <input type="text" name="OwnedTitle" value="<?php echo get_option("OwnedTitle") ?>" /><br />
    Maximum No. of Items: <input type="text" name="OwnedMax" value="<?php echo get_option("OwnedMax") ?>" /><br />
    Show Vote Count: <input type="checkbox" name="showOwnedCount" <?php echo $ownedAddCountStr ?> /><br />

    <hr />

    <h2>Most Wanted</h2>
    Show Most Wanted List: <input type="checkbox" name="showWanted" <?php echo $wantedAddStr ?> /><br />
    Wanted Title: <input type="text" name="WantedTitle" value="<?php echo get_option("WantedTitle") ?>" /><br />
    Maximum No. of Items: <input type="text" name="WantedMax" value="<?php echo get_option("WantedMax") ?>" /><br />
    Show Vote Count: <input type="checkbox" name="showWantedCount" <?php echo $wantedAddCountStr ?> /><br />
    <?php
    }

    register_sidebar_widget('Most Wanted/Owned Gadgets', 'widget_wantHaveCount');
    register_widget_control('Most Wanted/Owned Gadgets','widget_wantHaveOptions');
  }


add_action("widgets_init", "initHaveWidget");

