<div class="bootstrap-iso">
    <h2><?php echo $this->pluginInfo->displayName; ?></h2>
    <hr />
    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">How it works?</div>
                <div class="panel-body">
                    <p>This plugin records how long it takes to load each page in your client's browser. That way you can identify which pages need improvements in terms of performance.</p>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-th-list"></span> Stats
                </div> 
                <div class="panel-body">
                    <?php if (Count($this->stats) != 0) { ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-xs-5">Url</th>
                                    <th class="col-xs-2">Start</th>
                                    <th class="col-xs-2">End</th>
                                    <th class="col-xs-1">Duration</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($this->stats as $stat) { ?>
                                    <tr>
                                        <td><?php echo $stat->url; ?></td>
                                        <td><?php echo $stat->start; ?></td>
                                        <td><?php echo $stat->end; ?></td>
                                        <td><?php echo (empty($stat->end)) ? '-' : strtotime($stat->end) - strtotime($stat->start); ?></td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <p>No records here yet.</javascriptp>
                        <?php } ?>
                </div>
            </div>
        </div>
        <?php include 'panel.php'; ?>
    </div>
</div>
