<?php
// don't call file directly
if ( !defined( 'ABSPATH' ) )
    exit;

/*
 * Main plugin class 
 */
class PWP_Photoroulette {

    public function __construct() {
        // Loads scripts and styles
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );
        add_action( 'plugins_loaded', array( $this, 'includes' ), 1 );
    }

    /*
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // styles
        wp_enqueue_style('thickbox');
        wp_enqueue_style( 'pwppr-styles',     PWPPR_HOME_URL . 'css/style.css',      array(), PWPPR_VER );
        // scripts
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script( 'pwppr-scripts',   PWPPR_HOME_URL . 'js/scripts.js',  array('jquery'), PWPPR_VER, true );
    }

    /*
     * Enqueue scripts and styles for admin area
     */
    public function enqueue_admin_scripts() {
        // styles
        wp_enqueue_style( 'pwpph-styles-adm', PWPPR_HOME_URL . 'css/style-admin.css', array(), PWPPR_VER );
        // scripts
        wp_enqueue_script( 'pwppr-scripts',   PWPPR_HOME_URL . 'js/scripts-admin.js',  array('jquery','media-upload','thickbox'), PWPPR_VER, true );
    }

    /*
     * Include stuff
     */
    public function includes(){
        require_once( PWPPR_HOME_DIR . 'inc/functions.php' );
    }

}
