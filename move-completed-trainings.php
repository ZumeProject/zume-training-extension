<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class Zume_Move_Completed_Trainings {
    public static function check_for_groups_ready_to_move() {
        // @todo query and test for groups needing to be moved
            //

        global $wpdb;
        $results = $wpdb->get_results(
            "
            
            "
            , ARRAY_A );
    }

    public static function add_sessions(){
        global $wpdb;
        // Get all records to process
        $zume_groups_raw = $wpdb->get_results(
            "SELECT user_id, meta_key as group_key, meta_value
                    FROM wp_usermeta 
                    WHERE meta_key LIKE 'zume_group_%'
                    AND ( meta_value LIKE '%\"session_9\";b:1%' OR meta_value LIKE '%\"session_10\";b:1%' );
            ", ARRAY_A );
        $trainings_raw = $wpdb->get_results(
            "
                    SELECT pm.post_id, pm.meta_value as group_key, 
                    s1.meta_value as session_1,  
                    s2.meta_value as session_2,
                    s3.meta_value as session_3,  
                    s4.meta_value as session_4,  
                    s5.meta_value as session_5,  
                    s6.meta_value as session_6,  
                    s7.meta_value as session_7,  
                    s8.meta_value as session_8,  
                    s9.meta_value as session_9,    
                    s10.meta_value as session_10
                    FROM wp_3_postmeta pm
                    LEFT JOIN wp_3_postmeta s1 ON pm.post_id=s1.post_id AND s1.meta_key = 'zume_sessions' AND s1.meta_value = 'session_1'
                    LEFT JOIN wp_3_postmeta s2 ON pm.post_id=s2.post_id AND s2.meta_key = 'zume_sessions' AND s2.meta_value = 'session_2'
                    LEFT JOIN wp_3_postmeta s3 ON pm.post_id=s3.post_id AND s3.meta_key = 'zume_sessions' AND s3.meta_value = 'session_3'
                    LEFT JOIN wp_3_postmeta s4 ON pm.post_id=s4.post_id AND s4.meta_key = 'zume_sessions' AND s4.meta_value = 'session_4'
                    LEFT JOIN wp_3_postmeta s5 ON pm.post_id=s5.post_id AND s5.meta_key = 'zume_sessions' AND s5.meta_value = 'session_5'
                    LEFT JOIN wp_3_postmeta s6 ON pm.post_id=s6.post_id AND s6.meta_key = 'zume_sessions' AND s6.meta_value = 'session_6'
                    LEFT JOIN wp_3_postmeta s7 ON pm.post_id=s7.post_id AND s7.meta_key = 'zume_sessions' AND s7.meta_value = 'session_7'
                    LEFT JOIN wp_3_postmeta s8 ON pm.post_id=s8.post_id AND s8.meta_key = 'zume_sessions' AND s8.meta_value = 'session_8'
                    LEFT JOIN wp_3_postmeta s9 ON pm.post_id=s9.post_id AND s9.meta_key = 'zume_sessions' AND s9.meta_value = 'session_9'
                    LEFT JOIN wp_3_postmeta s10 ON pm.post_id=s10.post_id AND s10.meta_key = 'zume_sessions' AND s10.meta_value = 'session_10'
                    WHERE pm.meta_key = 'zume_group_id';
            ", ARRAY_A );

        $trainings = [];
        $zume_groups = [];
        foreach( $trainings_raw as $item ) {
            $trainings[$item['group_key']] = $item;
        }
        foreach( $zume_groups_raw as $item ) {
            $zume_groups[$item['group_key']] = $item;
        }
        foreach( $zume_groups as $group ) {
            if ( isset( $trainings[$group['group_key']] ) ) {
                $k = $group['group_key'];
                $t = $trainings[$k];
                $z = unserialize( $group['meta_value'] );

                if ( empty($t['session_1'] ) && ! empty( $z['session_1'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_1', false );
                }
                if ( empty($t['session_2'] ) && ! empty( $z['session_2'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_2', false );
                }
                if ( empty( $t['session_3'] ) && ! empty( $z['session_3'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_3', false );
                }
                if ( empty($t['session_4'] ) && ! empty( $z['session_4'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_4', false );
                }
                if ( empty($t['session_5'] ) && ! empty( $z['session_5'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_5', false );
                }
                if ( empty($t['session_6'] ) && ! empty( $z['session_6'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_6', false );
                }
                if ( empty( $t['session_7'] ) && ! empty( $z['session_7'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_7', false );
                }
                if ( empty($t['session_8'] ) && ! empty( $z['session_8'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_8', false );
                }
                if ( empty( $t['session_9'] ) && ! empty( $z['session_9'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_9', false );
                }
                if ( empty( $t['session_10'] ) && ! empty( $z['session_10'] ) ) {
                    add_post_meta( $t['post_id'], 'zume_sessions', 'session_10', false );
                }

            }
        }
    }
}
