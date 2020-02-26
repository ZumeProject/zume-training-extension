<?php
/**
 * Plugin Name: Zúme - Disciple Tools Training Extension
 * Plugin URI: https://github.com/DiscipleTools/disciple-tools-one-page-extension
 * Description: One page extension of Disciple Tools Training plugin to include Zúme specific training data.
 * Version:  0.1.0
 * Author URI: https://github.com/DiscipleTools
 * GitHub Plugin URI: https://github.com/DiscipleTools/disciple-tools-one-page-extension
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.3
 *
 * @package Disciple_Tools
 * @link    https://github.com/DiscipleTools
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * PLEASE, RENAME CLASS AND FUNCTION NAMES BEFORE USING TEMPLATE
 * Rename these three strings:
 *      Zume Training Extension
 *      Zume_DT_Training
 *      zume_dt_training
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

add_action( 'after_setup_theme', function (){
    $required_dt_theme_version = '0.22.0';
    $wp_theme = wp_get_theme();
    $version = $wp_theme->version;
    /*
     * Check if the Disciple.Tools theme is loaded and is the latest required version
     */
    $is_theme_dt = strpos( $wp_theme->get_template(), "disciple-tools-theme" ) !== false || $wp_theme->name === "Disciple Tools";
    if ( !$is_theme_dt || version_compare( $version, $required_dt_theme_version, "<" ) ) {
        if ( ! is_multisite() ) {
            add_action('admin_notices', function () {
                ?>
                <div class="notice notice-error notice-zume_dt_training is-dismissible" data-notice="zume_dt_training">Disciple
                    Tools Theme not active or not latest version for Zume Training Extension plugin.
                </div><?php
            });
        }

        return false;
    }
    /**
     * Load useful function from the theme
     */
    if ( !defined( 'DT_FUNCTIONS_READY' ) ){
        require_once get_template_directory() . '/dt-core/global-functions.php';
    }
    /*
     * Don't load the plugin on every rest request. Only those with the 'sample' namespace
     */
    $is_rest = dt_is_rest();
    if ( !$is_rest || strpos( dt_get_url_path(), 'trainings' ) != false ){
        return Zume_DT_Training::instance();
    }
    return false;

}, 150 );


/**
 * Class Zume_DT_Training
 */
class Zume_DT_Training {

    public $token = 'zume_dt_training';
    public $title = 'Zúme Training Extension';
    public $permissions = 'manage_dt';

    /**  Singleton */
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Constructor function.
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {
        require_once ('tile.php' );
        require_once ('rest-api.php' );

        add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 999 );

