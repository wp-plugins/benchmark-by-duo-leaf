<?php

/**
 * Plugin Name: Benchmark by Duo Leaf
 * Plugin URI: http://DuoLeaf.com/
 * Version: 1.0.1
 * Author: Duo Leaf
 * Author URI: http://DuoLeaf.com/benchmark-wordpress-plugin/
 * Description: A simple way to check how long it takes to load pages to its users.
 * License: GPLv3 or later
 */
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/plugin-info.php');
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/stats.php');
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/admin-area.php');
require_once(WP_PLUGIN_DIR . '/benchmark-by-duo-leaf/core/injector.php');


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

    /** @var dl_b_PluginInfo */
    public $pluginInfo;

    /** @var dl_b_AdminArea */
    public $adminArea;

    /** @var dl_b_Injector */
    public $injector;

    /**
     * Constructor
     */
    public function __construct($pluginInfo, $adminArea, $injector) {

        $this->pluginInfo = $pluginInfo;
        $this->adminArea = $adminArea;
        $this->injector = $injector;
    }

}

$dl_b_pluginInfo = new dl_b_PluginInfo();
$dl_b_AdminArea = new dl_b_AdminArea($dl_b_pluginInfo);
$dl_b_Injector = new dl_b_Injector($dl_b_pluginInfo);
$dl_b_LoadingTime = new dl_b_LoadingTime($dl_b_pluginInfo, $dl_b_AdminArea, $dl_b_Injector);


