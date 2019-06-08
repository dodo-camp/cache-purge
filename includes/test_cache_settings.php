<?php

    class Test_Cache_Settings {

        function __construct () {
            add_action("admin_init", array ($this, 'create_options_menu_entries'));
            add_action("admin_menu", array ($this, 'create_options_menu'));
        }

        function create_options_menu () {
            add_options_page (
                "Cache Settings",
                "Cache Cleaner",
                "manage_options",
                "cache_settings_options_menu",
                array ($this, "create_plugin_form_layout")
            );
        }

        function create_options_menu_entries () {
            register_setting (
                'test_cache_plugin_group',
                'test_cache_settings',
                array($this, "test_cache_sanitize")
            );
            add_settings_section (
                'test_cache_plugin_section_one',
                'Cache Cleaner Settings',
                array ($this, 'test_cache_section_one_callback'),
                'test_cache_plugin_group'
            );
            add_settings_field (
                'test_cache_plugin_path_field',
                "Cache Location",
                array ($this, 'render_test_cache_plugin_path_field'),
                'test_cache_plugin_group',
                'test_cache_plugin_section_one'
            );
        }

        function test_cache_section_one_callback () {
            echo "";
        }

        function render_test_cache_plugin_path_field () {
            $options = get_option( "test_cache_settings" );
            if ($options["test_cache_plugin_path_field"].trim() == "" ) {
                update_option("test_cache_settings", array("test_cache_plugin_path_field" => "/var/run/nginx-fastcgi-cache/"));
            }
            ?>
            <input type="text" name="test_cache_settings[test_cache_plugin_path_field]" id="test_cache_settings[test_cache_plugin_path_field]" value="<?php echo $options["test_cache_plugin_path_field"] == "" ? "/var/run/nginx-fastcgi-cache/" : $options["test_cache_plugin_path_field"]   ?>">
            <p class="description">Give absolute path of cache directory</p>
            <?php
        }

        function create_plugin_form_layout () {
            ?>
            <form method="POST" action="options.php">
                <?php
                    settings_fields("test_cache_plugin_group");
                    do_settings_sections("test_cache_plugin_group");
                    submit_button();
                ?>    
            </form>
            <?php
        }

    }

    new Test_Cache_Settings();

?>