        if ( is_admin() ) {
            add_action( "admin_menu", [ $this, "register_menu" ] );
        }
    } // End __construct()

    public function scripts() {
        if ( function_exists( 'dt_get_url_path') ) {

            $url_path = dt_get_url_path();

            // trainings post type

            if ( strpos( $url_path, 'trainings' ) !== false ){
                $post_array = [];
                if ( is_single() ) {
                    $post_array = DT_Posts::get_post( get_post_type(), get_the_ID() );
                }

                wp_enqueue_script( 'zume-training', plugin_dir_url(__FILE__) . '/zume-training.js', array( 'jquery' ), filemtime( plugin_dir_path(__FILE__) . '/zume-training.js' ), true );
                wp_localize_script(
                    "zume-training", "zumeTraining", array(
                        "training" => $post_array,
                        "translations" => array(
                            "cancel" => esc_html__( 'Cancel', 'zume' ),
                            "current:" => esc_html__( 'Current Step:', 'zume' ),
                            "pagination" => esc_html__( 'Cancel', 'zume' ),
                            "finish" => esc_html__( 'Finish', 'zume' ),
                            "next" => esc_html__( 'Next', 'zume' ),
                            "previous" => esc_html__( 'Previous', 'zume' ),
                            "loading" => esc_html__( 'Loading...', 'zume' ),
                        )
                    )
                );
            }

        }
    }


    /**
     * Loads the subnav page
     * @since 0.1
     */
    public function register_menu() {
        add_menu_page( 'Extensions (DT)', 'Extensions (DT)', $this->permissions, 'dt_extensions', [ $this, 'extensions_menu' ], 'dashicons-admin-generic', 59 );
        add_submenu_page( 'dt_extensions', $this->title, $this->title, $this->permissions, $this->token, [ $this, 'content' ] );
    }

    /**
     * Menu stub. Replaced when Disciple Tools Theme fully loads.
     */
    public function extensions_menu() {}

    /**
     * Builds page contents
     * @since 0.1
     */
    public function content() {

        if ( !current_user_can( $this->permissions ) ) { // manage dt is a permission that is specific to Disciple Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        $this->process_postback();

        ?>
        <div class="wrap">
            <h2><?php echo esc_html( $this->title ) ?></h2>
            <div class="wrap">
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <!-- Main Column -->

                            <?php $this->main_column(); ?>

                            <!-- End Main Column -->
                        </div><!-- end post-body-content -->
                        <div id="postbox-container-1" class="postbox-container">
                            <!-- Right Column -->

                            <?php $this->right_column(); ?>

                            <!-- End Right Column -->
                        </div><!-- postbox-container 1 -->
                        <div id="postbox-container-2" class="postbox-container">
                        </div><!-- postbox-container 2 -->
                    </div><!-- post-body meta box container -->
                </div><!--poststuff end -->
            </div><!-- wrap end -->
        </div><!-- End wrap -->

        <?php
    }

    public function main_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr>
                <th>Header</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Content
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    public function right_column() {
        ?>
        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr><th>Resync</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Resync Zúme Training Groups to Global Network Training Groups<br><br>
                    <form method="post">
                        <?php wp_nonce_field() ?>
                        <button type="submit" class="button large" name="resync" value="true">Resync</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->
        <?php
    }

    public function process_postback() {
        // check for valid nonce
        if ( ! ( isset( $_POST['_wpnonce'] )
            && isset( $_POST['_wp_http_referer'] )
            && '/wp-admin/admin.php?page=zume_dt_training' === sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) )
            && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) )
        {
            return false;
        }

        // check for resync request
        if ( isset( $_POST['resync'] ) ) {
            dt_write_log('resync');
            $this->resync_zume_and_global();
        }

        dt_write_log($_POST);
        return true;
    }

    public function resync_zume_and_global() {
        global $wpdb;
        // get list of groups in training
        $groups_in_zt = $wpdb->get_col("SELECT meta_key FROM $wpdb->usermeta WHERE meta_key LIKE 'zume_group%'" );

        // get list of groups in global
        $trainings_in_global = $wpdb->get_col( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'zume_group_id'" );
        $trainings = [];
        foreach( $trainings_in_global as $value ) {
            $trainings[$value] = true;
        }


        // compare list
        foreach( $groups_in_zt as $item ) {
            if ( !isset( $trainings[$item] ) ) {
                // @todo add function to sync zt group to create a new global training
                dt_write_log($item . ': no');
            }
        }

        // loop create trainings for any missing
    }

    /**
     * Method that runs only when the plugin is activated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function activation() {}

    /**
     * Method that runs only when the plugin is deactivated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function deactivation() {}

    /**
     * Magic method to output a string if trying to use the object as a string.
     *
     * @since  0.1
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->token;
    }

    /**
     * Magic method to keep the object from being cloned.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, esc_html( 'Whoah, partner!' ), '0.1' );
    }

    /**
     * Magic method to keep the object from being unserialized.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, esc_html( 'Whoah, partner!' ), '0.1' );
    }

    /**
     * Magic method to prevent a fatal error when calling a method that doesn't exist.
     *
     * @param string $method
     * @param array $args
     *
     * @return null
     * @since  0.1
     * @access public
     */
    public function __call( $method = '', $args = array() ) {
        // @codingStandardsIgnoreLine
        _doing_it_wrong( __FUNCTION__, esc_html('Whoah, partner!'), '0.1' );
        unset( $method, $args );
        return null;
    }

    public static function get_meta( $post_id ) {
        return array_map( function ( $a ) { return maybe_unserialize( $a[0] );
        }, get_post_meta( $post_id ) );
    }
}

// Register activation hook.
register_activation_hook( __FILE__, [ 'Zume_DT_Training', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'Zume_DT_Training', 'deactivation' ] );