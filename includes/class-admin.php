<?php
if(!defined('ABSPATH')) exit;

class Admin{

    
    public function __construct() {
        add_action('admin_menu', array($this,'add_admin_menu'));
    }

    public function enqueue_admin_styles(){
        wp_enqueue_style(
            'snm-admin-style', // handle
            plugin_dir_url(__FILE__) . '../assets/css/style.css', 
            [],
            '1.0'
        );
    }

    public function add_admin_menu(){
        add_menu_page(
            'Simple Notes',
            'Simple Notes',
            'manage_options',
            'simple-notes-manager',
            array($this,'list_notes_page'),
            'dashicons-edit',
            6
        );

        add_submenu_page(
            'simple-notes-manager',
            'Add Note',
            'Add Note',
            'manage_options',
            'add-note',
            array($this,'add_note_page')
        );

        add_submenu_page(
            'simple-notes-manager',
            'Note History',
            'Note History',
            'manage_options',
            'note-history',
            array($this,'history_page')
        );
    }

    // ---------- Add Note Page ----------
    public function add_note_page(){
    global $wpdb;
    $table = $wpdb->prefix . 'snm_notes';

    // Handle form submit safely
    if(isset($_POST['note_title'], $_POST['note_description'])){
        // sanitize input
        $title = sanitize_text_field($_POST['note_title']);
        $desc = sanitize_textarea_field($_POST['note_description']);

        // Insert and check for error
        $result = $wpdb->insert($table, [
            'note_title' => $title,
            'note_description' => $desc
        ]);

        if($result === false){
            echo "<div class='error'><p>Insert failed: " . $wpdb->last_error . "</p></div>";
        }else{
            echo "<div class='updated'><p>Note added successfully!</p></div>";
        }
    }

    ?>
    <div class="wrap add-note-wrap">
        <h1>Add Note</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Title</th>
                    <td><input type="text" name="note_title" required class="regular-text"></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><textarea name="note_description" rows="5" class="large-text" required></textarea></td>
                </tr>
            </table>
            <input type="submit" class="button button-primary" value="Save Note">
        </form>
    </div>
    <?php
}


    // ---------- List Notes Page ----------
    public function list_notes_page(){
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';

        // Handle deletion
        if(isset($_GET['delete_note'])){
            $id = intval($_GET['delete_note']);
            $wpdb->delete($table, ['id'=>$id]);
            echo "<div class='updated'><p>Note deleted!</p></div>";
        }

        $notes = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
         ?>
    <div class="wrap list-notes-wrap">
        <h1>All Notes</h1>

        <!-- Shortcode Display Section -->
        <div class="snm-shortcode-section" style="margin-bottom:20px; padding:10px; border:1px solid #ddd; background:#f9f9f9;">
            <p><strong>Use this shortcode to display notes on any page/post:</strong></p>
            <input type="text" value="[simple_notes]" readonly style="width:250px; padding:5px;" id="snm-shortcode-input">
            <button class="button" onclick="copyShortcode()">Copy</button>
        </div>

        <!-- Notes Table -->
        <?php if($notes): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($notes as $note): ?>
                    <tr>
                        <td><?php echo esc_html($note->note_title); ?></td>
                        <td><?php echo esc_html($note->note_description); ?></td>
                        <td><?php echo esc_html($note->created_at); ?></td>
                        <td>
                            <a href="?page=simple-notes-manager&delete_note=<?php echo $note->id; ?>" 
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No notes found.</p>
        <?php endif; ?>
    </div>

    <!-- Copy shortcode JS -->
    <script>
        function copyShortcode(){
            var copyText = document.getElementById("snm-shortcode-input");
            copyText.select();
            copyText.setSelectionRange(0, 99999); // mobile support
            document.execCommand("copy");
            alert("Shortcode copied: " + copyText.value);
        }
    </script>
    <?php
    }

    // ---------- History Page ----------
    public function history_page(){
        global $wpdb;
        $table = $wpdb->prefix . 'snm_notes';
        $notes = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");
        ?>
        <div class="wrap">
            <h1>Note History</h1>
            <?php if($notes): ?>
                <ul>
                    <?php foreach($notes as $note): ?>
                        <li>
                            <strong><?php echo esc_html($note->note_title); ?></strong> 
                            - <?php echo esc_html($note->note_description); ?> 
                            (<?php echo esc_html($note->created_at); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Not Ready Yeat</p>
            <?php endif; ?>
        </div>
        <?php
    }
}
