<?php

if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

add_filter( 'dt_post_type_modules', function( $modules ){
    $modules["contacts_zume"] = [
        "name" => "Contacts - Zume Tile",
        "enabled" => true,
        "locked" => true,
        "prerequisites" => [ "contacts_base" ],
        "post_type" => "contacts",
        "description" => "Zume Tile Extension for Contacts"
    ];
    return $modules;
}, 40, 1 );

class Zume_Contact_Extension_Hook extends DT_Module_Base {

    private static $_instance = null;
    public $post_type = "trainings";
    public $module = "contacts_zume";

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        parent::__construct();
        if ( !self::check_enabled_and_prerequisites() ){
            return;
        }

        add_filter( 'dt_custom_fields_settings', [ $this, 'dt_custom_fields_settings' ], 999, 2 );
        add_filter( 'dt_details_additional_tiles', [ $this, 'dt_details_additional_tiles' ], 60, 2 );
        add_action( 'dt_details_additional_section', [ $this, 'dt_details_additional_section' ], 20, 2 );

    }

    public function dt_custom_fields_settings( $fields, $post_type ) {
        if ( 'contacts' === $post_type ) {
            $fields['zume_training_id'] = [
                'name' => "Zume User ID",
                'type' => 'text',
                'default' => '',
                'show_in_table' => false,
                'hidden' => true
            ];
            $fields['zume_foreign_key'] = [
                'name' => "Zume Foriegn Key",
                'type' => 'text',
                'default' => '',
                'show_in_table' => false,
                'hidden' => true
            ];
        }
        return $fields;
    }

    public function dt_details_additional_tiles( $tiles, $post_type = "" ){
        if ( "contacts" === $post_type ){
            $tiles["zume_contact_details"] = [ "label" => __( "Zume.Training Course", 'disciple_tools' ) ];
        }
        return $tiles;
    }

    public function dt_details_additional_section( $section ) {

        if ( 'zume_contact_details' === $section ) :

            global $post;
            $post_meta = Zume_Training_Extension::get_meta( $post->ID );

            // if no public key or group id
            if ( empty( $post_meta['zume_foreign_key'] ) && empty( $post_meta['zume_training_id'] ) ) {
                $this->display_foreign_key_for_linking();
                ?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery('#zume_contact_details').hide()
                    })
                </script>
                <?php
            }
            // if public key but no group id
            else if ( ! empty( $post_meta['zume_foreign_key'] ) && empty( $post_meta['zume_training_id'] ) ) {
                // get group id and add it
                $zume_training_id = $this->get_user_id_by_key( $post_meta['zume_foreign_key'] );
                if ( $zume_training_id ) {
                    update_post_meta( $post->ID, 'zume_training_id', $zume_training_id );
                    $this->display_zume_user( $zume_training_id );
                } else {
                    $this->display_foreign_key_for_linking();
                }
            }
           // if user id
            else if ( ! empty( $post_meta['zume_training_id'] ) ) {
                // then query by group id
                $this->display_zume_user( $post_meta['zume_training_id'] );
            }

        endif; // End test if section zume_training

    }

    public function get_user_id_by_key( $zume_foreign_key ) {
        global $wpdb;
        return $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_value = %s AND meta_key = 'zume_foreign_key' LIMIT 1", $zume_foreign_key ) );
    }

    public function display_foreign_key_for_linking() {
        global $post;
        // show link form
        $post_type = get_post_type();
        $post_settings = apply_filters( "dt_get_post_type_settings", [], $post_type );
        $dt_post = DT_Posts::get_post( $post_type, get_the_ID() );
        ?>

        <?php if ( get_post_meta( $post->ID, 'zume_foreign_key', true ) ) : ?>
            <p>This current key was not found in connection to a Zúme Training User. Check again.</p>
        <?php endif; ?>

        <?php render_field_for_display( 'zume_foreign_key', $post_settings["fields"], $dt_post ) ?>

        <script>
            jQuery(document).ready(function(){
                jQuery( document ).on( 'text-input-updated', function (e, newObject, id, val){
                    console.log(id)
                    if ( id === 'zume_foreign_key' ) {
                        location.reload()
                    }
                })
            })
        </script>
        <?php
    }

    public function display_zume_user( $zume_training_id ) {
        $record = $this->get_zume_user( $zume_training_id );

        if ( ! $record ) :
            $this->display_foreign_key_for_linking();

        else: // if zume key matches
            ?>
            <style>
                #zume-tabs li a { padding: 1rem 1rem; }
                .date-text {
                    font-size:.8em;
                }
            </style>


            <ul class="tabs" data-tabs id="zume-tabs">

                <li class="tabs-title is-active"><a href="#info" data-tabs-target="info"><i class="fi-info"></i></a></li>
                <?php if ( user_can( get_current_user_id(), 'manage_dt' ) ) : ?>
                    <li class="tabs-title"><a data-tabs-target="raw" href="#raw"><i class="fi-database"></i></a></li>
                <?php endif; ?>
            </ul>
            <script>
                jQuery(document).ready(function() {
                    jQuery('#zume-tabs').on('change.zf.tabs', function() {
                        console.log('test')
                        jQuery('.grid').masonry({
                            itemSelector: '.grid-item',
                            percentPosition: true
                        });
                    })
                })

            </script>

            <div class="tabs-content" data-tabs-content="zume-tabs">

                <!-- Info box -->
                <div class="tabs-panel is-active" id="info" style="min-height: 300px; vertical-align: top;">
                    <dl>
                        <?php if ( isset( $record['last_activity'] ) ) :
                            ?>
                            <dt>
                                <?php esc_html_e( 'Last Activity' ) ?>:
                            </dt>
                            <dd>
                                <?php echo empty( $record['last_activity'] ) ? '' : $this->time_elapsed_string( $record['last_activity'] )  ?><br>
                            </dd>
                        <?php endif; ?>

                        <?php if ( isset( $record['location_grid_meta']['label'] ) ) :
                            ?>
                            <dt>
                                <?php esc_html_e( 'User Provided Location' ) ?>:
                            </dt>
                            <dd>
                                <?php echo empty( $record['location_grid_meta']['label'] ) ? '' : esc_html( $record['location_grid_meta']['label'] ) ?>
                            </dd>
                        <?php endif; ?>

                        <?php if ( isset( $record['ip_location_grid_meta']['label'] ) ) :
                            ?>
                            <dt>
                                <?php esc_html_e( 'IP Address Location' ) ?>:
                            </dt>
                            <dd>
                                <?php echo empty( $record['ip_location_grid_meta']['label'] ) ? '' : esc_html( $record['ip_location_grid_meta']['label'] ) ?>
                            </dd>
                        <?php endif; ?>

                        <dt>Groups:</dt>
                        <?php foreach( $record as $key => $value ) {
                            if ( substr( $key, 0, 10 ) === 'zume_group' && $key !== $value['key'] ) {
                                ?>
                                <dd>
                                    - <?php echo esc_html( $value['group_name'] ) ?>
                                </dd>
                                <?php
                            }
                        }
                        ?>

                        <?php if ( isset( $record['zume_language'] ) ) :
                            ?>
                            <dt>
                                <?php esc_html_e( 'Language' ) ?>:
                            </dt>
                            <dd>
                                <?php echo empty( $record['zume_language'] ) ? '' : esc_html( $record['zume_language'] ) ?>
                            </dd>
                        <?php endif; ?>

                    </dl>

                </div>

                <!-- Sessions Tab -->
                <div class="tabs-panel" id="progress" style="min-height: 300px;vertical-align: top;">
                    <div class="grid-x">

                        <?php if ( isset( $record['zume_progress'] ) ) : $this->verify_progress_array( $record['zume_progress'] ) ?>
                            <div class="cell small-6">

                            </div>
                            <div class="cell small-6">
                                <div class="button-group small">
                                    <a class="button">H</a>
                                    <a class="alert button">O</a>
                                    <a class="warning button">S</a>
                                    <a class="success button hollow">T</a>
                                </div>
                            </div>


                        <?php else: ?>
                            <dt>PROGRESS<hr></dt>
                            <dd>No progress recorded. Likely they have not signed in since progress began being
                                tracked.</dd>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Raw Tab-->
                <?php if ( user_can( get_current_user_id(), 'manage_dt' ) ) : ?>
                    <div class="tabs-panel" id="raw" >
                        <div style="width:100%;height: 300px;overflow-y: scroll;overflow-x:hidden;">
                            <?php
                            if ( $record ) {
                                foreach ( $record as $key => $value ) {
                                    echo '<strong>' . esc_attr( $key ) . ': </strong>' . esc_attr( maybe_serialize( $value ) ) . '<br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif;  // end Raw Tab ?>
            </div>
        <div class="center"><button class="button clear small" id="unlink-zume-group">unlink</button></div>

        <?php endif; // end has group id
    }

    public function time_elapsed_string( $datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function verify_progress_array( &$args ) {
        $defaults = array(
            '1h' => '',
            '1o' => '',
            '1s' => '',
            '1t' => '',
            '2h' => '',
            '2o' => '',
            '2s' => '',
            '2t' => '',
            '3h' => '',
            '3o' => '',
            '3s' => '',
            '3t' => '',
            '4h' => '',
            '4o' => '',
            '4s' => '',
            '4t' => '',
            '5h' => '',
            '5o' => '',
            '5s' => '',
            '5t' => '',
            '6h' => '',
            '6o' => '',
            '6s' => '',
            '6t' => '',
            '7h' => '',
            '7o' => '',
            '7s' => '',
            '7t' => '',
            '8h' => '',
            '8o' => '',
            '8s' => '',
            '8t' => '',
            '9h' => '',
            '9o' => '',
            '9s' => '',
            '9t' => '',
            '10h' => '',
            '10o' => '',
            '10s' => '',
            '10t' => '',
            '11h' => '',
            '11o' => '',
            '11s' => '',
            '11t' => '',
            '12h' => '',
            '12o' => '',
            '12s' => '',
            '12t' => '',
            '13h' => '',
            '13o' => '',
            '13s' => '',
            '13t' => '',
            '14h' => '',
            '14o' => '',
            '14s' => '',
            '14t' => '',
            '15h' => '',
            '15o' => '',
            '15s' => '',
            '15t' => '',
            '16h' => '',
            '16o' => '',
            '16s' => '',
            '16t' => '',
            '17h' => '',
            '17o' => '',
            '17s' => '',
            '17t' => '',
            '18h' => '',
            '18o' => '',
            '18s' => '',
            '18t' => '',
            '19h' => '',
            '19o' => '',
            '19s' => '',
            '19t' => '',
            '20h' => '',
            '20o' => '',
            '20s' => '',
            '20t' => '',
            '21h' => '',
            '21o' => '',
            '21s' => '',
            '21t' => '',
            '22h' => '',
            '22o' => '',
            '22s' => '',
            '22t' => '',
            '23h' => '',
            '23o' => '',
            '23s' => '',
            '23t' => '',
            '24h' => '',
            '24o' => '',
            '24s' => '',
            '24t' => '',
            '25h' => '',
            '25o' => '',
            '25s' => '',
            '25t' => '',
            '26h' => '',
            '26o' => '',
            '26s' => '',
            '26t' => '',
            '27h' => '',
            '27o' => '',
            '27s' => '',
            '27t' => '',
            '28h' => '',
            '28o' => '',
            '28s' => '',
            '28t' => '',
            '29h' => '',
            '29o' => '',
            '29s' => '',
            '29t' => '',
            '30h' => '',
            '30o' => '',
            '30s' => '',
            '30t' => '',
            '31h' => '',
            '31o' => '',
            '31s' => '',
            '31t' => '',
            '32h' => '',
            '32o' => '',
            '32s' => '',
            '32t' => '',
        );

        return wp_parse_args( $args, $defaults );
    }

    /**
     * @param $zume_training_id
     * @return bool|array
     */
    public function get_zume_user( $zume_training_id ) {
       $usermeta = zume_get_user_meta( $zume_training_id );

        if ( $usermeta ) {
            return $usermeta;
        } else {
            return [];
        }
    }

}
Zume_Contact_Extension_Hook::instance();