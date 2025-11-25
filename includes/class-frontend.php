<?php 

if(!defined('ABSPATH')) exit;

class Frontend{

    public function __construct()
    {
       add_shortcode('simple_notes',array($this,"show_notes"));
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