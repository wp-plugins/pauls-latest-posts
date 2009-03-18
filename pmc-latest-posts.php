<?php
/*
Plugin Name: Pauls Latest Posts
Plugin URI: http://www.paulmc.org/whatithink/wordpress/plugins/pauls-latest-posts/
Description: Plugin to display your latest posts with excerpt in a sidebar widget.
Author: Paul McCarthy
Version: 1.3
Author URI: http://www.paulmc.org/whatithink
*/

// Put functions into one big function we'll call at the plugins_loaded
// action. This ensures that all required plugin functions are defined.
function widget_pmcLatestPosts_init() {
		
		if (!function_exists('register_sidebar_widget')) {
		return;
	}

		//function to output the latest posts and excerpts
		function widget_pmcLatestPosts($pmcArgs) {
			//get the arguments passed back by wordpress
			extract($pmcArgs);
	
			//we get the options that the user sets in the config panel
			$pmcOptions = get_option('widget_pmcLatestPosts');
		
			//store the options
			$pmcTitle = $pmcOptions['pmc_title'];
			$pmcNumPosts = $pmcOptions['pmc_num_posts'];
			$pmcExcerptSize = $pmcOptions['pmc_size'];
			$pmcPostOffset = $pmcOptions['pmc_post_offset'];
			$pmcShowComments = $pmcOptions['pmc_show_comments'];
			$pmcNumComments = $pmcOptions['pmc_num_comments'];
			
			//build the parameter list for get_posts
			$pmcParameters = 'numberposts=' . $pmcNumPosts . '&offset=' . $pmcPostOffset;
			//get the posts
			$pmcPosts = get_posts($pmcParameters);

			//start building the list
			echo $before_widget . $before_title . $pmcTitle . $after_title;
			
			//echo the Posts heading
			echo '<h3 class="pmc-h3">Posts</h3>';

			//start the list to hold the output
			echo '<ul>';
			
			//loop through the posts
			foreach ($pmcPosts as $pmcSinglePost) {
		
				//get the post data
				setup_postdata($pmcSinglePost);
		
				//retrieve the post id and use this to get the permalink
				$pmcLINK = $pmcSinglePost->ID;		
				$pmcHREF = get_permalink($pmcLINK);
		
				//get the post title
				$pmcTITLE = $pmcSinglePost->post_title;
						
				//create the link
				echo '<li><a class="pmc-link" href="' . $pmcHREF . '">' . $pmcTITLE . '</a><br />';

				//get the content of the post
				$pmcFullContent = get_the_content('');
				
				//call the function to trim it according to our settings
				$pmcTrimmedContent = pmc_trim_excerpt($pmcFullContent);
				
				//check to see if the excerpt size is set to 0
				//if so the we won't display it.
				if ($pmcExcerptSize != 0) {
					//output the excerpt
					
					echo '<span class="pmc-excerpt">';
					echo $pmcTrimmedContent;
					echo '</span></li>';
				}
				
			} //close foreach
			
			//close the list
			echo '</ul>';
			
			//check if the user wants to display the latest comments
			if ($pmcShowComments) {	
				//get the comment rss feed URL
				$commentrss = get_bloginfo('comments_rss2_url');
				
				//set the title
				$pmcTitle = '<h3 class="pmc-h3">Comments</h3>';
				
				//we need the in-built WP RSS parser for this to work
				require_once(ABSPATH . WPINC . '/rss-functions.php');
				
				//build the list
				echo $pmcTitle;
				echo '<ul>';
				//get the rss feed
				get_rss($commentrss, $pmcNumComments);
				echo '</ul>';
			}
		
			//finish off the widget
			echo $after_widget;
		} //close widget_pmcLatestPosts
		
		//function taken from formatting.php, used by WordPress to trim the excerpt
		//we'll use this function as the basis of trimming the content and stripping HTML tags as per the settings provided by the user
		function pmc_trim_excerpt($text) {
			//get the settings for our widget
			$pmcOptions = get_option('widget_pmcLatestPosts');
			//store excerpt_size, strip_tags, and read_more
			$pmcExcerptSize = $pmcOptions['pmc_size'];
			$pmcStripTags = $pmcOptions['pmc_strip_tags'];
			$pmcReadMore = $pmcOptions['pmc_read_more'];
			

			$text = strip_shortcodes( $text );

			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			if (!$pmcStripTags) {
				$text = strip_tags($text);
			}
			$excerpt_length = apply_filters('excerpt_length', $pmcExcerptSize);
			$words = explode(' ', $text, $excerpt_length + 1);
			if (count($words) > $excerpt_length) {
				array_pop($words);
				array_push($words, $pmcReadMore);
				$text = implode(' ', $words);
			}
			return $text;
		}

		//function to build the config panel
		function pmcLatestPosts_control() {
			//get the options from the wp database
			$options = $newoptions = get_option('widget_pmcLatestPosts');
			
			if ( $_POST['pmc_latest_posts_submit'] ) {
				//strip the user entered options for anything that shouldn't be there
				$newoptions['pmc_title'] = strip_tags(stripslashes($_POST['pmc_title']));
				$newoptions['pmc_num_posts'] = (int) $_POST['pmc_num_posts'];
				$newoptions['pmc_size'] = (int) $_POST['pmc_size'];
				$newoptions['pmc_post_offset'] = (int) $_POST['pmc_post_offset'];
				$newoptions['pmc_show_comments'] = $_POST['pmc_show_comments'];
				$newoptions['pmc_num_comments'] = $_POST['pmc_num_comments'];
				$newoptions['pmc_strip_tags'] = $_POST['pmc_strip_tags'];
				$newoptions['pmc_read_more'] = $_POST['pmc_read_more'];
				} //close if
			
			//if there's been a change, do an update
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('widget_pmcLatestPosts', $options);
			} //close if

			//These are the default settings
			if ( !$options['pmc_title'] ) $options['pmc_title'] = 'Latest Activity';
			if ( !$options['pmc_num_posts'] ) $options['pmc_num_posts'] = 5;
			if ( !$options['pmc_size'] ) $options['pmc_size'] = 25;
			if ( !$options['pmc_post_offset'] ) $options['pmc_post_offset'] = 1;
			if ( !$options['pmc_show_comments'] ) $options['pmc_show_comments'] = '';
			if ( !$options['pmc_num_comments'] ) $options['pmc_num_comments'] = 5;
			if ( !$options['pmc_strip_tags'] ) $options['pmc_strip_tags'] = '';
			if ( !$options['pmc_read_more'] ) $options['pmc_read_more'] = "Read More";
			
			//store the options we got from the database
			$pmcTitle = htmlspecialchars($options['pmc_title'], ENT_QUOTES);
			$pmcNumPosts = htmlspecialchars($options['pmc_num_posts'], ENT_QUOTES);
			$pmcSize = htmlspecialchars($options['pmc_size'], ENT_QUOTES);
			$pmcPostOffset = htmlspecialchars($options['pmc_post_offset'], ENT_QUOTES);
			$pmcShowComments = htmlspecialchars($options['pmc_show_comments'], ENT_QUOTES);
			$pmcNumComments = htmlspecialchars($options['pmc_num_comments'], ENT_QUOTES);
			$pmcStripTags = htmlspecialchars($options['pmc_strip_tags'], ENT_QUOTES);
			$pmcReadMore = htmlspecialchars($options['pmc_read_more'], ENT_QUOTES);
			
			//WP stores a check box as Null or "on", in order to display properly, we need to convert "on" to "checked=yes"
			if ($pmcShowComments) {
				$pmcShowComments = ' checked="yes" ';
			} else {
				$pmcShowComments = '';
			}
			
			if ($pmcStripTags) {
				$pmcStripTags = ' checked="yes" ';
			} else {
				$pmcStripTags = '';
			}
			
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_title">' . __('Title:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_title" name="pmc_title" type="text" value="'.$pmcTitle.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_num_posts">' . __('Number of Posts:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_num_posts" name="pmc_num_posts" type="text" value="'.$pmcNumPosts.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_size">' . __('Excerpt Size:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_size" name="pmc_size" type="text" value="'.$pmcSize.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_post_offset">' . __('Post Offset:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_post_offset" name="pmc_post_offset" type="text" value="'.$pmcPostOffset.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_read_more">' . __('Read More Text:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_read_more" name="pmc_read_more" type="text" value ="'.$pmcReadMore.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_strip_tags">' . __('Allow HTML in Excerpt?:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_strip_tags" name="pmc_strip_tags" type="checkbox"'.$pmcStripTags.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_comments">' . __('Show Latest Comments?:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_comments" name="pmc_show_comments" type="checkbox"'.$pmcShowComments.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_num_comments">' . __('Number of Comments:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_post_num_comments" name="pmc_num_comments" type="text" value="'.$pmcNumComments.'" /></label></p>';
		echo '<input type="hidden" id="pmc_latest_posts_submit" name="pmc_latest_posts_submit" value="1" />';
		}

	//register the widget
	register_sidebar_widget('Pauls Latest Posts', 'widget_pmcLatestPosts');
	register_widget_control('Pauls Latest Posts', 'pmcLatestPosts_control', 300, 400 );

}

//have wordpress load the widget
add_action("plugins_loaded", "widget_pmcLatestPosts_init"); 
?>