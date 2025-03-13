<!-- basic settings tab -->
<div class="tab-pane active" id="ntpsettings">
  <h4 class="mt-3"><?php echo ("NTP Server settings") ;?></h4>
  <div class="row">
    <div class="mb-3 col-lg-12 mt-2">
      <div class="row">
        <div class="col-md-6">
          <?php echo _("NTP daemon") ?>: <?php echo $__template_data['ntpDaemon'] ?><br />
          <?php echo _("Synchronized time") ?>:
          <pre class="mt-1"><?php echo $__template_data['ntpTime'] ?></pre>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <?php if ($__template_data['ntpServers'] !== null) : ?>
          <label for="js-ntp-servers"><?php echo _("NTP servers") ?></label>
          <?php endif; ?>
          <div class="js-ntp-servers">
            <?php foreach ($__template_data['ntpServers'] as $server): ?>
              <div class="mb-3 input-group input-group-sm js-dhcp-upstream-server">
                <input type="text" class="form-control" name="server[]" value="<?php echo $server ?>">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary js-remove-dhcp-upstream-server" type="button"><i class="fas fa-minus"></i></button>
                </div>
              </div>
            <?php endforeach ?>
          </div>

          <div class="mb-3">
            <label for="add-ntp-server-field"><?php echo _("Add an NTP server") ?></label>
            <p id="add-server-description">
              <small>
                <?php echo _("Specify a public NTP server or a private one on your local network. IPv4 and IPv6 address, or a fully qualified domain name (FQDN) are acceptable values."); ?>
                <?php echo _("Public NTP servers supporting Network Time Security (NTS) may be specified with the <code>nts</code> suffix."); ?>
                <?php echo sprintf(_("Examples of valid server entries include <code>%s</code>, <code>%s</code> and <code>%s</code>."), "127.127.1.0", "raspap.local", "time.cloudflare.com nts"); ?>
              </small>
            </p>
            <div class="input-group">
              <input type="text" class="form-control" id="add-ntp-server-field" aria-describedby="new-ntp-server" placeholder="<?php printf(_("e.g. %s"), "127.127.1.0") ?>">
              <div class="input-group-append">
                <button type="button" class="btn btn-outline-secondary js-add-ntp-server"><i class="fas fa-plus"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <template id="ntp-server">
      <div class="mb-3 input-group input-group-sm js-ntp-server">
        <input type="text" class="form-control" name="server[]" value="{{ server }}">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary js-remove-ntp-server" type="button"><i class="fas fa-minus"></i></button>
        </div>
      </div>
    </template>
  </div><!-- /.row -->
</div><!-- /.tab-pane | general tab -->

