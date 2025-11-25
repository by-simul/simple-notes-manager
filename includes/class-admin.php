<?php 
if(!defined('ABSPATH')) exit;

class Admin{
    public function __construct() {
        add_action('admin_menu',array($this,'add_admin_menu'));
    }

    public function add_admin_menu(){
        add_menu_page(
            'Simple Notes',
            'Simple Notes',
            'manage_options',
            'simple-notes-manager',
            array($this,'admin_page_html')
        );
    }

   public function admin_page_html() {
    
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';

         // Form to add new note
        echo '<h1>Simple Notes Manager</h1>';
        echo '<form method="post">';
        echo '<input type="text" name="note_text" required>';
        echo '<input type="submit" value="Save">';
        echo '</form>';

        // Handle form submit for adding a note
        if (isset($_POST['note_text']) && !empty($_POST['note_text'])) {
            $note = sanitize_text_field($_POST['note_text']);
            $wpdb->insert($table, array('note_text' => $note));
            echo "<div class='updated'><p>Note saved!</p></div>";
        }

        // Handle note deletion
        if (isset($_GET['delete_note'])) {
            $id = intval($_GET['delete_note']);
            $wpdb->delete($table, array('id' => $id));
            echo "<div class='updated'><p>Note deleted!</p></div>";
        }


        // Display existing notes
        $notes = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
        if ($notes) {
            echo '<h2>Saved Notes</h2>';
            echo '<ul>';
            foreach ($notes as $note) {
                echo '<li>' . esc_html($note->note_text) . 
                     ' <a href="?page=simple-notes-manager&delete_note=' . $note->id . '" onclick="return confirm(\'Are you sure?\')">Delete</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No notes found.</p>';
        }
    }

    
   
}



?>