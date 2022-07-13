=== Sticky Postbox ===
Contributors: Enrico Sorcinelli
Tags: Administration, Dashboard, Options, Sticky, Post, Classic Editor, meta box
Requires at least: 4.4
Requires PHP: 5.2.4
Tested up to: 6.0
Stable tag: 1.3.0
License: GPLv2 or later

Add sticky feature to administration meta boxes.

== Description ==

**Sticky Postbox** is a piece of code written few years ago as exercise to learn how WordPress handles administration meta boxes's availables statuses (closed, hidden and sortings).

This very lightweight plugin adds the _sticky_ feature to administration meta boxes allowing them to be sticky at top right corner of the browser window.

Only one meta box can be sticky at once, so a new sticky meta box unstick the current one (if there is one).

== Basic Features ==

* Per-user settings.
* Multisite support.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/sticky-postbox` directory, or install the plugin through the WordPress _Plugins_ screen directly.
1. Activate the plugin through the _Plugins_ screen in WordPress.

== Usage ==

Once the plugin is installed and activated you can sticky administration meta boxes using sticky icon (it appears on the top right corner of each box).

== API ==

= Constants =

You can use define following constants in your _wp-config.php_ file.

**`STICKY_POSTBOX_DEBUG`**

Turn on debug messages (also `WP_DEBUG` has to be `true`).
Default to `false`.

**`STICKY_POSTBOX_GLOBAL_OPTIONS`**

Shares user's sticky meta boxes settings across all his sites. For example, if you sticky *Publish* box in editing Pages in a specific site, it will be sticky on all Pages of all sites).
Default to `false`.

**`STICKY_POSTBOX_AUTOENABLE`**

By default if the plugin has been activated, it starts automatically. 
Define to `false` if you want to init it manually, for example:

	// Activate manually the plugin once all plugin have been loaded.
	add_action( 'plugins_loaded', function() {
		\Sticky_Postbox::get_instance( 
			array(
				'debug'          => WP_DEBUG,
				'global_options' => false,
			)
		);
	} );

= Hooks =

Currently the plugin doesn't have actions or filters.

== Frequently Asked Questions ==

= Does it work with Gutenberg? =

This plugin is intended to work with Classic Editor and Dashboards meta boxes.

= Does it work with multisite installation? =

Yes?

== Screenshots ==

1. Sticky meta box in classic edit post.
2. Sticky meta box in Dashboard.

== Changelog ==

For plugin changelog, please see [the Releases page on GitHub](https://github.com/enrico-sorcinelli/sticky-postbox/releases).

