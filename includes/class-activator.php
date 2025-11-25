<?php 


if(!defined('ABSPATH')) exit;

class Activator {
    public static function activate(){
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        note_text text NOT NULL, 
        PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}







?>