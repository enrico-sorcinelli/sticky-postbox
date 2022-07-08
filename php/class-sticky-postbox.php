<?php
/**
 * Plugin base class.
 *
 * @package sticky-postbox
 * @author Enrico Sorcinelli
 */

// Check running WordPress instance.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'HTTP/1.1 404 Not Found' );
	exit();
}

/**
 * Plugin base class.
 */
class Sticky_Postbox {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * For use with minified libraries.
	 *
	 * @var string
	 */
	private $assets_suffix;

	/**
	 * Instance settings.
	 *
	 * @var array
	 */
	private $settings = false;

	/**
	 * Class constructor.
	 *
	 * @param array $args {
	 *     Argument list.
	 *     @type boolean $debug Debug mode.
	 * }
	 */
	public function __construct( $args = array() ) {

		$this->settings = wp_parse_args(
			$args,
			array(
				'debug' => false,
			)
		);

		// Load plugin text domain.
		load_plugin_textdomain( 'sticky-postbox', false, dirname( plugin_basename( __FILE__ ) ) . '/../languages/' );

		// Admin init.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Get the singleton instance of this class.
	 *
	 * @param array $args Constructor arguments list.
	 *
	 * @return object
	 */
	public static function get_instance( $args = array() ) {
		if ( ! ( self::$instance instanceof self ) ) {
			self::$instance = new self( $args );
		}
		return self::$instance;
	}

	/**
	 * Admin init hook.
	 *
	 * @return void
	 */
	public function admin_init() {

		// Use minified libraries if SCRIPT_DEBUG is turned off.
		$this->assets_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// AJAX action for user sticky settings.
		add_action( 'wp_ajax_sticky_postbox_sticky_postboxes', array( $this, 'ajax_sticky_postboxes' ) );

		// Add CSS and JS to right pages.
		foreach ( array( 'index.php', 'post.php', 'post-new.php' ) as $page ) {
			add_action( 'admin_print_styles-' . $page, array( $this, 'load_css' ), 10, 0 );
			add_action( 'admin_print_scripts-' . $page, array( $this, 'load_javascript' ), 10, 0 );
		}
	}

	/**
	 * Load CSS files.
	 *
	 * @return void
	 */
	public function load_css() {
		wp_enqueue_style(
			'sticky-postbox-css',
			STICKY_POSTBOX_BASEURL . '/assets/css/admin' . $this->assets_suffix . '.css',
			array(),
			STICKY_POSTBOX_VERSION
		);
	}

	/**
	 * Load JavaScript files.
	 *
	 * @return void
	 */
	public function load_javascript() {
		global $pagenow;

		list( $current_post, $post_type ) = self::get_current_post_and_post_type();

		// Sticky postboxes handling.
		if ( preg_match( '/^(index|post(-new)?)\.php$/', $pagenow ) ) {
			$page             = preg_match( '/^index.php$/', $pagenow ) ? 'dashboard' : $post_type;
			$sticky_postboxes = get_user_option( 'sticky_postbox_sticky_postboxes_' . $page );
		}

		if ( empty( $sticky_postboxes ) ) {
			$sticky_postboxes = array();
		}

		wp_enqueue_script(
			'sticky-postbox-admin-js',
			STICKY_POSTBOX_BASEURL . '/assets/js/admin' . $this->assets_suffix . '.js',
			array(),
			STICKY_POSTBOX_VERSION,
			false
		);

		// Localization.
		wp_localize_script(
			'sticky-postbox-admin-js',
			'sticky_postbox_i18n',
			array(
				'nonces'           => array(
					'sticky_postboxes' => wp_create_nonce( 'sticky_postbox_sticky_postboxes' ),
				),
				'sticky_postboxes' => $sticky_postboxes,
				'msgs'             => array(),
			)
		);
	}

	/**
	 * AJAX sticky postboxes handle.
	 */
	public function ajax_sticky_postboxes() {

		// Check nonce code.
		check_ajax_referer( 'sticky_postbox_sticky_postboxes', '_ajax_nonce_sticky_postbox_sticky_postboxes' );

		$args = wp_parse_args(
			$_REQUEST,
			array(
				'sticky' => array(),
				'page'   => '',
			)
		);

		if ( sanitize_key( $args['page'] ) !== $args['page'] ) {
			wp_die( 0 );
		}

		if ( ! $user = wp_get_current_user() ) {
			wp_die( -1 );
		}

		// Update status.
		if ( is_array( $args['sticky'] ) ) {
			$ret = update_user_option( $user->ID, 'sticky_postbox_sticky_postboxes_' . $args['page'], $args['sticky'], true );
			$this->log( $user->ID, $args, $ret );
		}
		wp_die( 1 );
	}

	/**
	 * Try to return current post and post_type.
	 *
	 * @return array
	 */
	public static function get_current_post_and_post_type() {
		global $post;

		$current_post = $post;
		$post_type    = 'post';

		// Try to get current post if it isn't yet defined.
		if ( ! ( $current_post instanceof \WP_Post ) && ( isset( $_REQUEST['post'] ) || isset( $_REQUEST['post_ID'] ) ) ) {
			$current_post = get_post( isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : $_REQUEST['post_ID'] );
		}

		// Set post type.
		if ( ! empty( $current_post ) ) {
			$post_type = $current_post->post_type;
		}
		elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = $_REQUEST['post_type'];
		}

		return array( $current_post, $post_type );
	}

	/**
	 * Debugging helper.
	 */
	private function log() {
		if ( false === $this->settings['debug'] ) {
			return;
		}
		error_log( print_r( func_get_args(), true ) );
	}

	/**
	 * Plugin uninstall hook.
	 *
	 * @return void
	 */
	public static function plugin_uninstall() {
	}
}

