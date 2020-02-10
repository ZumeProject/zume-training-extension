<?php

class DT_Zume_Hooks_Training {

    public function training_detail_box( $section ) {
        global $post;
        $zume_group_id = get_post_meta( $post->ID, 'zume_group_id', true );

        // does not group id
        if ( $section === 'zume_training_details' && ! $zume_group_id ) :
            $post_type = get_post_type();
            $post_settings = apply_filters( "dt_get_post_type_settings", [], $post_type );
            $dt_post = DT_Posts::get_post( $post_type, get_the_ID() );
            ?>
            <label class="section-header"><?php esc_html_e( 'Zúme.Training Site Activity' ) ?></label>

            <?php render_field_for_display( 'zume_group_id', $post_settings["fields"], $dt_post ) ?>

            <?php
        endif;

        // has group it
        if ( $section === 'zume_training_details' && $zume_group_id ) :
            $post_type = get_post_type();
            $dt_post = DT_Posts::get_post( $post_type, get_the_ID() );
            $record = $this->get_zume_group( $zume_group_id, $dt_post );
//            dt_write_log($record);
            ?>
            <label class="section-header"><?php esc_html_e( 'Zúme.Training Course' ) ?></label>

            <style>
                #zume-tabs li a { padding: 1rem 1rem; }
                .date-text {
                    font-size:.8em;
                }
            </style>

            <ul class="tabs" data-tabs id="zume-tabs">
                <li class="tabs-title is-active"><a href="#sessions" aria-selected="true"><?php esc_html_e( 'Sessions' ) ?></a></li>
                <li class="tabs-title"><a href="#members" data-tabs-target="members"><?php esc_html_e( 'Members' ) ?></a></li>
                <li class="tabs-title"><a href="#info" data-tabs-target="info"><?php esc_html_e( 'Info' ) ?></a></li>
                <?php if ( user_can( get_current_user_id(), 'manage_dt' ) ) : ?>
                    <li class="tabs-title"><a data-tabs-target="raw" href="#raw"><?php esc_html_e( 'Raw' ) ?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tabs-content" data-tabs-content="zume-tabs">
            <!-- Sessions Tab -->
            <div class="tabs-panel is-active" id="sessions">
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

            <!-- Members Tab-->
            <div class="tabs-panel" id="members" style="height: 375px;">
                <?php
                if ( ! empty( $record['coleaders'] ) ) {
                    ?>
                    <div class="grid-x">
                    <?php foreach ( $record['coleaders'] as $coleader ) : ?>
                        <div class="cell"><?php echo esc_html( $coleader ) ?><br><button class="button hollow small" disabled>Create Contact</button></div>
                    <?php endforeach; ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <br clear="all" />

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
                        $mdy = date('m/d/Y', strtotime( $record['last_modified_date'] ) );
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
            </div>

        <?php endif;

    }

    public function trainings_filter_box( $sections, $post_type = '' ) {
        if ($post_type === "trainings") {
            global $post;
            if ( $post ) {
                $sections[] = 'zume_training_details';
            }
        }
        return $sections;
    }

    public function register_fields( $fields, $post_type ) {
        if ( 'trainings' === $post_type ) {
            $fields['zume_group_id'] = [
                'name' => "Group ID",
                'type' => 'text',
                'default' => '',
                'show_in_table' => false
            ];
            $fields['zume_check_sum'] = [
                'name' => "Zume Group Check Sum",
                'type' => 'text',
                'default' => '',
                'show_in_table' => false
            ];

        }
        return $fields;
    }

    /**
     * @param $zume_group_id
     * @return bool|array
     */
    public function get_zume_group( $zume_group_id, $dt_post ) {
        global $wpdb;
        $raw_results = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = %s LIMIT 1", $zume_group_id ) );
        if ( $raw_results ) {
            $results = maybe_unserialize( $raw_results );
//            dt_write_log($dt_post);
            dt_write_log($results);


//            if ( ! isset( $dt_post['zume_check_sum'] ) || $dt_post['zume_check_sum'] !== hash('sha256', maybe_serialize( $raw_results ) ) ) {
                // process training details with DT record

                if ( $dt_post['title'] === '' /* test if it has a title */) {
                    $my_post = array(
                        'ID'           => $dt_post['ID'],
                        'post_title'   => $results['title'],
                    );
                    wp_update_post( $my_post );
                }
                if ( ! ( isset( $dt_post['start_date']['timestamp'] ) && ( date( "Y-m-d", strtotime( $dt_post['start_date']['timestamp'] ) ) === date( "Y-m-d", strtotime( $results['created_date'] ) ) ) ) /* test if title start date is same */) {
                    update_post_meta( $dt_post['ID'], 'start_date', strtotime( $results['created_date'] ) );
                }
                if ( ! ( isset( $dt_post['contact_count'] ) && $dt_post['contact_count'] === $results['members'] )  /* test if number of members same */) {
                    update_post_meta( $dt_post['ID'], 'contact_count', $results['members'] );
                }
                if ( false /* @todo test if all dates are logged */) {
                    dt_write_log('Need to update date list');
                }
                if ( true /* @todo test if address match */) {
                    dt_write_log('Need to update address');
                }

                update_post_meta( $dt_post['ID'], 'zume_check_sum', hash('sha256', maybe_serialize( $raw_results ) ) );
//            }

            return $results;
        } else {
            return false;
        }
    }

    /**
     * This removes unnecissary zume data from the get_trainings call loaded into the wpApiGroupsSettings javascript object
     * @param $fields
     *
     * @return mixed
     */
    public function remove_zume_from_post_array( $fields ) {
        return $fields;
    }

    public function __construct() {

        add_action( 'dt_details_additional_section', [ $this, 'training_detail_box' ] );
        add_filter( 'dt_details_additional_section_ids', [ $this, 'trainings_filter_box' ], 999, 2 );
        add_filter( 'dt_custom_fields_settings', [ $this, 'register_fields' ], 999, 2 );
        add_filter( 'dt_trainings_fields_post_filter', [ $this, 'remove_zume_from_post_array' ], 999, 1 );

    }
}
new DT_Zume_Hooks_Training();