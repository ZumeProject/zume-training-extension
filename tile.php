<?php

class DT_Zume_Hooks_Groups {

    public function group_detail_box( $section ) {
        global $post;

        if ( $section == 'zume_group_details' ) :
//            DT_Zume_Core::check_for_update( $post->ID, 'group' );
            $record = get_post_meta( $post->ID, 'zume_raw_record', true );

            ?>
            <label class="section-header"><?php esc_html_e( 'Zúme Info' ) ?></label>

            <style>
                #zume-tabs li a { padding: 1rem 1rem; }
            </style>

            <ul class="tabs" data-tabs id="zume-tabs">
                <li class="tabs-title is-active"><a href="#sessions" aria-selected="true"><?php esc_html_e( 'Sessions' ) ?></a></li>
                <li class="tabs-title"><a href="#info" data-tabs-target="info"><?php esc_html_e( 'Info' ) ?></a></li>
                <li class="tabs-title"><a href="#map" data-tabs-target="map"><?php esc_html_e( 'Map' ) ?></a></li>
                <?php if ( user_can( get_current_user_id(), 'manage_dt' ) ) : ?>
                    <li class="tabs-title"><a data-tabs-target="raw" href="#raw"><?php esc_html_e( 'Raw' ) ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tabs-content" data-tabs-content="zume-tabs">
            <!-- Sessions Tab -->
            <div class="tabs-panel is-active" id="sessions">
                <style>
                    .date-text {
                        font-size:.8em;
                    }
                </style>
                <?php
                if ( $record ) { ?>

                    <!-- sessions -->
                    <button class="button <?php echo esc_html( $record['session_1'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 1' ) ?></strong>
                        <?php echo $record['session_1_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_1_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_2'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 2' ) ?></strong>
                        <?php echo $record['session_2_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_2_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_3'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 3' ) ?></strong>
                        <?php echo $record['session_3_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_3_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_4'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 4' ) ?></strong>
                        <?php echo $record['session_4_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_4_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_5'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 5' ) ?></strong>
                        <?php echo $record['session_5_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_5_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_6'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 6' ) ?></strong>
                        <?php echo $record['session_6_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_6_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_7'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 7' ) ?></strong>
                        <?php echo $record['session_7_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_7_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_8'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 8' ) ?></strong>
                        <?php echo $record['session_8_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_8_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_9'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 9' ) ?></strong>
                        <?php echo $record['session_9_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_9_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>
                    <button class="button <?php echo esc_html( $record['session_10'] ? 'success' : 'hollow' ) ?> expanded" type="button">
                        <strong><?php echo esc_html( 'Session 10' ) ?></strong>
                        <?php echo $record['session_10_complete'] ? '<br><span class="date-text">' . esc_html( date( 'M j, Y', strtotime( $record['session_10_complete'] ) ) ) . '</span>' : ''  ?>
                    </button>

                <?php } // endif ?>
            </div>

            <!-- Info box -->
            <div class="tabs-panel" id="info" style="min-height: 375px;">

                <dl>

                    <?php if ( isset( $record['members'] ) && ! empty( $record['members'] ) ) :
                        ?>
                        <dt>
                            <?php esc_html_e( 'Members' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( $record['members'] ) ?>
                        </dd>
                    <?php endif; ?>

                    <?php if ( isset( $record['coleaders_accepted'] ) && ! empty( $record['coleaders_accepted'] ) ) :
                        ?>
                        <dt>
                            <?php esc_html_e( 'Coleaders' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( is_array( $record['coleaders_accepted'] ) ? count( $record['coleaders_accepted'] ) : '' ) ?>
                        </dd>
                    <?php endif; ?>

                    <?php if ( isset( $record['meeting_time'] ) && ! empty( $record['meeting_time'] ) ) :
                        ?>
                        <dt>
                            <?php esc_html_e( 'Meeting Time' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( $record['meeting_time'] ) ?>
                        </dd>
                    <?php endif; ?>

                    <?php if ( isset( $record['created_date'] ) && ! empty( $record['created_date'] ) ) :
                        $mdy = DateTime::createFromFormat( 'Y-m-d H:i:s', $record['created_date'] )->format( 'm/d/Y' );
                        ?>
                        <dt>
                            <?php esc_html_e( 'Group Start Date' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( $mdy ) ?>
                        </dd>
                    <?php endif; ?>

                    <?php if ( isset( $record['last_modified_date'] ) && ! empty( $record['last_modified_date'] ) ) :
                        $mdy = DateTime::createFromFormat( 'Y-m-d H:i:s', $record['last_modified_date'] )->format( 'm/d/Y' );
                        ?>
                        <dt>
                            <?php esc_html_e( 'Last Active' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( $mdy ) ?>
                        </dd>
                    <?php endif; ?>

                    <?php if ( isset( $record['closed'] ) ) :
                        ?>
                        <dt>
                            <?php esc_html_e( 'Status' ) ?>:
                        </dt>
                        <dd>
                            <?php echo esc_attr( empty( $record['closed'] ) ? __( 'Open' ) : __( 'Closed' ) ) ?>
                        </dd>
                    <?php endif; ?>



                </dl>

            </div>

            <!-- Map Tab-->
            <div class="tabs-panel" id="map">
                <?php
                $raw_location = [];
                $show = true;
                if ( isset( $record['raw_location'] ) && ! empty( $record['raw_location'] ) ) {
                    $raw_location = $record['raw_location'];
                    $source = 'from user';
                } elseif ( isset( $record['ip_raw_location'] ) && ! empty( $record['ip_raw_location'] ) ) {
                    $raw_location = $record['ip_raw_location'];
                    $source = 'from ip address';
                } else {
                    $show = false;
                }

                if ( $show ) {
                    $lat = Disciple_Tools_Google_Geocode_API::parse_raw_result( $raw_location, 'lat' );
                    $lng = Disciple_Tools_Google_Geocode_API::parse_raw_result( $raw_location, 'lng' );
                    $address = Disciple_Tools_Google_Geocode_API::parse_raw_result( $raw_location, 'formatted_address' );

                    if ( empty( $lng ) || empty( $lat ) ) :
                        echo '<p>' . esc_html__( 'No map info gathered.' ) . '</p>';
                    else :
                        ?>

                        <p><?php echo esc_html( $address ) ?> <span
                                class="text-small grey">( <?php echo esc_html( $source ) ?> )</span></p>
                        <a id="map-reveal" data-open="<?php echo esc_attr( md5( $address ?? 'none' ) ) ?>"><img
                                src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo esc_attr( $lat ) . ',' . esc_attr( $lng ) ?>&zoom=6&size=640x640&scale=1&markers=color:red|<?php echo esc_attr( $lat ) . ',' . esc_attr( $lng ) ?>&key=<?php echo esc_attr( Disciple_Tools_Google_Geocode_API::key() ); ?>"/></a>
                        <p class="center"><a
                                data-open="<?php echo esc_attr( md5( $address ?? 'none' ) ) ?>"><?php esc_html_e( 'click to show large map' ) ?></a>
                        </p>

                        <div class="reveal large" id="<?php echo esc_attr( md5( $address ?? 'none' ) ) ?>"
                             data-reveal>
                            <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo esc_attr( $lat ) . ',' . esc_attr( $lng ) ?>&zoom=5&size=640x550&scale=2&markers=color:red|<?php echo esc_attr( $lat ) . ',' . esc_attr( $lng ) ?>&key=<?php echo esc_attr( Disciple_Tools_Google_Geocode_API::key() ); ?>"/>
                            <button class="close-button" data-close aria-label="Close modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <?php
                    endif;
                }
                ?>
            </div>
            <br clear="all" />

            <!-- Raw Tab-->
            <?php if ( user_can( get_current_user_id(), 'manage_dt' ) ) : ?>
            <div class="tabs-panel" id="raw" style="width: 100%;height: 300px;overflow-y: scroll;overflow-x:hidden;">
                <?php
                if ( $record ) {
                    foreach ( $record as $key => $value ) {
                        echo '<strong>' . esc_attr( $key ) . ': </strong>' . esc_attr( maybe_serialize( $value ) ) . '<br>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <?php
        endif;

    }

    public function groups_filter_box( $sections, $post_type = '' ) {
        if ($post_type === "groups") {
            global $post;
//            if ( $post && get_post_meta( $post->ID, 'zume_raw_record', true ) ) {
            if ( $post ) {
                $sections[] = 'zume_group_details';
            }
        }
        return $sections;
    }

    public function register_fields( $fields, $post_type ) {
        if ( 'groups' === $post_type ) {
            $fields["zume_last_check"] = [
                "name" => 'Zume Last Check Field',
                "type" => "text",
                "default" => '',
                "hidden" => true,
            ];
            $fields["zume_raw_record"] = [
                "name" => 'Zume Raw Record Field',
                "type" => "text",
                "default" => '',
                "hidden" => true,
            ];
            $fields["zume_check_sum"] = [
                "name" => 'Zume Check Sum Field',
                "type" => "text",
                "default" => '',
                "hidden" => true,
            ];
            $fields["health_metrics"]["customizable"] = 'all';
        }
        if ( 'contacts' === $post_type ){
            $fields["seeker_path"]["customizable"] = 'all';
            $fields["milestones"]["customizable"] = 'all';
        }
        return $fields;
    }

    /**
     * This removes unnecissary zume data from the get_groups call loaded into the wpApiGroupsSettings javascript object
     * @param $fields
     *
     * @return mixed
     */
    public function remove_zume_from_post_array( $fields ) {
        if ( isset( $fields['zume_last_check'] ) ) {
            unset( $fields['zume_last_check'] );
        }
        if ( isset( $fields['zume_raw_record'] ) ) {
            unset( $fields['zume_raw_record'] );
        }
        if ( isset( $fields['zume_check_sum'] ) ) {
            unset( $fields['zume_check_sum'] );
        }
        if ( isset( $fields['zume_foreign_key'] ) ) {
            unset( $fields['zume_foreign_key'] );
        }
        return $fields;
    }

    public function __construct() {

        add_action( 'dt_details_additional_section', [ $this, 'group_detail_box' ] );
        add_filter( 'dt_details_additional_section_ids', [ $this, 'groups_filter_box' ], 999, 2 );
        add_filter( 'dt_custom_fields_settings', [ $this, 'register_fields' ], 999, 2 );
        add_filter( 'dt_groups_fields_post_filter', [ $this, 'remove_zume_from_post_array' ], 999, 1 );

    }
}
new DT_Zume_Hooks_Groups();