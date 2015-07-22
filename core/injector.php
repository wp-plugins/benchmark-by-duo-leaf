<?php

class dl_b_Injector {

    /** @var dl_b_PluginInfo */
    public $pluginInfo;
    
    public $start;

    /**
     * Constructor
     */
    public function __construct($pluginInfo) {
        $this->start = current_time('mysql', FALSE);
        $this->pluginInfo = $pluginInfo;
        add_action('wp_footer', array(&$this, 'injectJS'));
        add_action('wp_ajax_loading-time-callback', array(&$this, 'CallBackStats'));
        add_action('wp_ajax_nopriv_loading-time-callback', array(&$this, 'CallBackStats'));
    }

    function injectJS() {

        if (is_admin() OR is_feed() OR is_robots() OR is_trackback()) {
            return;
        }
        
        
        global $wpdb;

        $stats = array();
        $stats["id"] = 0;
        $stats["url"] = $_SERVER['REQUEST_URI'];
        $stats["start"] = $this->start;

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

        if ($qtdRegistros->Qtd > 500) {

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
