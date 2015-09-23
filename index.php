<?php
/*
Plugin Name: PWP-Photoroulette
Plugin URI: http://photoroulette.pwpcode.ru 
Description: Simple way to increase pageviews of your site.
Version: 1.1.0
Author: Polkan
Author URI: http://pwpcode.ru
*/
define('PWPPR_VER', '1.0.9');

// don't call file directly
if ( !defined( 'ABSPATH' ) ) 
    exit;

define('PWPPR_MAIN_FILE', plugin_basename ( __FILE__ ) );
define('PWPPR_HOME_DIR', plugin_dir_path ( __FILE__ ) );
define('PWPPR_HOME_URL', plugin_dir_url ( __FILE__ ) );

// localisation
load_plugin_textdomain( 'pwppr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

// classes
require_once( PWPPR_HOME_DIR . 'main.php' );
require_once( PWPPR_HOME_DIR . 'widget.php' );

$pwppl = new PWP_Photoroulette;
