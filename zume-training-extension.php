<?php
/**
 * Plugin Name: Zúme - Training Extension for Disciple Tools
 * Plugin URI: https://github.com/DiscipleTools/disciple-tools-one-page-extension
 * Description: One page extension of Disciple Tools Training plugin to include Zúme specific training data.
 * Version:  0.2
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


if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

add_action( 'after_setup_theme', function (){
    $required_dt_theme_version = '1.0';
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
                <div class="notice notice-error notice-zume_training_extension is-dismissible" data-notice="zume_training_extension">Disciple
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
        return Zume_Training_Extension::instance();
    }
    return false;

}, 500 );


/**
 * Class Zume_Training_Extension
 */
class Zume_Training_Extension {

    public $token = 'zume_training_extension';
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
        require_once ('cron.php' );

        require_once ('contact-tile.php' );
        require_once ('training-tile.php' );

        if ( function_exists( 'dt_get_url_path') ) {
            $url_path = dt_get_url_path();

            // load only on contact details page
            if ( strpos( $url_path, 'contacts' ) !== false  ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 999 );
            }

            // load only on training details page
            if ( strpos( $url_path, 'trainings' ) !== false ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 999 );
            }

        }

        if ( is_admin() ) {
            add_action( "admin_menu", [ $this, "register_menu" ] );
        }


    } // End __construct()


    public function scripts() {
        wp_enqueue_script( 'zume-training', plugin_dir_url(__FILE__) . 'zume-training.js', array( 'jquery' ), filemtime( plugin_dir_path(__FILE__) . '/zume-training.js' ), true );
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
            <tr><th>Transfer Zume Groups to Global</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <form method="post">
                        <?php wp_nonce_field() ?>
                        <button type="submit" class="button large" name="resync" value="true">Transfer Zume Groups to Global</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->

        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr><th>Close Inactive Zúme.Training Groups in Global</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <form method="post">
                        <?php wp_nonce_field() ?>
                        <button type="submit" class="button large" name="close_inactive" value="true">Close Inactive Zùme Training Groups</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
        <br>
        <!-- End Box -->

        <!-- Box -->
        <table class="widefat striped">
            <thead>
            <tr><th>Compare and Update Zume Groups with Global Trainings</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <form method="post">
                        <?php wp_nonce_field() ?>
                        <button type="submit" class="button large" name="compare_groups" value="true">Compare and Update Zume Groups with Global Trainings</button>
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
            && '/wp-admin/admin.php?page=zume_training_extension' === sanitize_text_field( wp_unslash( $_POST['_wp_http_referer'] ) )
            && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ) ) ) )
        {
            return false;
        }

        // check for resync request
        if ( isset( $_POST['resync'] ) ) {
            dt_write_log('resync');
            $this->resync_zume_and_global();
        }

        // check for resync request
        if ( isset( $_POST['close_inactive'] ) ) {
            dt_write_log('close_inactive');
            $this->close_inactive_trainings();
        }

        // check for resync request
        if ( isset( $_POST['compare_groups'] ) ) {
            dt_write_log('compare_groups');
            $this->compare_groups();
        }

        return true;
    }

    public function query_get_zume_group_ids_in_global() {
        global $wpdb;
        return $wpdb->get_col( "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'zume_group_id'" );
    }

    public function query_get_group_ids_in_zume_training() {
        global $wpdb;
        return $wpdb->get_col("SELECT meta_key FROM $wpdb->usermeta WHERE meta_key LIKE 'zume_group%'" );
    }

    public function compare_groups() {
        global $wpdb;
        $trainings_in_global = $wpdb->get_results( "SELECT post_id, meta_value as zume_group_id FROM $wpdb->postmeta WHERE meta_key = 'zume_group_id'", ARRAY_A );

        $count = [
            "total" => 0,
            "check_needed" => 0,
            "checked" => 0,
            "checked_list" => [], //get_transient( __METHOD__ ),
        ];
        $count['total'] = count($trainings_in_global);
        if ( $count['checked_list'] === false ) {
            $count['checked_list'] = [];
        }

        $obj = new Zume_Training_Extension_Hook();

        // compare list
        $i = 0;
        foreach( $trainings_in_global as $row ) {
            if ( ! isset( $count['checked_list'][$row['zume_group_id']] ) ) {
                if ( $i > 50 ) { // set limit on number of records per sync. keep from timing out.
                    $count['check_needed']++;
                    continue;
                }

                $dt_post = DT_Posts::get_post( 'trainings', $row['post_id'], false, false );
                $obj->get_zume_group( $row['zume_group_id'], $dt_post );

                $count['checked']++;
                $count['checked_list'][$row['zume_group_id']] = true;
                $i++;
            }
        }
//        set_transient( __METHOD__, $count['checked_list'], 3600 );

        dt_write_log($count);

        ?>
        <div class="notice notice-success is-dismissible">
            <p>Total Groups: <?php echo esc_html( $count['total'] ) ?> | Checks Still Needed: <?php echo esc_html( $count['check_needed'] ) ?> | Checks Completed: <?php echo esc_html( $count['checked'] ) ?></p>
        </div>
        <?php
    }

    public function resync_zume_and_global() {
        global $wpdb;
        // get list of groups in training
        $groups_in_zt = $this->query_get_group_ids_in_zume_training();

        // get list of groups in global
        $trainings_in_global = $this->query_get_zume_group_ids_in_global();
        $trainings = [];
        foreach( $trainings_in_global as $value ) {
            $trainings[$value] = true;
        }

        $count = [
            "total" => 0,
            "transfer_needed" => 0,
            "transferred" => 0,
            "transfer_names" => [],
            'results' => []
        ];
        $count['total'] = count($groups_in_zt);

        // compare list
        $i = 0;
        foreach( $groups_in_zt as $item ) {
            if ( !isset( $trainings[$item] ) ) {
                if ( $i > 100 ) { // set limit on number of records per sync. keep from timing out.
                    $count['transfer_needed']++;
                    continue;
                }

                $group = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = %s", $item ) );
                $group = maybe_unserialize( $group );

                // create training
                $fields = [
                    "title" => $group['group_name'],
                    "zume_group_id" => $group['key'],
                    "zume_public_key" => $group['public_key'],
                    "member_count" => $group['members'],
                    "leader_count" => 1,
                    "status" => "in_progress",
                ];

                if ( get_user_meta( $group['owner'], 'wp_3_corresponds_to_contact', true ) ) {
                    $fields['assigned_to'] = $group['owner'];
                }


                $count['results'][] = DT_Posts::create_post( 'trainings', $fields, true, false );

                $count['transferred']++;
                $count['transfer_names'][] = $group['group_name'];
                $i++;
            }
        }

        ?>
        <div class="notice notice-success is-dismissible">
            <p>Total Groups: <?php echo esc_html( $count['total'] ) ?> | Transfers Still Needed: <?php echo esc_html( $count['transfer_needed'] ) ?> | Transfers Completed: <?php echo esc_html( $count['transferred'] ) ?></p>
        </div>
        <?php
    }

    public function close_inactive_trainings() {
        global $wpdb;
        $trainings_in_global = $this->query_get_zume_group_ids_in_global();

        $count = [
            "total" => 0,
            "check_needed" => 0,
            "checked" => 0,
            "checked_list" => get_transient( __METHOD__ ),
        ];
        $count['total'] = count($trainings_in_global);
        if ( $count['checked_list'] === false ) {
            $count['checked_list'] = [];
        }

        // compare list
        $i = 0;
        foreach( $trainings_in_global as $zume_group_id ) {
            if ( ! isset( $count['checked_list'][$zume_group_id] ) ) {
                if ( $i > 500 ) { // set limit on number of records per sync. keep from timing out.
                    $count['check_needed']++;
                    continue;
                }

                $group = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = %s LIMIT 1", $zume_group_id ) );
                $group = maybe_unserialize( $group );

                $today = time();
                if ( empty( $group['last_modified_date'] ) ) {
                    $last_modified_date = '915148800';
                }
                else if ( is_int( $group['last_modified_date'] ) ) {
                    $last_modified_date = (string) $group['last_modified_date'];
                } else {
                    $last_modified_date = strtotime($group['last_modified_date']);
                }

                $difference = $today - $last_modified_date;
                $days = $difference/60/1000;

                if ( $days > 90 ) {

                    $completed = 0;
                    if ( $group['session_1'] ) {
                        $completed++;
                    }
                    if ( $group['session_2'] ) {
                        $completed++;
                    }
                    if ( $group['session_3'] ) {
                        $completed++;
                    }
                    if ( $group['session_4'] ) {
                        $completed++;
                    }
                    if ( $group['session_5'] ) {
                        $completed++;
                    }
                    if ( $group['session_6'] ) {
                        $completed++;
                    }
                    if ( $group['session_7'] ) {
                        $completed++;
                    }
                    if ( $group['session_8'] ) {
                        $completed++;
                    }
                    if ( $group['session_9'] ) {
                        $completed++;
                    }
                    if ( $group['session_10'] ) {
                        $completed++;
                    }

//                    dt_write_log( $zume_group_id);

                    $post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = %s and meta_key = 'zume_group_id' LIMIT 1", $zume_group_id ) );
                    if ( $completed >= 5 ) {
                        /* test for completed */
                        update_post_meta( $post_id, 'status', 'complete' );
                    } else {
                        /* test for closed */
                        update_post_meta( $post_id, 'status', 'closed' );
                    }
                }

                $count['checked']++;
                $count['checked_list'][$zume_group_id] = true;
                $i++;
            }
        }
        set_transient( __METHOD__, $count['checked_list'], 3600 );

        dt_write_log('Close');
//        dt_write_log($count);
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Total Groups: <?php echo esc_html( $count['total'] ) ?> | Checks Still Needed: <?php echo esc_html( $count['check_needed'] ) ?> | Checks Completed: <?php echo esc_html( $count['checked'] ) ?></p>
        </div>
        <?php
    }

    public function is_valid_timeStamp($timestamp)
    {
        return ((string) (int) $timestamp === $timestamp)
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
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
register_activation_hook( __FILE__, [ 'Zume_Training_Extension', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'Zume_Training_Extension', 'deactivation' ] );

if ( ! function_exists( 'zume_get_user_meta' ) ) {
    function zume_get_user_meta( $user_id = null ) {
        if ( ! is_user_logged_in() ) {
            return [];
        }
        if ( is_null( $user_id ) ) {
            $user_id = get_current_user_id();
        }
        return array_map( function ( $a ) { return maybe_unserialize( $a[0] );
        }, get_user_meta( $user_id ) );
    }
}
