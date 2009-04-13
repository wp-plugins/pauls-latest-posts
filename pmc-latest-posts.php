<?php
/*
Plugin Name: Pauls Latest Posts
Plugin URI: http://www.paulmc.org/whatithink/wordpress/plugins/pauls-latest-posts/
Description: Plugin to display your latest posts with excerpt in a sidebar widget.
Author: Paul McCarthy
Version: 1.9
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
			$pmcRandOffset = $pmcOptions['pmc_rand_offset'];
			$pmcReadMore = $pmcOptions['pmc_read_more'];
			$pmcUseTitle = $pmcOptions['pmc_use_title'];
			$pmcShowPosts = $pmcOptions['pmc_show_posts'];
			$pmcShowPostsTitle = $pmcOptions['pmc_show_posts_title'];
			$pmcPostsTitle = $pmcOptions['pmc_posts_title'];
			$pmcShowCommentsTitle = $pmcOptions['pmc_show_comments_title'];
			$pmcCommentsTitle = $pmcOptions['pmc_comments_title'];
			$pmcCategory = $pmcOptions['pmc_cat_list'];
			$pmcShowDate = $pmcOptions['pmc_show_date'];
			$pmcDateFormat = $pmcOptions['pmc_date_format'];
			
			//if the user has specified a random offset
			if ($pmcRandOffset != 'on') {				
				//build the parameter list for get_posts
				$pmcParameters = 'numberposts=' . $pmcNumPosts . '&offset=' . $pmcPostOffset;
			} else {
				//set the minimum bounds for rand()
				$pmcRandMin = 0;
				//set the maximum bounds for rand()
				//first get the total amount of posts
				$pmcTotalPosts = wp_count_posts();
				//now get the amount of published posts
				$pmcRandMax = $pmcTotalPosts->publish;
				
				//set the offset to a random number
				$pmcPostOffset = rand($pmcRandMin, $pmcRandMax);
				
				//build the parameter list
				$pmcParameters = 'numberposts=' . $pmcNumPosts . '&offset=' . $pmcPostOffset;
			} //close if
				
			//check if the user has selected a category to display posts
			if ($pmcCategory != 'all') {
				$pmcParameters .= '&category_name="' . $pmcCategory . '"';
			}
			
			//get the posts
			$pmcPosts = get_posts($pmcParameters);

			//start building the list
			echo $before_widget;
			
			//check if the user wants to use the widget title
			if ($pmcUseTitle) {
				echo $before_title . $pmcTitle . $after_title;
			} //close if
			
			//check if the user wants to display the posts
			if ($pmcShowPosts) {
				
				//check if the user wants to display the Posts title
				if ($pmcShowPostsTitle) {
					//echo the Posts heading
					echo '<h3 class="pmc-h3">' . $pmcPostsTitle . '</h3>';
				} //close if

				//start the list to hold the output
				echo '<ul>';
			
				//loop through the posts
				foreach ($pmcPosts as $pmcSinglePost) {
		
					//get the post data
					setup_postdata($pmcSinglePost);

					//retrieve the post id and use this to get the permalink
					$pmcLINK = $pmcSinglePost->ID;		
					$pmcHREF = get_permalink($pmcLINK);
					
					//get the post date and convert it to a proper timestamp
					$pmcPostDate = $pmcSinglePost->post_date;
					$pmcDate = strtotime($pmcPostDate);
		
					//get the post title
					$pmcTITLE = $pmcSinglePost->post_title;
						
					//start creating the output
					echo '<li>';
					
					//create the link to the post
					echo '<a class="pmc-link" href="' . $pmcHREF . '">' . $pmcTITLE . '</a><br />' . "\n";
					
					//check if the user wants to display the date
					if ($pmcShowDate) {
						//output the date
						echo '<div class="pmc-date">' . date($pmcDateFormat, $pmcDate) . '</div>';
					}
	
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
						echo '</span> ';
						echo '<a class="pmc-read-more" href="' . $pmcHREF . '">' . $pmcReadMore . '</a>';
					}
					
					//close the post 
					echo '</li>';
									
				} //close foreach
			
				//close the list
				echo '</ul>';
			} //close if - Show Posts check
			
			//check if the user wants to display the latest comments
			if ($pmcShowComments) {	
				//get the comment rss feed URL
				$commentrss = get_bloginfo('comments_rss2_url');
				
				//check if the user wants to display comments title
				if ($pmcShowCommentsTitle) {
					//set the title
					echo '<h3 class="pmc-h3">' . $pmcCommentsTitle . '</h3>';
				} //close if
				
				//we need the in-built WP RSS parser for this to work
				require_once(ABSPATH . WPINC . '/rss-functions.php');
				
				//build the list
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
				//array_push($words, $pmcReadMore);
				$text = implode(' ', $words);
			}
			//add quote marks to start and end of array
			$text = '"' . $text . ' ..." ';
			return $text;
		}

		//function to build the config panel
		function pmcLatestPosts_control() {
			//get the options from the wp database
			$options = $newoptions = get_option('widget_pmcLatestPosts');
			
			if ( $_POST['pmc_latest_posts_submit'] ) {
				//strip the user entered options for anything that shouldn't be there
				$newoptions['pmc_title'] = strip_tags(stripslashes($_POST['pmc_title']));
				$newoptions['pmc_num_posts'] = (int) strip_tags(stripslashes($_POST['pmc_num_posts']));
				$newoptions['pmc_size'] = (int) strip_tags(stripslashes($_POST['pmc_size']));
				$newoptions['pmc_post_offset'] = (int) strip_tags(stripslashes($_POST['pmc_post_offset']));
				$newoptions['pmc_show_comments'] = strip_tags(stripslashes($_POST['pmc_show_comments']));
				$newoptions['pmc_num_comments'] = strip_tags(stripslashes($_POST['pmc_num_comments']));
				$newoptions['pmc_strip_tags'] = strip_tags(stripslashes($_POST['pmc_strip_tags']));
				$newoptions['pmc_read_more'] = strip_tags(stripslashes($_POST['pmc_read_more']));
				$newoptions['pmc_rand_offset'] = strip_tags(stripslashes($_POST['pmc_rand_offset']));
				$newoptions['pmc_h3_pmc-h3'] = strip_tags(stripslashes($_POST['pmc_h3_pmc-h3']));
				$newoptions['pmc_a_pmc-link'] = strip_tags(stripslashes($_POST['pmc_a_pmc-link']));
				$newoptions['pmc_span_pmc-excerpt'] = strip_tags(stripslashes($_POST['pmc_span_pmc-excerpt']));
				$newoptions['pmc_a_read_more'] = strip_tags(stripslashes($_POST['pmc_a_read_more']));
				$newoptions['pmc_use_title'] = strip_tags(stripslashes($_POST['pmc_use_title']));
				$newoptions['pmc_show_posts'] = strip_tags(stripslashes($_POST['pmc_show_posts']));
				$newoptions['pmc_show_posts_title'] = strip_tags(stripslashes($_POST['pmc_show_posts_title']));
				$newoptions['pmc_posts_title'] = strip_tags(stripslashes($_POST['pmc_posts_title']));
				$newoptions['pmc_show_comments_title'] = strip_tags(stripslashes($_POST['pmc_show_comments_title']));
				$newoptions['pmc_comments_title'] = strip_tags(stripslashes($_POST['pmc_comments_title']));
				$newoptions['pmc_use_styles'] = strip_tags(stripslashes($_POST['pmc_use_styles']));
				$newoptions['pmc_cat_list'] = strip_tags(stripslashes($_POST['pmc_cat_list']));
				$newoptions['pmc_show_date'] = strip_tags(stripslashes($_POST['pmc_show_date']));
				$newoptions['pmc_date_format'] = strip_tags(stripslashes($_POST['pmc_date_format']));
				$newoptions['pmc_date_style'] = strip_tags(stripslashes($_POST['pmc_date_style']));
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
			if ( !$options['pmc_post_offset'] and $options['pmc_post_offset'] !=0) $options['pmc_post_offset'] = 1;
			if ( !$options['pmc_show_comments'] ) $options['pmc_show_comments'] = '';
			if ( !$options['pmc_num_comments'] ) $options['pmc_num_comments'] = 5;
			if ( !$options['pmc_strip_tags'] ) $options['pmc_strip_tags'] = '';
			if ( !$options['pmc_read_more'] ) $options['pmc_read_more'] = "Read More";
			if ( !$options['pmc_rand_offset'] ) $options['pmc_rand_offset'] = '';
			if ( !$options['pmc_h3_pmc-h3'] ) $options['pmc_h3_pmc-h3'] = 'text-align: center;';
			if ( !$options['pmc_a_pmc-link'] ) $options['pmc_a_pmc-link'] = 'font-weight: bold; font-style: normal; font-size: 1em;';
			if ( !$options['pmc_span_pmc-excerpt'] ) $options['pmc_span_pmc-excerpt'] = 'font-style: italic; font-size: .8em;';
			if ( !$options['pmc_a_read_more'] ) $options['pmc_a_read_more'] = 'font-weight: bold; font-size: .8em;';
			if ( !$options['pmc_posts_title'] ) $options['pmc_posts_title'] = 'Posts';
			if ( !$options['pmc_comments_title'] ) $options['pmc_comments_title'] = 'Comments';
			if ( !$options['pmc_cat_list'] ) $options['pmc_cat_list'] = 'all';
			if ( !$options['pmc_date_format'] ) $options['pmc_date_format'] = 'l, jS \of F, Y.';
			if ( !$options['pmc_date_style'] ) $options['pmc_date_style'] = 'font-weight: bold; margin: 3px auto; padding: 3px; font-size: .8em;';
			
			//store the options we got from the database
			$pmcTitle = htmlspecialchars($options['pmc_title'], ENT_QUOTES);
			$pmcNumPosts = htmlspecialchars($options['pmc_num_posts'], ENT_QUOTES);
			$pmcSize = htmlspecialchars($options['pmc_size'], ENT_QUOTES);
			$pmcPostOffset = htmlspecialchars($options['pmc_post_offset'], ENT_QUOTES);
			$pmcShowComments = htmlspecialchars($options['pmc_show_comments'], ENT_QUOTES);
			$pmcNumComments = htmlspecialchars($options['pmc_num_comments'], ENT_QUOTES);
			$pmcStripTags = htmlspecialchars($options['pmc_strip_tags'], ENT_QUOTES);
			$pmcReadMore = htmlspecialchars($options['pmc_read_more'], ENT_QUOTES);
			$pmcRandOffset = htmlspecialchars($options['pmc_rand_offset'], ENT_QUOTES);
			$pmcH3 = htmlspecialchars($options['pmc_h3_pmc-h3'], ENT_QUOTES);
			$pmcA = htmlspecialchars($options['pmc_a_pmc-link'], ENT_QUOTES);
			$pmcSpan = htmlspecialchars($options['pmc_span_pmc-excerpt'], ENT_QUOTES);
			$pmcMore = htmlspecialchars($options['pmc_a_read_more'], ENT_QUOTES);
			$pmcUseTitle = htmlspecialchars($options['pmc_use_title'], ENT_QUOTES);
			$pmcShowPosts = htmlspecialchars($options['pmc_show_posts'], ENT_QUOTES);
			$pmcShowPostsTitle = htmlspecialchars($options['pmc_show_posts_title'], ENT_QUOTES);
			$pmcPostsTitle = htmlspecialchars($options['pmc_posts_title'], ENT_QUOTES);
			$pmcShowCommentsTitle = htmlspecialchars($options['pmc_show_comments_title'], ENT_QUOTES);
			$pmcCommentsTitle = htmlspecialchars($options['pmc_comments_title'], ENT_QUOTES);
			$pmcUseStyles = htmlspecialchars($options['pmc_use_styles'], ENT_QUOTES);
			$pmcCurrCat = htmlspecialchars($options['pmc_cat_list'], ENT_QUOTES);
			$pmcShowDate = htmlspecialchars($options['pmc_show_date'], ENT_QUOTES);
			$pmcDateFormat = htmlspecialchars($options['pmc_date_format'], ENT_QUOTES);
			$pmcDateStyle = htmlspecialchars($options['pmc_date_style'], ENT_QUOTES);
			
			//WP stores a check box as Null or "on", in order to display properly, we need to convert "on" to "checked=yes"
			if ($pmcShowComments) $pmcShowComments = ' checked="yes" ';
			if ($pmcStripTags) $pmcStripTags = ' checked="yes" ';
			if ($pmcRandOffset) $pmcRandOffset = ' checked="yes" ';
			if ($pmcUseTitle) $pmcUseTitle = ' checked="yes" ';
			if ($pmcShowPosts) $pmcShowPosts = ' checked="yes" ';
			if ($pmcShowPostsTitle) $pmcShowPostsTitle = ' checked="yes" ';
			if ($pmcShowCommentsTitle) $pmcShowCommentsTitle = ' checked="yes" ';
			if ($pmcUseStyles) $pmcUseStyles = ' checked="yes" ';
			if ($pmcShowDate) $pmcShowDate = ' checked="yes" ';
			
		echo '<h3 style="text-align: center; text-decoration: underline;">General Options</h3>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_use_title">' . __('Use Title?', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_use_title" name="pmc_use_title" type="checkbox"'.$pmcUseTitle.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_title">' . __('Title:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_title" name="pmc_title" type="text" value="'.$pmcTitle.'" /></label></p>';
		echo '<h3 style="text-align: center; text-decoration: underline;">Post Options</h3>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_posts">' . __('Show Posts?', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_posts" name="pmc_show_posts" type="checkbox" '.$pmcShowPosts.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_posts_title">' . __('Show Posts Title?', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_posts_title" name="pmc_show_posts_title" type="checkbox"'.$pmcShowPostsTitle.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_posts_title">' . __('Posts Title:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_posts_title" name="pmc_posts_title" type="text" value="'.$pmcPostsTitle.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_cat_list">' . __('Display Only Posts from Category:', 'widgets');
		echo pmcCreateCatList($pmcCurrCat);
		echo '</label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_num_posts">' . __('Number of Posts:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_num_posts" name="pmc_num_posts" type="text" value="'.$pmcNumPosts.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_size">' . __('Excerpt Size:', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_size" name="pmc_size" type="text" value="'.$pmcSize.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_rand_offset">' . __('Randomise Post Offset:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_rand_offset" name="pmc_rand_offset" type="checkbox" '.$pmcRandOffset.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_post_offset">' . __('Post Offset:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_post_offset" name="pmc_post_offset" type="text" value="'.$pmcPostOffset.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_read_more">' . __('Read More Text:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_read_more" name="pmc_read_more" type="text" value ="'.$pmcReadMore.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_strip_tags">' . __('Allow HTML in Excerpt?:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_strip_tags" name="pmc_strip_tags" type="checkbox"'.$pmcStripTags.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_date">' . __('Show Post Date?:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_date" name="pmc_show_date" type="checkbox"'.$pmcShowDate.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_date_format">' . __('Date Format:', 'widget') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_date_format" name="pmc_date_format" type="text" value="'.$pmcDateFormat.'" /></label>See <a href="http://www.php.net/manual/function.date.php" title="PHP: date - Manual" target="_blank">PHP Date</a> for formatting options.</p>';
		echo '<h3 style="text-align: center; text-decoration: underline;">Comment Options</h3>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_comments">' . __('Show Latest Comments?:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_comments" name="pmc_show_comments" type="checkbox"'.$pmcShowComments.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_show_comments_title">' . __('Show Comments Title?', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_show_comments_title" name="pmc_show_comments_title" type="checkbox"'.$pmcShowCommentsTitle.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_comments_title">' . __('Comments Title:', 'widget') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_comments_title" name="pmc_comments_title" type="text" value="'.$pmcCommentsTitle.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_num_comments">' . __('Number of Comments:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_post_num_comments" name="pmc_num_comments" type="text" value="'.$pmcNumComments.'" /></label></p>';
		echo '<h3 style="text-align: center; text-decoration: underline;">CSS Style Options</h3>';
		echo '<p>Note: Do not include {} braces when specifying your CSS styles.</p>';
		echo '<p>If you prefer to specify the CSS in your own stylesheet, leave the settings as they are and untick the "Use These Styles" checkbox.</p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_use_styles">' . __('Use These Styles?', 'widgets') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_use_styles" name="pmc_use_styles" type="checkbox"'.$pmcUseStyles.' /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_h3_pmc-h3">' . __('Style for Posts/ Comments Heading:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_h3_pmc-h3" name="pmc_h3_pmc-h3" type="text" value="'.$pmcH3.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_a_pmc-link">' . __('Style for Post Link:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_a_pmc-link" name="pmc_a_pmc-link" type="text" value="'.$pmcA.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_span_pmc-excerpt">' . __('Style for Excerpt Text:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_span_pmc-excerpt" name="pmc_span_pmc-excerpt" type="text" value="'.$pmcSpan.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_a_read_more">' . __('Style for Read More Link:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_a_read_more" name="pmc_a_read_more" type="text" value="'.$pmcMore.'" /></label></p>';
		echo '<p style="margin: 20px auto;"><label style="display: block; width:300px; text-align: centre;" for="pmc_date_style">' . __('Style for Date:') . ' <input style="display: block; width: 300px; text-align: left;" id="pmc_date_style" name="pmc_date_style" type="text" value="'.$pmcDateStyle.'" /></label></p>';
		echo '<input type="hidden" id="pmc_latest_posts_submit" name="pmc_latest_posts_submit" value="1" />';
		}
		
			
		//function to load user defined styles in header
		function pmcLoadStyles() {
			//get options from database
			$pmcStyles=get_option('widget_pmcLatestPosts');
			$pmcH3style = $pmcStyles['pmc_h3_pmc-h3'];
			$pmcAstyle = $pmcStyles['pmc_a_pmc-link'];
			$pmcSpanstyle = $pmcStyles['pmc_span_pmc-excerpt'];
			$pmcMorestyle = $pmcStyles['pmc_a_read_more'];
			$pmcDatestyle = $pmcStyles['pmc_date_style'];
			$pmcUseStyles = $pmcStyles['pmc_use_styles'];
			
			//check if the user wants to use inbuilt styles options
			if ($pmcUseStyles) {
				//build the style using user styles
				$pmcHTML = '<!-- styles for Pauls Latest Posts Widget -->' . "\n";
				$pmcHTML .= '<style type="text/css">' . "\n";
				$pmcHTML .= 'h3.pmc-h3 {' . "\n";
				$pmcHTML .= $pmcH3style . "\n";
				$pmcHTML .= '}' . "\n";
				$pmcHTML .= 'a.pmc-link {' . "\n";
				$pmcHTML .= $pmcAstyle . "\n";
				$pmcHTML .= '}' . "\n";
				$pmcHTML .= 'span.pmc-excerpt {' . "\n";
				$pmcHTML .= $pmcSpanstyle . "\n";
				$pmcHTML .= '}' . "\n";
				$pmcHTML .= 'a.pmc-read-more {' . "\n";
				$pmcHTML .= $pmcMorestyle . "\n";
				$pmcHTML .= '}' . "\n";
				$pmcHTML .= 'div.pmc-date {' . "\n";
				$pmcHTML .= $pmcDatestyle . "\n";
				$pmcHTML .= '}' . "\n";
				$pmcHTML .='</style>' . "\n";
			
				//echo the styles
				echo $pmcHTML;
			} //close if
		} 
		
		//function to create drop down box for category list
		function pmcCreateCatList($pmcCategory) {
			//get the list of categories
			$pmcCatList = get_categories("type=post&orderby='name'&hide_empty=1&hierarchical=0");
			
			//start building the drop down box
			$pmcSelect = '<select style="display: block; width: 300px; height: auto; text-align: left;" id="pmc_cat_list" name="pmc_cat_list">';
			$pmcSelect .= '<option value="all">All Categories</option>';
			
			//loop through the category list and build the select
			foreach ($pmcCatList as $pmcCat) {
				//get the category name
				$pmcCatName = $pmcCat->cat_name;
				
				//get the category nicename
				$pmcCatNice = $pmcCat->category_nicename;
				
				//add the option
				$pmcSelect .= '<option value="' . $pmcCatNice . '"';
				
				//check for the currently selected category
				if ($pmcCategory == $pmcCatNice) {
					 $pmcSelect .= ' selected="selected"';
				}
				
				//finish off the option.
				$pmcSelect .= '>' . $pmcCatName . '</option>';
				
			} //close foreach
			
			//close off the select
			$pmcSelect .= '</select>';
			
			//return the completed list
			return $pmcSelect;
		}
	//register the widget
	register_sidebar_widget('Pauls Latest Posts', 'widget_pmcLatestPosts');
	register_widget_control('Pauls Latest Posts', 'pmcLatestPosts_control', 300, 400 );

}

//have wordpress load the widget
add_action("plugins_loaded", "widget_pmcLatestPosts_init"); 
//add the styles to the document head
add_action("wp_head", "pmcLoadStyles");
?>