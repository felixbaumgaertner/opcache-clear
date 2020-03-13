<?php

/**
 * @link              felixbaumgaertner.de
 * @since             1.0.0
 * @package           opcache-clear
 *
 * @wordpress-plugin
 * Plugin Name:       OPcache Clear
 * Description:       A plugin that just adds an admin button for clearing the PHP OPcache if the server enabled it. That's it.
 * Version:           1.0.0
 * Author:            Felix Baumgaertner
 * Author URI:        felixbaumgaertner.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       opcache-clear
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

function run_opcc() {

	// detect if cache clearing is requested
	if ( isset($_GET['opcc']) ) {

		add_action( 'admin_notices', 'opcc_notice' );
		function opcc_notice() {
			// run opcache_reset and display success or error notice
			if ( opcache_reset() ) {
				?>
				<div class="notice notice-success">
					<p><?php _e( 'OPcache cleared successfully.', 'opcache-clear' ); ?></p>
				</div>
				<?php
			} else {
				?>
				<div class="notice notice-error">
					<p><?php _e( 'Error: OPcache could not be cleared.', 'opcache-clear' ); ?></p>
				</div>
				<?php
			}
		}
	}

	// adds button to admin bar for easy access cache clearing
	add_action( 'admin_bar_menu', 'add_toolbar_items', 96 );
	function add_toolbar_items( $admin_bar ) {
		if ( is_admin() && ini_get('opcache.enable') && opcache_get_status() ) {
			$admin_bar->add_menu(
				array(
					'id'    => 'opcc',
					'title' => 'Clear Cache',
					'href'  => '#',
					'meta'  => array(
						'title'		=> __( 'Clears the current OPcache.', 'opcache-clear' ),	// tooltip
						'onclick'	=> 'window.location.search += "&opcc=true";',	// functionality to reload site with $_GET parameter
					),
				)
			);
		}
	}
	
}
run_opcc();
