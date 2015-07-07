<?php

require_once(WP_PLUGIN_DIR . '/' . $this->pluginInfo->name .'/core/stats-list-table.php');

global $wpdb;

$resultsStats = $wpdb->get_results('SELECT * FROM ' . $this->pluginInfo->tableStatsName . ' ORDER BY start DESC;');

$this->statsListTable = new dl_b_ResourcesListTable($resultsStats, $this->pluginInfo);

$this->statsListTable->prepare_items();

include_once(WP_PLUGIN_DIR . '/' . $this->pluginInfo->name . '/views/stats-list.php');
