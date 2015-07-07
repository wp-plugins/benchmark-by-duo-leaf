<?php

if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class dl_b_ResourcesListTable extends WP_List_Table {

    /** @var array */
    public $stats;

    /** @var dl_b_PluginInfo */
    public $pluginInfo;

    public function __construct($stats, $pluginInfo) {

        parent::__construct();

        $this->stats = $stats;
        $this->pluginInfo = $pluginInfo;
    }

    function get_columns() {
        $columns = array(
            'url' => 'Url',
            'start' => 'Start',
            'end' => 'End',
            'duration' => 'Duration'
        );
        return $columns;
    }

    function prepare_items() {

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->stats;
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'url':
                return $item->url;
            case 'start':
                return $item->start;
            case 'end':
                return $item->end;
            case 'duration':
                return strtotime($item->end) - strtotime($item->start);

                //return (new DateTime($item->start) ->diff(new DateTime($item->end));

            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

}
