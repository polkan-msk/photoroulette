<?php
/*
Plugin Name: PWP-Photoroulette
Plugin URI: 
Description: Simple way to increase pageviews of your site. Plugin adds funny interactive widget.
Version: 1.0.8
Author: Polkan
Author URI: http://woo-apishops.ru
*/
define('PWPPR_VER', '1.0.8');
define('PWPPR_DBG', false);

// don't call file directly
if ( !defined( 'ABSPATH' ) ) 
    exit;

define('PWPPR_MAIN_FILE', plugin_basename ( __FILE__ ) );
define('PWPPR_HOME_DIR', plugin_dir_path ( __FILE__ ) );
define('PWPPR_HOME_URL', plugin_dir_url ( __FILE__ ) );

/* Initialise localisation outside of classes */
$rez= load_plugin_textdomain( 'pwppr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

require_once( PWPPR_HOME_DIR . 'main.php' );
require_once( PWPPR_HOME_DIR . 'widget.php' );

$pwppl = new PWP_Photoroulette;
