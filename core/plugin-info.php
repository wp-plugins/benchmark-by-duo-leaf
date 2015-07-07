<?php

class dl_b_PluginInfo {

    /**
     * Properties
     */
    public $name;
    public $displayName;
    public $tableStatsName;

    /**
     * Constructor
     */
    public function __construct() {

        $this->name = "benchmark-by-duo-leaf";
        $this->displayName = "Benchmark by Duo Leaf";

        global $wpdb;

        $this->tableStatsName = $wpdb->prefix . str_replace('-', '_', $this->name . '_stats');
    }

}
