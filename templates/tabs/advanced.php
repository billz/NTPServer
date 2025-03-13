<!-- advanced tab -->
<div class="tab-pane fade" id="ntpadvanced">
  <h4 class="mt-3 mb-3"><?php echo _("Advanced") ;?></h4>
  <p><?php echo _("Use the <strong>Edit mode</strong> toggle to manually edit the current <code>ntp.config</code> configuration."); ?></p>
  <div class="row">
    <div class="col-md-6 mb-2">
      <div class="form-check form-switch">
        <input class="form-check-input" id="chxntpedit" name="ntpconfigedit" type="checkbox" value="1" />
        <label class="form-check-label" for="chxntpedit"><?php echo _("Edit mode"); ?></label>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="mb-3 col-md-8 mt-2">
      <textarea class="logoutput text-secondary" name="txtntpconfigraw" id="txtntpconfigraw" disabled><?php echo htmlspecialchars($__template_data['ntpConfig'], ENT_QUOTES); ?></textarea>
    </div>
  </div>
</div><!-- /.tab-pane -->

