<?php ob_start() ?>
    <?php if (!RASPI_MONITOR_ENABLED) : ?>
    <input type="submit" class="btn btn-outline btn-primary" name="SaveNTPsettings" value="<?php echo _("Save settings"); ?>" />
        <?php if ($__template_data['serviceStatus'] == 'down') : ?>
        <input type="submit" class="btn btn-success" name="StartNTPservice" value="<?php echo _("Start NTP service"); ?>" />
        <?php else : ?>
        <input type="submit" class="btn btn-warning" name="StopNTPservice" value="<?php echo _("Stop NTP service"); ?>" />
        <?php endif; ?>
    <?php endif ?>
  <?php $buttons = ob_get_clean(); ob_end_clean() ?>

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col">
              <i class="far fa-clock me-2"></i><?php echo _("NTP Server"); ?>
            </div>
            <div class="col">
              <button class="btn btn-light btn-icon-split btn-sm service-status float-end">
                <span class="icon text-gray-600"><i class="fas fa-circle service-status-<?php echo $__template_data['serviceStatus'] ?>"></i></span>
                <span class="text service-status"><?php echo $__template_data['serviceName'];?> <?php echo $__template_data['serviceStatus'] ?></span>
              </button>
            </div>
          </div><!-- /.row -->
        </div><!-- /.card-header -->
        <div class="card-body">
        <?php $status->showMessages(); ?>
          <form role="form" action="<?php echo $__template_data['action']; ?>" method="POST" class="needs-validation" novalidate>
            <?php echo \RaspAP\Tokens\CSRF::hiddenField(); ?>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" id="ntpsettingstab" href="#ntpsettings" data-bs-toggle="tab"><?php echo _("Settings"); ?></a></li>
                <li class="nav-item"><a class="nav-link" id="ntpadvancedstab" href="#ntpadvanced" data-bs-toggle="tab"><?php echo _("Advanced"); ?></a></li>
                <li class="nav-item"><a class="nav-link" id="ntploggingtab" href="#ntplogging" data-bs-toggle="tab"><?php echo _("Status"); ?></a></li>
                <li class="nav-item"><a class="nav-link" id="ntpabouttab" href="#ntpabout" data-bs-toggle="tab"><?php echo _("About"); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <?php echo renderTemplate("tabs/basic", $__template_data, $__template_data['pluginName']) ?>
              <?php echo renderTemplate("tabs/advanced", $__template_data, $__template_data['pluginName']) ?>
              <?php echo renderTemplate("tabs/logging", $__template_data, $__template_data['pluginName']) ?>
              <?php echo renderTemplate("tabs/about", $__template_data, $__template_data['pluginName']) ?>
            </div><!-- /.tab-content -->

            <?php echo $buttons ?>
          </form>
        </div><!-- /.card-body -->
      <div class="card-footer"> <?php echo _("Information provided by ".$__template_data['serviceName']); ?></div>
    </div><!-- /.card -->
  </div><!-- /.col-lg-12 -->
</div><!-- /.row -->

<!-- Custom Plugin JS -->
<script src="/app/js/plugins/NTPServer.js"></script>

