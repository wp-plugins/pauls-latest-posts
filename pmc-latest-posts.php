<?php
/*
Plugin Name: Pauls Latest Posts
Plugin URI: http://www.paulmc.org/whatithink/wordpress/plugins/
Description: Plugin to display your latest posts with excerpt in a sidebar widget.
Author: Paul McCarthy
Version: 1.0
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
			
			//build the parameter list for get_posts
			$pmcParameters = 'numberposts=' . $pmcNumPosts . '&offset=1';
			//get the posts
			$pmcPosts = get_posts($pmcParameters);

			//start building the list
			echo $before_widget . $before_title . $pmcTitle . $after_title;

			//start the list to hold the output
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
				echo '<h3 class="pmc-h3"><a class="pmc-link" href="' . $pmcHREF . '">' . $pmcTITLE . '</a></h3>';

				//check to see if the excerpt size is set to 0
				//if so the we won't display it.
				if ($pmcExcerptSize != 0) {
					//output the excerpt
					//we use the_content_rss as this allows us to set a size for the excerpt
					echo '<p class="pmc-excerpt">';
					the_content_rss('', TRUE, '', $pmcExcerptSize);
					echo '</p>';
				}
				
			} //close foreach
			
			
			//finish off the widget
			echo $after_widget;
		} //close widget_pmcLatestPosts
		
		//function to build the config panel
		function pmcLatestPosts_control() {
			//get the options from the wp database
			$options = $newoptions = get_option('widget_pmcLatestPosts');
			
			if ( $_POST['pmc_latest_posts_submit'] ) {
				//strip the user entered options for anything that shouldn't be there
				$newoptions['pmc_title'] = strip_tags(stripslashes($_POST['pmc_title']));
				$newoptions['pmc_num_posts'] = (int) $_POST['pmc_num_posts'];
				$newoptions['pmc_size'] = (int) $_POST['pmc_size'];
				} //close if
			
			//if there's been a change, do an update
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option('widget_pmcLatestPosts', $options);
			} //close if

			//These are the default settings
			if ( !$options['pmc_title'] ) $options['pmc_title'] = 'Latest Posts';
			if ( !$options['pmc_num_posts'] ) $options['pmc_num_posts'] = 5;
			if ( !$options['pmc_size'] ) $options['pmc_size'] = 25;
			
			//store the options we got from the database
			$pmcTitle = $options['pmc_title'];
			$pmcNumPosts = $options['pmc_num_posts'];
			$pmcSize = $options['pmc_size'];

			//strip any html characters from the title
			$pmcTitle = htmlspecialchars($options['pmc_title'], ENT_QUOTES);
			
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_title">' . __('Title:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_title" name="pmc_title" type="text" value="'.$pmcTitle.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_num_posts">' . __('Number of Posts:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_num_posts" name="pmc_num_posts" type="text" value="'.$pmcNumPosts.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_size">' . __('Excerpt Size:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_size" name="pmc_size" type="text" value="'.$pmcSize.'" /></label></p>';
		echo '<input type="hidden" id="pmc_latest_posts_submit" name="pmc_latest_posts_submit" value="1" />';
		}

	//register the widget
	register_sidebar_widget('Pauls Latest Posts', 'widget_pmcLatestPosts');
	register_widget_control('Pauls Latest Posts', 'pmcLatestPosts_control', 300, 300 );

}

//have wordpress load the widget
add_action("plugins_loaded", "widget_pmcLatestPosts_init"); 
?>