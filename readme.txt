=== Plugin Name ===
Contributors: DanielSands
Donate link: http://www.danielsands.co.cc/
Tags: Gadget, Technology, Want, Have, Poll, Votes
Requires at least: 2.0.2
Tested up to: 2.7
Stable tag: 3.0.1

A Simple Plugin which allows you to have an "I want one" and "I have one" on your blog posts, and then display the results in a widget.

== Description ==

A Simple Plugin which allows you to have an "I want one" and "I have one" on your blog posts, and then display the results in a widget.
It's as simple as it sounds really, I saw this feature on the GadgetShow website and decided to replicate it in to a Wordpress plugin. 
It's great for anyone who has a Technology blog, as you can see how effective your blog posts are, and get an idea of your target audience.

**Version History**
>**2.0**: *New Features* - <br />
>1. Changed voting method to using the permalink of a vote to using AJAX, thereby votes are alot quicker and don't result in a refresh.<br />
>2. You no longer have to use the <!--havewantbutton--> to specify the use of the buttons for a post, there's now an option in the edit posts/pages screen which allows you to show the buttons or not.<br />
>3. You can now specify where you want the buttons to appear in respect to the post - topright, topleft, bottomleft or bottomright.<br />
>4. You can now choose from 5 default themes to decide the appearance of the buttons, or create your own theme (advanced).<br />
>5. All coding has been cleaned up and split into seperate php files for better readability.<br />
>6. Added an option to specify the message the end-user will see once they have voted.

>**0.01**: *Initial Release*

**updating to version 2.0**<br />
As this is a major release it is advised that you disable the plugin, delete the wantHave folder and overwrite it with the downloaded folder. All current votes will remain intact as they are stored in the wordpress database.

== Screenshots ==

1. Want have buttons with iWant_Black Theme.
2. Configurable layout options for buttons.
3. Configurable themes in admin settings.
4. Configurable sidebar widget to display results.

== Installation ==

1. Upload the wantHave folder to your wp-content/plugins folder, **NOTE** it's important that you upload the plugin to a folder named 'wantHave'.
1. Simply activate the plugin.
1. Choose a theme, button position and post-vote message from your Settings  - "iWant iHave" menu.
1. Activate the Widget and choose if you want to show how many people want the item and/or how many people own the item.
1. Now, when you write a post, simply tick the box at the bottom, and this will add the buttons underneath the content.
1. That's it! Enjoy! And please offer me any feedback!

== Frequently Asked Questions ==

Q. How do I create my own theme?<br />
A. A basic understanding of css/php is required, and you'll need access to your sites files. However, if you find the /themes folder in the plugin/wantHave directory and look into the iWant_Black folder you will see 3 files: "buttonBG.png", "style.css" and "template.php". The buttonBG image is the background to be used for the buttons, and the style.css file contains the necessary css to position the text within the buttons.  The template.php file contains options to specify the theme name, author, author url and button template, please change the first 3 options to suit you, *note* do not change the button template if you do not know what you are doing! 

Q. Can I just show the "i Want" button and not the "i Have" button?<br />
A. YES! You will need to edit the theme as above, and change the template so it doesn't include the ihave button.

If you have any further questions please visit my website [Here](http://www.danielsands.co.cc/ "Danielsands.co.cc")

