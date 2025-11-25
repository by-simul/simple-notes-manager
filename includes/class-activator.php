<?php


if (!defined('ABSPATH')) exit;

class Activator{
    public static function activate()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';
        $charset_collate = $wpdb->get_charset_collate();

        // Drop old table if exists
        $wpdb->query("DROP TABLE IF EXISTS $table;");

        // Create new table
        $sql = "CREATE TABLE $table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        note_title varchar(255) NOT NULL,
        note_description text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
