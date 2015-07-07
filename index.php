<?php

/**
 * Plugin Name: Benchmark by Duo Leaf
 * Plugin URI: http://DuoLeaf.com/
 * Version: 1.0.0
 * Author: Duo Leaf
 * Author URI: http://DuoLeaf.com/benchmark-wordpress-plugin/
 * Description: A simple way to check how long it takes to load pages to its users.
 * License: GPLv3 or later
 */
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/plugin-info.php');
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/storage-access.php');
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/stats.php');


register_activation_hook(__FILE__, 'dl_b_pluginActivation');

function dl_b_pluginActivation() {

    global $wpdb;

    $pluginInfo = new dl_b_PluginInfo();

    if ($wpdb->get_var("SHOW TABLES LIKE '$pluginInfo->tableStatsName'") != $pluginInfo->tableStatsName) {

        $sql = "CREATE TABLE `$pluginInfo->tableStatsName` (
                `id` INT( 11 ) NOT NULL AUTO_INCREMENT,
                `url` TEXT NOT NULL,
                `start` TIMESTAMP NULL,
                `end` TIMESTAMP NULL,
                PRIMARY KEY(id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

class dl_b_LoadingTime {

    /** @var dl_ift_PluginInfo */
    public $pluginInfo;

    /**
     * Constructor
     */
    public function __construct($pluginInfo) {
        $this->pluginInfo = $pluginInfo;

        add_action('wp_footer', array(&$this, 'injectJS'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('wp_ajax_loading-time-callback', array(&$this, 'CallBackStats'));
        add_action('wp_ajax_nopriv_loading-time-callback', array(&$this, 'CallBackStats'));
    }

    function adminPanelsAndMetaBoxes() {
        add_submenu_page('options-general.php', $this->pluginInfo->displayName, $this->pluginInfo->displayName, 'manage_options', $this->pluginInfo->name, array(&$this, 'adminPanel'));
    }

    /**
     * Output the Administration Panel
     * Save POSTed data from the Administration Panel into a WordPress option
     */
    function adminPanel() {

        $this->view = new stdClass();

        include_once(WP_PLUGIN_DIR . '/' . $this->pluginInfo->name . '/actions/resources-list.php');
    }

    /**
     * Inject JS into page 
     */
    function injectJS() {

        if (is_admin() OR is_feed() OR is_robots() OR is_trackback()) {
            return;
        }
        global $wpdb;

        $stats = array();
        $stats["id"] = 0;
        $stats["url"] = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $stats["start"] = current_time('mysql', FALSE);


        $wpdb->insert($this->pluginInfo->tableStatsName, $stats);

        $idStats = $wpdb->insert_id;

        echo '<script typt=\'script/javascript\'> jQuery(window).bind("load", function() {
                        var ajaxurl = \'' . admin_url('admin-ajax.php') . '\';
                        var data = {
                                \'action\': \'loading-time-callback\',
                                \'idStats\': ' . $idStats . '
                        };
                        jQuery.post(ajaxurl, data, function(response) {  });
        });</script>';



        $this->RemoveOldRecords();
    }

    function RemoveOldRecords() {

        global $wpdb;

        $qtdRegistros = $wpdb->get_row('SELECT COUNT(*) AS Qtd FROM ' . $this->pluginInfo->tableStatsName . ';');

        echo $qtdRegistros->Qtd;

        if ($qtdRegistros->Qtd > 50) {

            $qtdRemover = $qtdRegistros->Qtd - 50;

            $wpdb->query($wpdb->prepare('DELETE FROM `' . $this->pluginInfo->tableStatsName . '` ORDER BY start ASC LIMIT %d ;', $qtdRemover));
        }
    }

    function CallBackStats() {

        $idStats = intval($_POST['idStats']);

        $stats1 = array();
        $stats1['end'] = current_time('mysql', FALSE);

        global $wpdb;

        $wpdb->update($this->pluginInfo->tableStatsName, $stats1, array('id' => $idStats));

        echo $stats1['end'];

        wp_die(); // this is required to terminate immediately and return a proper response
    }

}

$dl_b_pluginInfo = new dl_b_PluginInfo();
$dl_b_LoadingTime = new dl_b_LoadingTime($dl_b_pluginInfo);


