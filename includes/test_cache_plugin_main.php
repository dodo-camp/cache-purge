<?php

    class Test_Cache_Plugin_Main {

        function __construct () {
            add_action( "wp_enqueue_scripts", array ($this, "enqueue_scripts") );
            add_action( "admin_enqueue_scripts", array ($this, 'enqueue_admin_scripts_plugin') );
        }

        function enqueue_scripts () {
            wp_enqueue_style( "styles-css", get_template_directory_uri() . "/css/styles.css" );
            wp_enqueue_script("main-js", get_template_directory_uri() . "/js/main.js", array(), "0.0.1", true);
        }

        function enqueue_admin_scripts_plugin () {
            wp_enqueue_script( "ajax-script", plugins_url( "../js/main.js", __FILE__ ), array('jquery'));
            wp_localize_script( 'ajax-script', 'ajax_object',  array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'action' => 'cache_clean'  ) );
        }

    }

    new Test_Cache_Plugin_Main();