=== Pauls Latest Posts ===
Contributors: paulmac
Tags: posts, excerpts, lastest, widget, sidebar
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: trunk

Display latest posts with excerpts and comments in a sidebar widget.

== Description ==

Pauls Latest Pots displays a list of your latest posts and comments in the sidebar with excerpts. Excerpts length can be modified or left out completely. Useful for sites that display one post on the main page, and want to link to previous posts.

== Installation ==

1. Extract pauls-latest-posts.zip
1. Upload pauls-latest-posts.php to your WordPress plugin directory.
1. Activate the plugin from the 'Plugins' menu in WordPress.
1. Add the widget to your sidebar from the 'Widgets' menu in WordPress.

== Frequently Asked Questions ==

= What settings can I change? =
The plugin page allows you to change the following settings:

1. Title: Title of the Sidebar widget.
1. Number of Posts: Number of posts to show in the Sidebar.
1. Excerpt Size: Number of words to display in the excerpt for the post.
1. Randomise Post Offset: Picks a random offset and displays the next "Number of Posts" from that offset. Over-rides any specified "Post Offset".
1. Post Offset: The number of posts to skip before displaying the list in the Sidebar. Numbering starts from 0. To ignore the latest post, set the offset to 1.
1. Read More Text: Text display at the end of the excerpt. Defaults to "Read More".
1. Allow HTML in Excerpt: If left unticked, all HTML tags will be stripped, leaving only plain text. Enabling this option will leave in the HTML.
1. Show Comments: Should the widget also display your latest comments.
1. Number of Comments: Number of comments to show in the Sidebar.
1. Style for Posts/ Comments Heading: Set CSS style for the H3 heading used to display "Posts" and "Comments".
1. Style for Post Link: Set CSS style for link to article.
1. Style for Excerpt Text: Set CSS style for the excerpt.
1. Style for Read More Link: Set CSS style for "Read More".

Note: When specifying the styles there is no need to include {} at the beginning and end of your styles. See the default styles for an example.

= What if I don't want to show the excerpt? =
Change the Excerpt Size to 0 and no excerpt will be inserted.

= I'm using an old version of WordPress, will this plugin work? =
This plugin uses the Widget API built into WordPress since version 2.3. Therefore, this plugin will not work on versions older than 2.3. If you are using a version of WordPress prior to 2.3, then I suggest that you upgrade to the latest version.

= Can I re-use your code in my own plugin? =
If you want to, then yes. This plugin is released under the GPL. You are free to modify it, change it, re-distribute it as you want.

= What other functionality are you thinking of adding? =
I'm looking into adding a list of latest uploads to the plugin.

== Changelog ==

= Version 1.5 =
* Added style options.
* Added link to "Read More".
* Tweaked HTML output to display better.
* Added default styles.

== Thanks ==

Thanks goes to Simon who suggested that I add the Latest Comments functionality. If you would like to see anything else added to the list, please do not hesitate to leave a comment on the plugin homepage. Thanks to Juno for suggesting the HTML functionality and the ability to change the Read More text. Thanks to Wil for suggesting the Randomised Offset setting.
