<?php

global $wpdb;

$this->stats = $wpdb->get_results('SELECT * FROM ' . $this->pluginInfo->tableStatsName . ' ORDER BY start DESC;');


include_once(WP_PLUGIN_DIR . '/' . $this->pluginInfo->name . '/views/stats-list.php');
