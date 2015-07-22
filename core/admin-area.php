<?php

class dl_b_AdminArea {

    /** @var dl_b_PluginInfo */
    public $pluginInfo;

    /**
     * Constructor
     */
    public function __construct($pluginInfo) {
        $this->pluginInfo = $pluginInfo;
        add_action('admin_menu', array(&$this, 'registerMenu'));
    }

    function registerMenu() {
        add_submenu_page('options-general.php', $this->pluginInfo->displayName, $this->pluginInfo->displayName, 'manage_options', $this->pluginInfo->name, array(&$this, 'adminPanel'));
        add_action('admin_enqueue_scripts', array(&$this, 'adminEnqueueScripts'));
    }

    function adminPanel() {

        $this->view = new stdClass();

        $this->adminRegisterScripts();

        include_once(WP_PLUGIN_DIR . '/' . $this->pluginInfo->name . '/actions/stats-list.php');
        
    }

    function adminEnqueueScripts() {
        wp_register_script('dl_acj_customJS', WP_PLUGIN_URL . '/' . $this->pluginInfo->name . '/assets/js/custom.js', array('jquery'), NULL);
        wp_register_script('dl_acj_bootstrap', WP_PLUGIN_URL . '/' . $this->pluginInfo->name . '/assets/js/bootstrap.min.js', array('jquery'), NULL);
        wp_enqueue_style('dl_acj_css_custom', WP_PLUGIN_URL . '/' . $this->pluginInfo->name . '/assets/css/custom.css', array(), null, 'all');
        wp_enqueue_style('dl_acj_css_bootstrap', WP_PLUGIN_URL . '/' . $this->pluginInfo->name . '/assets/css/bootstrap-iso.css', array(), null, 'all');
        wp_enqueue_style('dl_acj_css_bootstrap_theme', WP_PLUGIN_URL . '/' . $this->pluginInfo->name . '/assets/css/bootstrap-theme.css', array(), null, 'all');
    }

    function adminRegisterScripts() {
        wp_enqueue_script('dl_acj_customJS');
        wp_enqueue_script('dl_acj_bootstrap');
        wp_enqueue_script('dl_acj_css_custom');
        wp_enqueue_script('dl_acj_css_bootstrap');
        wp_enqueue_script('dl_acj_css_bootstrap_theme');
    }

}
