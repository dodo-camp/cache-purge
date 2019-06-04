<?php

    class Test_Cache_Clean {

        function __construct () {
            add_action('admin_bar_menu', array($this, "add_cache_button"));
            add_action('admin_footer', array($this, 'clean_cache_linkage'));
            add_action( 'wp_ajax_cache_clean', array($this, 'clean_cache_functionality') );
        }

        function add_cache_button ($admin_bar) {
            $admin_bar -> add_menu (
                array(
                    'id' => 'cache-clean',
                    'title' => 'Clean Cache',
                    'href' => '#'
                )
            );
        }

        function clean_cache_linkage () {
            ?>
            <script type="text/javascript">
                const cleanCacheButton = document.querySelector("#wp-admin-bar-cache-clean > a");
                cleanCacheButton.addEventListener('click', event => {
                    var data = { 'action': 'cache_clean' };
                    jQuery.post(ajax_object.ajax_url, data, function(response) {
                        alert( response );
                    });
                });
            </script>
            <?php
        }

        function deleteDir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                  if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") 
                       $this -> deleteDir($dir."/".$object); 
                    else unlink ($dir."/".$object);
                  }
                }
                reset($objects);
                return rmdir($dir);
            } else {
                return false;
            }
        }

        function clean_cache_functionality () {
            $dirPath = realpath(plugin_dir_path(__FILE__) . "/../../../cache");
            $is_cache_deleted = $this -> deleteDir($dirPath);
            if ($is_cache_deleted === false) {
                echo "Cache not available";
            } else {
                echo "Cache deleted successfully";
            }
            wp_die();
        }

    }

    new Test_Cache_Clean();