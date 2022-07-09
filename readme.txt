=== Sticky Postbox ===
Contributors: Enrico Sorcinelli
Tags: Administration, Dashboard, Options, Sticky, Post, Classic Editor
Requires at least: 4.4
Requires PHP: 5.2.4
Tested up to: 6.0
Stable tag: 1.1.0
License: GPLv2 or later

Add sticky feature to admin postboxes.

== Description ==

**Sticky Postbox** was created a few years ago as a POC to learn how  WordPress save/restore postboxes's availables preferences (closed, hidden, sortings, etc).
This very lightweight plugin add the _sticky_ feature to administration postboxes allowing them to be sticky at top right corner of the browser window.
Only one postbox can be sticky at once, so a new sticky postbox unstick the current one (if there is one).

== Basic Features ==

* Per-user settings.
* Multisite support.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/sticky-postbox` directory, or install the plugin through the WordPress _Plugins_ screen directly.
1. Activate the plugin through the _Plugins_ screen in WordPress.

== Usage ==

Once the plugin is installed and activated you can sticky postboxes using icon

== API ==

= Constants =

You can use define following constants in your _wp-config.php_ file.

`STICKY_POSTBOX_DEBUG`

Turn on debug messages.

`STICKY_POSTBOX_AUTOENABLE`

By default if the plugin has been activated, it starts automatically. 
Define to `false` if you want to init it manually, for example:

	// Activate the plugin once all plugin have been loaded.
	add_action( 'plugins_loaded', function() {
		\Sticky_Postbox::get_instance( array( 'debug' => WP_DEBUG ) );
	};

= Hooks =

Currently the plugin doesn't have actions or filters.

== Frequently Asked Questions ==

= Does it work with Gutenberg? =

This plugin is intended to work with Classic Editor and Dashboard postboxes.

= Does it work with multisite installation? =

Yes?

== Screenshots ==

1. Sticky postboxes in classic edit post.
2. Sticky postboxes in Dashboard.

== Changelog ==

For plugin changelog, please see [the Releases page on GitHub](https://github.com/enrico-sorcinelli/sticky-postbox/releases).

