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



if ( !wp_next_scheduled( 'zume_transfer_trainings' ) ) {
    wp_schedule_event( strtotime( 'tomorrow 2am' ), 'daily', 'zume_transfer_trainings' );
}
add_action( 'zume_transfer_trainings', 'zume_transfer_trainings' );
function zume_transfer_trainings(){
    Zume_Training_Extension::instance()->resync_zume_and_global();
}

if ( !wp_next_scheduled( 'zume_install_completed_trainings' ) ) {
    wp_schedule_event( strtotime( 'tomorrow 3am' ), 'daily', 'zume_install_completed_trainings' );
}
add_action( 'zume_install_completed_trainings', 'zume_install_completed_trainings' );
function zume_install_completed_trainings(){
    Zume_Training_Extension::instance()->zume_install_completed_trainings();
}

