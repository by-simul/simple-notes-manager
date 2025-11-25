<?php 

if(!defined('ABSPATH')) exit;

class Frontend{

    public function __construct(){
        add_shortcode('simple_notes',array($this,"show_notes"));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_styles'));
    }

    public function enqueue_frontend_styles() {
        wp_enqueue_style(
            'snm-frontend-style', 
            plugin_dir_url(__FILE__) . '../assets/css/style.css', 
            [],
            '1.0'
        );
    }

    public function show_notes(){
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';

        $notes = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC ");


        if(!$notes){
            return "<p> No data found.</p>";
        }

        $output = "<ul>";
        foreach ($notes as $note) {
            $output .= "<li>" . esc_html($note->note_text) . "</li>";
        }
        $output .= "</ul>";

        return $output;

    }
}

?>