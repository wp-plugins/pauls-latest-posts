=== Pauls Latest Posts ===
Contributors: paulmac
Tags: posts, excerpts, lastest, widget, sidebar
Requires at least: 2.3
Tested up to: 2.7.1
Stable tag: 1.2

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
The plugin page allows you to change the following settings: Title, Number of Posts, Excerpt Size, Post Offset, Read More Text, Allow HTML in Excerpt, Show Comments and Number of Comments.

1. Title: Title of the Sidebar widget.
1. Number of Posts: Number of posts to show in the Sidebar.
1. Excerpt Size: Number of words to display in the excerpt for the post.
1. Post Offset: The number of posts to skip before displaying the list in the Sidebar. Numbering starts from 0. To ignore the latest post, set the offset to 1.
1. Read More Text: Text display at the end of the excerpt. Defaults to "Read More".
1. Allow HTML in Excerpt: If left unticked, all HTML tags will be stripped, leaving only plain text. Enabling this option will leave in the HTML.
1. Show Comments: Should the widget also display your latest comments.
1. Number of Comments: Number of comments to show in the Sidebar.

= What if I don't want to show the excerpt? =
Change the Excerpt Size to 0 and no excerpt will be inserted.

= How do I change the CSS style of the plugin? =
The following CSS classes are used in the plugin:

* Titles: h3.pmc-h3
* Link: a.pmc-link
* Excerpt: span.pmc-excerpt

Add the class names to your theme's style.css with your custom css.

= I'm using an old version of WordPress, will this plugin work? =
This plugin uses the Widget API built into WordPress since version 2.3. Therefore, this plugin will not work on versions older than 2.3. If you are using a version of WordPress prior to 2.3, then I suggest that you upgrade to the latest version.

= Can I re-use your code in my own plugin? =
If you want to, then yes. This plugin is released under the GPL. You are free to modify it, change it, re-distribute it as you want.

= What other functionality are you thinking of adding? =
I'm looking into adding a list of latest uploads to the plugin.

== Thanks ==

Thanks goes to Simon who suggested that I add the Latest Comments functionality. If you would like to see anything else added to the list, please do not hesitate to leave a comment on the plugin homepage. Thanks to Juno for suggesting the HTML functionality and the ability to change the Read More text.
