=== WP Sermons ===
Contributors: cghweb
Tags: sermons, church, ministry, audio, video
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 0.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress Plugin for Sermon Archive - Organize and display your church sermons with ease.

== Description ==

WP Sermons is a comprehensive WordPress plugin designed to help churches and ministries organize, manage, and display their sermon archives. With custom post types, taxonomies, shortcodes, and REST API support, WP Sermons provides a complete solution for sermon management.

= Features =

* **Custom Post Type**: Dedicated sermon post type with full WordPress editor support
* **Taxonomies**: Organize sermons by Series, Speaker, and Topic
* **Shortcodes**: Easy-to-use shortcodes for displaying sermons anywhere
* **REST API**: Full REST API support for headless implementations
* **Settings Page**: Configurable options for archive display
* **Responsive Design**: Mobile-friendly default styling

= Shortcodes =

* `[sermons]` - Display a list of sermons
* `[sermon_series]` - Display sermon series
* `[recent_sermons]` - Display recent sermons

= Shortcode Parameters =

**[sermons] parameters:**
* `number` - Number of sermons to display (default: 10)
* `series` - Filter by series slug
* `speaker` - Filter by speaker slug
* `topic` - Filter by topic slug
* `orderby` - Sort by date, title, etc. (default: date)
* `order` - ASC or DESC (default: DESC)

Example: `[sermons number="5" series="romans" speaker="john-doe"]`

= REST API Endpoints =

* `/wp-json/wp-sermons/v1/sermons` - Get sermons
* `/wp-json/wp-sermons/v1/sermons/{id}` - Get single sermon
* `/wp-json/wp-sermons/v1/series` - Get sermon series
* `/wp-json/wp-sermons/v1/speakers` - Get speakers

== Installation ==

1. Upload the `wp-sermons` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to Sermons > Settings to configure the plugin
4. Start adding sermons through the Sermons menu

== Frequently Asked Questions ==

= How do I display sermons on my website? =

You can use the built-in shortcodes on any page or post. For example, use `[sermons]` to display a list of all sermons.

= Can I customize the appearance of the sermons? =

Yes! The plugin includes CSS classes that you can override in your theme's stylesheet. You can also create custom templates in your theme.

= Does this support audio and video? =

The plugin provides the structure for managing sermons. You can add audio/video embeds using the WordPress editor or third-party plugins.

= Is this plugin compatible with page builders? =

Yes! The shortcodes work with all major page builders including Elementor, Beaver Builder, and Divi.

== Screenshots ==

1. Sermon admin list view
2. Sermon edit screen
3. Settings page
4. Frontend sermon display
5. Sermon series display

== Changelog ==

= 0.1.0 =
* Initial release
* Custom post type for sermons
* Three taxonomies: Series, Speaker, Topic
* Shortcodes for displaying sermons
* REST API endpoints
* Admin settings page
* Responsive frontend styling

== Upgrade Notice ==

= 0.1.0 =
Initial release of WP Sermons plugin.

== Development ==

This plugin is actively maintained. For bug reports and feature requests, please visit our GitHub repository.

== Credits ==

Developed by CGH Web
