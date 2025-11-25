<?php 

if(!defined('ABSPATH')) exit;

class Core {

    public function run(){

        if(is_admin()){
            require_once plugin_dir_path(__FILE__) . 'class-admin.php';
            new Admin;
        }

        require_once plugin_dir_path(__FILE__) . 'class-frontend.php';
        new Frontend;

    }

}

?>