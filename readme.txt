=== Pauls Latest Posts ===
Contributors: paulmac
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=LPT7UT9QE2G42&lc=IE&item_name=Paul%20McCarthy&amount=1%2e00&currency_code=EUR&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: posts, excerpts, lastest, widget, sidebar
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: trunk

Display latest posts with excerpts and comments in a sidebar widget.

== Description ==

Pauls Latest Posts displays a list of your latest posts and comments in the sidebar with excerpts. Excerpts length can be modified or left out completely. Useful for sites that display one post on the main page, and want to link to previous posts.

== Installation ==

1. Extract pauls-latest-posts.zip
1. Upload pauls-latest-posts.php to your WordPress plugin directory.
1. Activate the plugin from the 'Plugins' menu in WordPress.
1. Add the widget to your sidebar from the 'Widgets' menu in WordPress.

== Frequently Asked Questions ==

= What settings can I change? =
The plugin page allows you to change the following settings:

1. General Options
* Use Title: Choose whether the widget title should be displayed or not.
* Title: Title of the Sidebar widget.
1. Post Options
* Show Posts: Choose whether Posts should be displayed or not.
* Show Posts Title: Choose whether the title for the Posts section should be displayed or not.
* Posts Title: Title for the Posts section.
* Display Only Posts From Category: Choose to restrict posts from a particular category. Default is to display posts from all categories.
* Number of Posts: Number of posts to show in the Sidebar.
* Excerpt Size: Number of words to display in the excerpt for the post.
* Randomise Post Offset: Picks a random offset and displays the next "Number of Posts" from that offset. Over-rides any specified "Post Offset".
* Post Offset: The number of posts to skip before displaying the list in the Sidebar. Numbering starts from 0. To ignore the latest post, set the offset to 1.
* Read More Text: Text display at the end of the excerpt. Defaults to "Read More".
* Allow HTML in Excerpt: If left unticked, all HTML tags will be stripped, leaving only plain text. Enabling this option will leave in the HTML.
* Show Date: Displays the date the post was originally published.
1. Comments Options
* Show Latest Comments: Should the widget also display your latest comments.
* Show Comments Title: Choose whether the title for the Comments section should be displayed or not.
* Comments Title: Title for the Comments section.
* Number of Comments: Number of comments to show in the Sidebar.
1. Style Options
* Use These Styles: Tick this box is you want the widget to insert the specified styles in the header of your blog. Leave this unticked if you prefer to specify the styles in your own stylesheet.
* Style for Posts/ Comments Heading: Set CSS style for the H3 heading used to display "Posts" and "Comments".
* Style for Post Link: Set CSS style for link to article.
* Style for Excerpt Text: Set CSS style for the excerpt.
* Style for Read More Link: Set CSS style for "Read More".
* Style for date: Set CSS style for the date.

Note: When specifying the styles there is no need to include {} at the beginning and end of your styles. See the default styles for an example.

= What if I don't want to show the excerpt? =
Change the Excerpt Size to 0 and no excerpt will be inserted.

= I'm using an old version of WordPress, will this plugin work? =
This plugin uses the Widget API built into WordPress since version 2.3. Therefore, this plugin will not work on versions older than 2.3. If you are using a version of WordPress prior to 2.3, then I suggest that you upgrade to the latest version.

= Can I re-use your code in my own plugin? =
If you want to, then yes. This plugin is released under the GPL. You are free to modify it, change it, re-distribute it as you want.

= I want to use my themes stylesheet to specify the styles for your plugin. What are the CSS ID's that I need to include? =
The plugin uses the following CSS ID's:

* h3.pmc-h3: used for Post and Comment section titles.
* a.pmc-link: used for the link to the post.
* span.pmc-excerpt: used for the excerpt.
* a.pmc-read-more: used for the "Read More" link.
* div.pmc-date: used for the date.

== Changelog ==

= Version 1.9 =
* Added option to display post date.
* Added option to style post date.
* General code cleanup

= Version 1.8 =
* Bug Fix: Post offset of 0 not being saved correctly.

= Version 1.7 =
* Added option to restrict latest posts to a specified category.

= Version 1.6 =
* Added option to enable/disable display of:
1. Widget Title
1. Post Title
1. Comment Title
1. Styles
* Added options to specify:
1. Post Title
1. Comment Title

= Version 1.5 =
* Added style options.
* Added link to "Read More".
* Tweaked HTML output to display better.
* Added default styles.

== Feedback ==

Feedback is welcome as are feature requests. Just leave a comment on the [Plugin Page](http://www.paulmc.org/whatithink/wordpress/plugins/pauls-latest-posts/).

== Thanks ==

Thanks goes to the following people: Simon who suggested that I add the Latest Comments functionality, Juno for suggesting the HTML functionality, the ability to change the Read More text, specify post and comment titles, disabling and enabling post title, comment title and styles. Wil for suggesting the Randomised Offset setting, Farabi for suggesting the category option, Peter for reporting the bug fixed in version 1.8, Charles for the post date.
