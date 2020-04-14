<?php
/*
Plugin Name: ConfigWise AR Module
Plugin URI: https://github.com/ConfigWise/ARModuleWooCommerce
Description: Extends WooCommerce with AR ConfigWise Platform integration.
Version: 1.0.0
Author: ConfigWise
Author URI: https://configwise.io/
Text Domain: configwise-arm-
Domain Path: /lang
Copyright: © 2020 ConfigWise
License: BSD 2-Clause
License URI: https://github.com/ConfigWise/ARModuleWooCommerce/blob/master/LICENSE
*/



add_action( 'plugins_loaded', 'configwise_arm_init', 0 );

function configwise_arm_init() {

	if ( class_exists( 'WooCommerce' ) ) {
		// code that requires WooCommerce

		include_once( 'configwise_arm.php' );

		new configwise_arm();

		if ( is_admin() ) {
			include_once( 'configwise_arm_admin.php' );
			new configwise_arm_admin();
		}
	}
}