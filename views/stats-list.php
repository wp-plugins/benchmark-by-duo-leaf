
<style type="text/css">
    .column-edit { width: 8%; }
    .column-delete { width: 10%; }
</style>

<div class="wrap">
    <h2><?php echo $this->pluginInfo->displayName; ?></h2>
    <hr />
    <?php $this->statsListTable->display(); ?>
</div>
