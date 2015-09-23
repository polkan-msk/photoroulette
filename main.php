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

        // Ajax actions
        add_action( 'wp_ajax_pwppr_actions', array($this, 'ajax_actions') );
        // Ajax for no_logged_in users
        add_action( 'wp_ajax_nopriv_pwppr_actions', array($this, 'ajax_actions') );
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

        // Localize and vars for javascripts
        wp_localize_script( 'pwppr-scripts', 'pwppr', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'pwppr_nonce' ),
            'errorMessage' => __( 'error: Something went wrong', 'pwppr' ),
        ) );
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


    /*
     * Ajax handlers for adding to cart, removing from cart, recalculate cart, send cart
     */
    public function ajax_actions() {
        check_ajax_referer( 'pwppr_nonce', 'nonce' );

        if ( !isset($_POST['flag']) )
            wp_send_json_error( __('error: Empty ajax flag', 'pwpcs') );
    
        if ($_POST['flag'] == "pwpprPostRefreshFlag"){

            $wdgt_num = $_POST['number'];

            $pwppr_ajax = new PWP_Photoroulette_Widget;
            $options = get_option($pwppr_ajax->option_name);
            $options = $options[ $wdgt_num ];

            if ( ! empty($_POST['numPosts']) )
                $options['items2show'] = $_POST['numPosts'];

            $posts = get_pwppr_posts ( $options );

            if (!empty($posts)) wp_send_json_success( $posts );
            else wp_send_json_error( __('error: No posts found', 'pwpcs') );
        }
    }


    /*
     * TODO Shortcode support goes here
     */

}
