<?php

class Test_Cache_Clean
{

    function __construct()
    {
        add_action('admin_bar_menu', array($this, "add_cache_button"), 100);
        add_action('admin_footer', array($this, 'clean_cache_linkage'));
        add_action('wp_ajax_cache_clean', array($this, 'clean_cache_functionality'));
        $options = get_option("test_cache_settings");
        if ($options["test_cache_plugin_autoclean_cache_checkbox"] == "autoclean") {
            add_action('save_post', array($this, 'auto_clean_cache'));
        } else {
            remove_action('save_post', array($this, 'auto_clean_cache'));
        }
    }

    function add_cache_button($admin_bar)
    {
        $admin_bar->add_node(
            array(
                'id' => 'cache-clean',
                'class' => '',
                'title' => '
                        <div>
                            <img src="https://serverguy.com/wp-content/uploads/2018/03/serverguy-favicon.png" style="height: 20px; width: 20px; position: relative; top: 5px; margin-right: 5px; background: white; border-radius: 10px;">
                            <span>Clear Cache</span>
                        </div>
                    ',
                'meta' => array(),
                'href' => get_site_url() . "/wp-admin/options-general.php?page=cache_settings_options_menu#"
            )
        );
    }

    function clean_cache_linkage()
    {
        ?>
    <script type="text/javascript">
        const cleanCacheButton = document.querySelector("#wp-admin-bar-cache-clean");
        cleanCacheButton.addEventListener('click', event => {
            var data = {
                'action': 'cache_clean'
            };
            jQuery.post(ajax_object.ajax_url, data, function(response) {
                alert(response);
            });
        });
    </script>
<?php
}

function deleteDir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    $this->deleteDir($dir . "/" . $object);
                else unlink($dir . "/" . $object);
            }
        }
        reset($objects);
        return rmdir($dir);
    } else {
        return false;
    }
}

function clean_cache_functionality()
{
    $options = get_option("test_cache_settings");
    if (trim($options["test_cache_plugin_path_field"]) == "") {
        update_option("test_cache_settings", array("test_cache_plugin_path_field" => "/var/run/nginx-fastcgi-cache/"));
    }
    $dirPath = realpath($options["test_cache_plugin_path_field"]);
    $this->deleteDir($dirPath);
    $total_items  = count(glob($dirPath + "/*", GLOB_ONLYDIR));
    if ($total_items < 1) {
        echo "Cache deleted successfully";
    } else {
        echo "Unable to delete the cache";
    }
    wp_die();
}

function auto_clean_cache()
{
    $options = get_option("test_cache_settings");
    if (trim($options["test_cache_plugin_path_field"]) == "") {
        update_option("test_cache_settings", array("test_cache_plugin_path_field" => "/var/run/nginx-fastcgi-cache/"));
    }
    $dirPath = realpath($options["test_cache_plugin_path_field"]);
    $this->deleteDir($dirPath);
}
}

new Test_Cache_Clean();
