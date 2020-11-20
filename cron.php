<?php

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly


/**
 * Schedule daily closing of inactive, incomplete trainings
 */
if ( !wp_next_scheduled( 'zume_close_inactive_trainings' ) ) {
    wp_schedule_event( strtotime( 'tomorrow 1am' ), 'daily', 'zume_close_inactive_trainings' );
}
add_action( 'zume_close_inactive_trainings', 'zume_close_inactive_trainings' );
function zume_close_inactive_trainings(){
    Zume_Training_Extension::instance()->close_inactive_trainings();
}